<?php

namespace App\Services\Payment;

use App\Models\Course;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Service for handling payment business logic
 * Responsible for: Payment processing, transaction management, course enrollment
 */
class PaymentService
{
    public function __construct(
        private FlutterwaveService $flutterwaveService
    ) {}

    /**
     * Generate unique transaction reference
     */
    public function generateTransactionReference(User $user, Course $course): string
    {
        return 'BLI_' . time() . '_' . $user->id . '_' . $course->id;
    }

    /**
     * Create a new transaction record
     */
    public function createTransaction(User $user, Course $course, string $txRef): Transaction
    {
        return Transaction::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'transaction_id' => $txRef,
            'flw_ref' => $txRef,
            'amount' => $course->price,
            'currency' => config('services.flutterwave.currency', 'USD'),
            'status' => 'pending',
        ]);
    }

    /**
     * Initialize payment
     */
    public function initializePayment(User $user, Course $course, array $customerData): array
    {
        DB::beginTransaction();

        try {
            $txRef = $this->generateTransactionReference($user, $course);

            $transaction = $this->createTransaction($user, $course, $txRef);

            $paymentData = $this->flutterwaveService->preparePaymentData([
                'tx_ref' => $txRef,
                'amount' => $course->price,
                'currency' => config('services.flutterwave.currency', 'USD'),
                'redirect_url' => route('payment.callback'),
                'customer' => [
                    'email' => $customerData['email'],
                    'name' => $user->name,
                    'phonenumber' => $customerData['phone'] ?? $user->phone ?? '',
                ],
                'customizations' => [
                    'title' => 'Course Purchase',
                    'description' => "Purchase of {$course->title}",
                    'logo' => asset('images/logo.png'),
                ],
                'meta' => [
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'transaction_id' => $transaction->id,
                ],
            ]);

            DB::commit();

            return [
                'success' => true,
                'payment_data' => $paymentData,
            ];

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Payment initialization failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'course_id' => $course->id,
            ]);

            throw $e;
        }
    }

    /**
     * Verify and process payment
     */
    public function verifyAndProcessPayment(string $txRef, string $transactionId): array
    {
        $transaction = Transaction::where('transaction_id', $txRef)->first();

        if (!$transaction) {
            throw new \Exception('Transaction not found');
        }

        $course = $transaction->course;

        // Verify with Flutterwave
        $result = $this->flutterwaveService->verifyTransaction($transactionId);

        if ($result['status'] !== 'success' || $result['data']['status'] !== 'successful') {
            throw new \Exception('Payment verification failed');
        }

        $data = $result['data'];

        // Verify amount and currency
        if ($data['amount'] < $transaction->amount || $data['currency'] !== $transaction->currency) {
            throw new \Exception('Payment amount mismatch');
        }

        DB::beginTransaction();

        try {
            // Update transaction
            $transaction->update([
                'status' => 'successful',
                'flw_ref' => $data['flw_ref'] ?? $txRef,
                'payment_type' => $data['payment_type'] ?? null,
                'metadata' => $data,
                'paid_at' => now(),
            ]);

            // Enroll user in course
            $this->enrollUserInCourse($transaction->user_id, $course);

            DB::commit();

            return [
                'success' => true,
                'course' => $course,
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
    public function processWebhookPayment(array $payload): void
    {
        if ($payload['event'] !== 'charge.completed' || $payload['data']['status'] !== 'successful') {
            return;
        }

        $txRef = $payload['data']['tx_ref'];
        $transaction = Transaction::where('transaction_id', $txRef)->first();

        if (!$transaction || !$transaction->isPending()) {
            return;
        }

        DB::beginTransaction();

        try {
            $transaction->update([
                'status' => 'successful',
                'flw_ref' => $payload['data']['flw_ref'] ?? $txRef,
                'payment_type' => $payload['data']['payment_type'] ?? null,
                'metadata' => $payload['data'],
                'paid_at' => now(),
            ]);

            $this->enrollUserInCourse($transaction->user_id, $transaction->course);

            DB::commit();

            Log::info('Payment processed via webhook', [
                'transaction_id' => $txRef,
                'user_id' => $transaction->user_id,
                'course_id' => $transaction->course_id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Webhook processing failed', [
                'error' => $e->getMessage(),
                'transaction_id' => $txRef,
            ]);

            throw $e;
        }
    }

    /**
     * Enroll user in course (if not already enrolled)
     */
    private function enrollUserInCourse(int $userId, Course $course): void
    {
        if (!$course->students()->where('user_id', $userId)->exists()) {
            $course->students()->attach($userId);
        }
    }

    /**
     * Check if user can checkout for a course
     */
    public function canCheckout(User $user, Course $course): array
    {
        // Check if course is free
        if ($course->is_free || $course->price <= 0) {
            return [
                'can_checkout' => false,
                'reason' => 'free_course',
                'message' => 'This is a free course. You can enroll directly.',
            ];
        }

        // Check if already enrolled
        if ($course->students()->where('user_id', $user->id)->exists()) {
            return [
                'can_checkout' => false,
                'reason' => 'already_enrolled',
                'message' => 'You are already enrolled in this course',
            ];
        }

        // Check if already has successful transaction
        $existingTransaction = Transaction::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'successful')
            ->first();

        if ($existingTransaction) {
            return [
                'can_checkout' => false,
                'reason' => 'already_purchased',
                'message' => 'You have already purchased this course',
            ];
        }

        return [
            'can_checkout' => true,
        ];
    }

    /**
     * Mark transaction as failed
     */
    public function markTransactionAsFailed(string $txRef): void
    {
        $transaction = Transaction::where('transaction_id', $txRef)->first();

        if ($transaction) {
            $transaction->markAsFailed();
        }
    }
}
