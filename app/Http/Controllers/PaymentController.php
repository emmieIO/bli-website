<?php

namespace App\Http\Controllers;

use App\Contracts\Services\PaymentGatewayInterface;
use App\Models\Event;
use App\Services\Event\EventParticipantStateService;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

/**
 * Controller for handling payment HTTP requests
 * Responsible for: Request validation, response formatting, routing
 * Business logic delegated to: PaymentService, PaymentGatewayInterface
 */
class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
        private PaymentGatewayInterface $paymentGateway,
        private EventParticipantStateService $participantStateService
    ) {}

    /**
     * Show checkout page for an event
     */
    public function checkoutEvent(Event $event)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with([
                'message' => 'Please login to register for this event',
                'type' => 'info'
            ]);
        }

        $user = auth()->user();
        $checkoutStatus = $this->paymentService->canCheckoutEvent($user, $event);

        if (! $checkoutStatus['can_checkout']) {
            $redirectRoute = match ($checkoutStatus['reason']) {
                'already_confirmed', 'already_waitlisted' => route('user.events.show', $event->slug),
                default => route('events.show', $event->slug),
            };

            return redirect($redirectRoute)->with([
                'message' => $checkoutStatus['message'],
                'type' => 'info'
            ]);
        }

        return Inertia::render('Events/Checkout', [
            'event' => $event,
            'paystackPublicKey' => $this->paymentGateway->getPublicKey(),
        ]);
    }

    /**
     * Initialize event payment with Paystack
     */
    public function initializeEventPayment(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'nullable|string',
        ]);

        $user = auth()->user();

        $checkoutStatus = $this->paymentService->canCheckoutEvent($user, $event);

        if (! $checkoutStatus['can_checkout']) {
            return response()->json([
                'success' => false,
                'message' => $checkoutStatus['message'],
            ], 422);
        }

        try {
            $result = $this->paymentService->initializeEventPayment($user, $event, $validated);

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('Event payment initialization failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'event_id' => $event->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to initialize payment. Please try again.',
            ], 500);
        }
    }

    /**
     * Handle payment callback from Paystack
     */
    public function callback(Request $request)
    {
        $txRef = $request->query('reference');

        if (!$txRef) {
            return redirect()
                ->route('user_dashboard')
                ->with([
                    'message' => 'Payment reference not found.',
                    'type' => 'error'
                ]);
        }

        // Security: Verify transaction belongs to authenticated user
        $transaction = \App\Models\Transaction::where('transaction_id', $txRef)
            ->where('user_id', auth()->id())
            ->first();

        if (!$transaction) {
            return redirect()
                ->route('user_dashboard')
                ->with([
                    'message' => 'Transaction not found or unauthorized.',
                    'type' => 'error'
                ]);
        }

        try {
            $result = $this->paymentService->verifyAndProcessPayment($txRef);

            if ($result['type'] === 'event') {
                $message = ($result['registration_status'] ?? null) === 'waitlisted'
                    ? 'Payment successful! You have been added to the event waitlist.'
                    : 'Payment successful! Your event registration is now confirmed.';

                return redirect()
                    ->route('user.events.show', $result['event']->slug)
                    ->with([
                        'message' => $message,
                        'type' => 'success'
                    ]);
            }

            return redirect()->route('transactions.index')->with([
                'message' => 'Payment processed.',
                'type' => 'success',
            ]);

        } catch (\Exception $e) {
            Log::error('Payment verification failed', [
                'error' => $e->getMessage(),
                'transaction_id' => $txRef,
            ]);

            $this->paymentService->markTransactionAsFailed($txRef);

            return $this->redirectAfterPaymentFailure($txRef, [
                'message' => 'Payment verification failed. Please contact support.',
                'type' => 'error'
            ]);
        }
    }

    /**
     * Handle Paystack webhook
     */
    public function webhook(Request $request)
    {
        $signature = $request->header('x-paystack-signature');
        $payload = $request->all();

        try {
            $this->paymentService->processWebhookPayment($payload, $signature);

            return response()->json(['message' => 'Webhook received'], 200);

        } catch (\Exception $e) {
            Log::error('Webhook processing error', [
                'error' => $e->getMessage(),
                'payload' => $payload,
            ]);

            return response()->json(['message' => 'Webhook processing failed'], 500);
        }
    }

    /**
     * Verify and process a pending payment
     */
    public function verifyPayment(string $reference)
    {
        try {
            // Get the transaction by payment reference
            $transaction = \App\Models\Transaction::where('payment_ref', $reference)
                ->where('user_id', auth()->id())
                ->first();

            if (!$transaction) {
                return redirect()->route('transactions.index')->with([
                    'message' => 'Transaction not found.',
                    'type' => 'error'
                ]);
            }

            // If already successful, redirect to appropriate page
            if ($transaction->status === 'successful') {
                if ($transaction->payable_type === Event::class) {
                    $registrationStatus = $transaction->payable
                        ? $this->participantStateService->registrationStatusForUser($transaction->payable, auth()->id())
                        : null;
                    $message = $registrationStatus === 'waitlisted'
                        ? 'Your payment was successful and you are currently on the event waitlist.'
                        : 'Your registration for this event is already confirmed.';

                    return redirect()->route('user.events.show', $transaction->payable->slug)->with([
                        'message' => $message,
                        'type' => 'info'
                    ]);
                }
                return redirect()->route('transactions.index')->with([
                    'message' => 'This payment has already been completed.',
                    'type' => 'info'
                ]);
            }

            // Try to verify the payment with Paystack
            $result = $this->paymentService->verifyAndProcessPayment($reference);

            if ($result['type'] === 'event') {
                $message = ($result['registration_status'] ?? null) === 'waitlisted'
                    ? 'Payment successful! You have been added to the event waitlist.'
                    : 'Payment successful! Your event registration is now confirmed.';

                return redirect()
                    ->route('user.events.show', $result['event']->slug)
                    ->with([
                        'message' => $message,
                        'type' => 'success'
                    ]);
            }

            return redirect()->route('transactions.index')->with([
                'message' => 'Payment successful.',
                'type' => 'success',
            ]);

        } catch (\Exception $e) {
            Log::error('Payment verification failed', [
                'error' => $e->getMessage(),
                'reference' => $reference,
            ]);

            return redirect()->route('transactions.index')->with([
                'message' => 'Unable to verify payment. Please contact support if you have been charged.',
                'type' => 'error'
            ]);
        }
    }

    /**
     * Helper to redirect after payment failures.
     */
    private function redirectAfterPaymentFailure(string $txRef, array $flashData)
    {
        $transaction = \App\Models\Transaction::where('transaction_id', $txRef)->first();

        if ($transaction && $transaction->payable_type === Event::class) {
            return redirect()->route('user.events.show', $transaction->payable->slug)->with($flashData);
        }

        return redirect()->route('transactions.index')->with($flashData);
    }
}
