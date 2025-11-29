<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Services\Payment\PaystackService;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

/**
 * Controller for handling payment HTTP requests
 * Responsible for: Request validation, response formatting, routing
 * Business logic delegated to: PaymentService, PaystackService
 */
class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
        private PaystackService $paystackService
    ) {}

    /**
     * Show checkout page for a course
     */
    public function checkout(Course $course)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with([
                'message' => 'Please login to purchase this course',
                'type' => 'info'
            ]);
        }

        $user = auth()->user();
        $checkoutStatus = $this->paymentService->canCheckout($user, $course);

        if (!$checkoutStatus['can_checkout']) {
            $redirectRoute = $checkoutStatus['reason'] === 'free_course'
                ? route('courses.show', $course->slug)
                : route('courses.learn', $course->slug);

            return redirect($redirectRoute)->with([
                'message' => $checkoutStatus['message'],
                'type' => 'info'
            ]);
        }

        $course->load(['category', 'instructor']);

        return Inertia::render('Courses/Checkout', [
            'course' => $course,
            'paystackPublicKey' => $this->paystackService->getPublicKey(),
        ]);
    }

    /**
     * Initialize payment with Paystack
     */
    public function initializePayment(Request $request, Course $course)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'phone' => 'nullable|string',
        ]);

        $user = auth()->user();

        try {
            $result = $this->paymentService->initializePayment($user, $course, $validated);

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('Payment initialization failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'course_id' => $course->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to initialize payment. Please try again.',
            ], 500);
        }
    }

    /**
     * Initialize cart payment with Paystack
     */
    public function initializeCartPayment(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'phone' => 'nullable|string',
        ]);

        $user = auth()->user();
        $cart = $user->cart()->with('items.course')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty',
            ], 400);
        }

        try {
            $result = $this->paymentService->initializeCartPayment($user, $cart, $validated);

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('Cart payment initialization failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'cart_id' => $cart->id,
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
            return $this->redirectToCoursePage($txRef, [
                'message' => 'Payment reference not found.',
                'type' => 'error'
            ]);
        }

        try {
            $result = $this->paymentService->verifyAndProcessPayment($txRef);

            if ($result['type'] === 'cart') {
                return redirect()
                    ->route('dashboard')
                    ->with([
                        'message' => 'Payment successful! You are now enrolled in ' . count($result['courses']) . ' course(s).',
                        'type' => 'success'
                    ]);
            } else {
                return redirect()
                    ->route('courses.learn', $result['course']->slug)
                    ->with([
                        'message' => 'Payment successful! You are now enrolled in the course.',
                        'type' => 'success'
                    ]);
            }

        } catch (\Exception $e) {
            Log::error('Payment verification failed', [
                'error' => $e->getMessage(),
                'transaction_id' => $txRef,
            ]);

            $this->paymentService->markTransactionAsFailed($txRef);

            return $this->redirectToCoursePage($txRef, [
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
                if ($transaction->course) {
                    return redirect()->route('courses.learn', $transaction->course->slug)->with([
                        'message' => 'You are already enrolled in this course.',
                        'type' => 'info'
                    ]);
                }
                return redirect()->route('dashboard')->with([
                    'message' => 'This payment has already been completed.',
                    'type' => 'info'
                ]);
            }

            // Try to verify the payment with Paystack
            $result = $this->paymentService->verifyAndProcessPayment($reference);

            if ($result['type'] === 'cart') {
                return redirect()
                    ->route('dashboard')
                    ->with([
                        'message' => 'Payment successful! You are now enrolled in ' . count($result['courses']) . ' course(s).',
                        'type' => 'success'
                    ]);
            } else {
                return redirect()
                    ->route('courses.learn', $result['course']->slug)
                    ->with([
                        'message' => 'Payment successful! You are now enrolled in the course.',
                        'type' => 'success'
                    ]);
            }

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
     * Helper to redirect to course page based on transaction
     */
    private function redirectToCoursePage(string $txRef, array $flashData)
    {
        $transaction = \App\Models\Transaction::where('transaction_id', $txRef)->first();

        $route = $transaction && $transaction->course
            ? route('courses.show', $transaction->course->slug)
            : route('courses.index');

        return redirect($route)->with($flashData);
    }
}
