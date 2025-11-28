<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Services\Payment\FlutterwaveService;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

/**
 * Controller for handling payment HTTP requests
 * Responsible for: Request validation, response formatting, routing
 * Business logic delegated to: PaymentService, FlutterwaveService
 */
class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
        private FlutterwaveService $flutterwaveService
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
            'flutterwavePublicKey' => $this->flutterwaveService->getPublicKey(),
        ]);
    }

    /**
     * Initialize payment with Flutterwave
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
     * Handle payment callback from Flutterwave
     */
    public function callback(Request $request)
    {
        $status = $request->query('status');
        $txRef = $request->query('tx_ref');
        $transactionId = $request->query('transaction_id');

        // Handle cancelled payment
        if ($status === 'cancelled') {
            $this->paymentService->markTransactionAsFailed($txRef);

            return $this->redirectToCoursePage($txRef, [
                'message' => 'Payment was cancelled',
                'type' => 'warning'
            ]);
        }

        // Handle successful payment
        if ($status === 'successful') {
            try {
                $result = $this->paymentService->verifyAndProcessPayment($txRef, $transactionId);

                return redirect()
                    ->route('courses.learn', $result['course']->slug)
                    ->with([
                        'message' => 'Payment successful! You are now enrolled in the course.',
                        'type' => 'success'
                    ]);

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

        return $this->redirectToCoursePage($txRef, [
            'message' => 'Payment status unknown. Please contact support.',
            'type' => 'warning'
        ]);
    }

    /**
     * Handle Flutterwave webhook
     */
    public function webhook(Request $request)
    {
        $signature = $request->header('verif-hash');

        if (!$this->flutterwaveService->verifyWebhookSignature($signature)) {
            Log::warning('Invalid webhook signature');
            return response()->json(['message' => 'Invalid signature'], 401);
        }

        $payload = $request->all();

        Log::info('Flutterwave webhook received', $payload);

        try {
            $this->paymentService->processWebhookPayment($payload);

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
