<?php

namespace App\Services\Payment;

use App\Contracts\Services\PaymentGatewayInterface;
use App\Models\Event;
use App\Models\Transaction;
use App\Models\User;
use App\Services\Event\EventParticipantStateService;
use App\Services\Event\EventRegistrationService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Service for handling payment business logic
 * Responsible for: Payment processing, transaction management, and purchase fulfillment
 */
class PaymentService
{
    public function __construct(
        private PaymentGatewayInterface $paymentGateway,
        private EventRegistrationService $eventRegistrationService,
        private EventParticipantStateService $eventParticipantStateService
    ) {}

    /**
     * Generate unique transaction reference
     */
    public function generateTransactionReference(User $user, Model $payable): string
    {
        $type = class_basename($payable);

        return 'BLI_'.strtoupper($type).'_'.time().'_'.$user->id.'_'.$payable->id;
    }

    /**
     * Create a new transaction record
     */
    public function createTransaction(User $user, ?Model $payable, string $txRef, float $amount, array $metadata = []): Transaction
    {
        return Transaction::create([
            'user_id' => $user->id,
            'payable_id' => $payable?->id,
            'payable_type' => $payable ? get_class($payable) : null,
            'transaction_id' => $txRef,
            'amount' => $amount,
            'currency' => config('services.paystack.currency', 'NGN'),
            'status' => 'pending',
            'metadata' => $metadata,
        ]);
    }

    private function getTransactionSubjectType(Transaction $transaction): string
    {
        $subjectType = $transaction->metadata['subject_type'] ?? null;

        if (is_string($subjectType) && $subjectType !== '') {
            return $subjectType;
        }

        if ($transaction->payable_type === Event::class || ($transaction->metadata['type'] ?? null) === 'event') {
            return 'event';
        }

        return 'payment';
    }

    private function isEventTransaction(Transaction $transaction): bool
    {
        return $this->getTransactionSubjectType($transaction) === 'event';
    }

    /**
     * Initialize event payment
     */
    public function initializeEventPayment(User $user, Event $event, array $customerData): array
    {
        DB::beginTransaction();

        try {
            $checkoutStatus = $this->canCheckoutEvent($user, $event);

            if (! $checkoutStatus['can_checkout']) {
                throw new \RuntimeException($checkoutStatus['message']);
            }

            $txRef = $this->generateTransactionReference($user, $event);

            $transaction = $this->createTransaction($user, $event, $txRef, (float) $event->entry_fee);

            $paymentData = $this->paymentGateway->initializePayment([
                'email' => $customerData['email'],
                'amount' => $event->entry_fee * 100, // Convert to kobo
                'reference' => $txRef,
                'callback_url' => route('payment.callback'),
                'first_name' => $customerData['name'] ?? null,
                'phone' => $customerData['phone'] ?? null,
                'metadata' => [
                    'user_id' => $user->id,
                    'event_id' => $event->id,
                    'payable_id' => $event->id,
                    'payable_type' => Event::class,
                    'transaction_id' => $transaction->id,
                    'subject_type' => 'event',
                    'checkout_context' => 'direct',
                ],
            ]);

            DB::commit();

            return [
                'success' => true,
                'payment_data' => $paymentData,
            ];

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Event payment initialization failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'event_id' => $event->id,
            ]);

            throw $e;
        }
    }

    /**
     * Check if user can checkout for an event
     */
    public function canCheckoutEvent(User $user, Event $event): array
    {
        if ((float) $event->entry_fee <= 0) {
            return [
                'can_checkout' => false,
                'reason' => 'free_event',
                'message' => 'This is a free event. Register directly instead of using checkout.',
            ];
        }

        if (! $event->isRegistrationOpen()) {
            return [
                'can_checkout' => false,
                'reason' => 'registration_closed',
                'message' => 'Registration is not open for this event.',
            ];
        }

        if ($event->end_date && now()->greaterThan($event->end_date)) {
            return [
                'can_checkout' => false,
                'reason' => 'event_ended',
                'message' => 'This event has already ended.',
            ];
        }

        $registrationStatus = $this->eventParticipantStateService->registrationStatusForUserEnum($event, $user->id);

        if ($registrationStatus === \App\Enums\EventRegistrationStatus::REGISTERED) {
            return [
                'can_checkout' => false,
                'reason' => 'already_confirmed',
                'message' => 'Your registration is already confirmed.',
            ];
        }

        if ($registrationStatus === \App\Enums\EventRegistrationStatus::WAITLISTED) {
            return [
                'can_checkout' => false,
                'reason' => 'already_waitlisted',
                'message' => 'You are already on the waitlist for this event.',
            ];
        }

        if ($this->eventParticipantStateService->hasReachedMaxRevokes($event, $user->id)) {
            return [
                'can_checkout' => false,
                'reason' => 'revoke_limit_reached',
                'message' => 'Registration failed. The event has reached its maximum number of registrations.',
            ];
        }

        if ($this->eventParticipantStateService->slotsRemaining($event) === 'Full') {
            return [
                'can_checkout' => false,
                'reason' => 'event_full',
                'message' => 'This event is currently full. Join the waitlist instead of proceeding to checkout.',
            ];
        }

        $successfulTransaction = Transaction::query()
            ->where('user_id', $user->id)
            ->where('payable_id', $event->id)
            ->where('payable_type', Event::class)
            ->where('status', 'successful')
            ->latest()
            ->first();

        if ($successfulTransaction) {
            return [
                'can_checkout' => false,
                'reason' => 'already_paid',
                'message' => 'You already have a completed payment for this event.',
            ];
        }

        $pendingTransaction = Transaction::query()
            ->where('user_id', $user->id)
            ->where('payable_id', $event->id)
            ->where('payable_type', Event::class)
            ->where('status', 'pending')
            ->latest()
            ->first();

        if ($pendingTransaction) {
            return [
                'can_checkout' => false,
                'reason' => 'pending_payment',
                'message' => 'You already have a pending payment for this event. Complete or verify that payment first.',
            ];
        }

        return [
            'can_checkout' => true,
        ];
    }

    /**
     * Verify and process payment
     */
    public function verifyAndProcessPayment(string $txRef): array
    {
        $transaction = Transaction::query()->where('transaction_id', $txRef)->first();

        if (! $transaction) {
            throw new \Exception('Transaction not found');
        }

        if (! $transaction->isPending()) {
            if ($transaction->isSuccessful()) {
                $subjectType = $this->getTransactionSubjectType($transaction);

                if ($this->isEventTransaction($transaction)) {
                    $event = $transaction->payable;

                    return [
                        'success' => true,
                        'type' => $subjectType,
                        'event' => $event,
                        'transaction' => $transaction,
                        'registration_status' => $event
                            ? $this->eventParticipantStateService->registrationStatusForUser($event, $transaction->user_id)
                            : null,
                    ];
                }

                return [
                    'success' => true,
                    'type' => $subjectType,
                    'transaction' => $transaction,
                ];
            } else {
                throw new \Exception('Payment was not successful.');
            }
        }

        // Verify with Paystack
        $result = $this->paymentGateway->verifyTransaction($txRef);

        Log::info('Paystack verification response', [
            'paystack_response' => $result,
            'local_transaction' => $transaction,
        ]);

        if ($result['data']['status'] !== 'success') {
            throw new \Exception('Payment verification failed');
        }

        $data = $result['data'];

        // Security: Verify exact amount and currency match (prevents overpayment attacks)
        $paystackAmount = (int) ($data['amount']);
        $localAmount = (int) ($transaction->amount * 100);

        if ($paystackAmount !== $localAmount || $data['currency'] !== $transaction->currency) {
            throw new \Exception('Payment amount or currency mismatch. Expected: '.$localAmount.' '.$transaction->currency.', Got: '.$paystackAmount.' '.$data['currency']);
        }

        DB::beginTransaction();

        try {
            // Update transaction
            $transaction->forceFill([
                'status' => 'successful',
                'payment_ref' => $data['reference'] ?? $txRef,
                'payment_type' => $data['channel'] ?? null,
                'metadata' => array_merge($transaction->metadata ?? [], ['payment_data' => $data]),
                'paid_at' => now(),
            ])->save();

            // Handle Event Payment
            if ($this->isEventTransaction($transaction)) {
                $event = $transaction->payable;
                if (! $event && isset($transaction->metadata['event_id'])) {
                    $event = Event::query()->find($transaction->metadata['event_id']);
                }

                if ($event) {
                    $registrationStatus = $this->eventRegistrationService->registerOrWaitlist($event, $transaction->user_id);
                } else {
                    $registrationStatus = false;
                }

                DB::commit();

                return [
                    'success' => true,
                    'type' => 'event',
                    'event' => $event,
                    'transaction' => $transaction,
                    'registration_status' => $registrationStatus?->value,
                ];
            }

            DB::commit();

            return [
                'success' => true,
                'type' => $this->getTransactionSubjectType($transaction),
                'transaction' => $transaction,
            ];

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Payment processing failed', [
                'error' => $e->getMessage(),
                'transaction_id' => $txRef,
            ]);

            throw $e;
        }
    }

    /**
     * Process webhook payment
     */
    public function processWebhookPayment(array $payload, string $signature): void
    {
        if (! $this->paymentGateway->verifyWebhookSignature(json_encode($payload), $signature)) {
            Log::warning('Invalid Paystack webhook signature');

            return;
        }

        if (($payload['event'] ?? null) !== 'charge.success') {
            return;
        }

        $txRef = $payload['data']['reference'];
        $transaction = Transaction::query()->where('transaction_id', $txRef)->first();

        if (! $transaction || ! $transaction->isPending()) {
            return;
        }

        DB::beginTransaction();

        try {
            $transaction->forceFill([
                'status' => 'successful',
                'payment_ref' => $payload['data']['reference'] ?? $txRef,
                'payment_type' => $payload['data']['channel'] ?? null,
                'metadata' => array_merge($transaction->metadata ?? [], ['webhook_data' => $payload['data']]),
                'paid_at' => now(),
            ])->save();

            // Handle Event Payment
            if ($this->isEventTransaction($transaction)) {
                $event = $transaction->payable;
                if (! $event && isset($transaction->metadata['event_id'])) {
                    $event = Event::query()->find($transaction->metadata['event_id']);
                }

                if ($event) {
                    $this->eventRegistrationService->registerOrWaitlist($event, $transaction->user_id);
                }
            }

            DB::commit();

            Log::info('Payment processed via Paystack webhook', [
                'transaction_id' => $txRef,
                'user_id' => $transaction->user_id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Paystack webhook processing failed', [
                'error' => $e->getMessage(),
                'transaction_id' => $txRef,
            ]);

            throw $e;
        }
    }

    /**
     * Mark transaction as failed
     */
    public function markTransactionAsFailed(string $txRef): void
    {
        $transaction = Transaction::query()->where('transaction_id', $txRef)->first();

        if ($transaction) {
            $transaction->markAsFailed();
        }
    }

}
