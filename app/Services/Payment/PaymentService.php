<?php

namespace App\Services\Payment;

use App\Models\Cart;
use App\Models\Course;
use App\Models\Transaction;
use App\Models\User;
use App\Services\Instructor\InstructorEarningsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Service for handling payment business logic
 * Responsible for: Payment processing, transaction management, course enrollment
 */
class PaymentService
{
    public function __construct(
        private PaystackService $paystackService,
        private InstructorEarningsService $earningsService
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
    public function createTransaction(User $user, ?Course $course, string $txRef, float $amount, array $metadata = []): Transaction
    {
        return Transaction::create([
            'user_id' => $user->id,
            'course_id' => $course?->id,
            'transaction_id' => $txRef,
            'amount' => $amount,
            'currency' => config('services.paystack.currency', 'NGN'),
            'status' => 'pending',
            'metadata' => $metadata,
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

            $transaction = $this->createTransaction($user, $course, $txRef, (float) $course->price);

            $paymentData = $this->paystackService->initializePayment([
                'email' => $customerData['email'],
                'amount' => $course->price * 100, // Convert to kobo
                'reference' => $txRef,
                'callback_url' => route('payment.callback'),
                'first_name' => $customerData['name'] ?? null, // Added name
                'phone' => $customerData['phone'] ?? null, // Added phone for consistency, though it's already validated as nullable
                'metadata' => [
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'transaction_id' => $transaction->id,
                    'type' => 'course',
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
    public function verifyAndProcessPayment(string $txRef): array
    {
        $transaction = Transaction::where('transaction_id', $txRef)->first();

        if (!$transaction) {
            throw new \Exception('Transaction not found');
        }

        if (!$transaction->isPending()) {
            if ($transaction->isSuccessful()) {
                return [
                    'success' => true,
                    'type' => $transaction->metadata['type'] ?? 'course',
                    'course' => $transaction->course,
                    'courses' => isset($transaction->metadata['course_ids']) ? Course::whereIn('id', $transaction->metadata['course_ids'])->get() : [],
                    'transaction' => $transaction,
                ];
            } else {
                throw new \Exception('Payment was not successful.');
            }
        }

        // Verify with Paystack
        $result = $this->paystackService->verifyTransaction($txRef);

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
            throw new \Exception('Payment amount or currency mismatch. Expected: ' . $localAmount . ' ' . $transaction->currency . ', Got: ' . $paystackAmount . ' ' . $data['currency']);
        }

        DB::beginTransaction();

        try {
            // Update transaction
            $transaction->update([
                'status' => 'successful',
                'payment_ref' => $data['reference'] ?? $txRef,
                'payment_type' => $data['channel'] ?? null,
                'metadata' => array_merge($transaction->metadata ?? [], ['payment_data' => $data]),
                'paid_at' => now(),
            ]);

            // Check if this is a cart purchase or single course purchase
            $isCartPurchase = isset($transaction->metadata['type']) && $transaction->metadata['type'] === 'cart';

            if ($isCartPurchase) {
                // Enroll user in all courses from cart
                $courseIds = $transaction->metadata['course_ids'] ?? [];
                foreach ($courseIds as $courseId) {
                    $course = Course::find($courseId);
                    if ($course) {
                        $this->enrollUserInCourse($transaction->user_id, $course);
                    }
                }

                // Note: For cart purchases with multiple instructors, earnings are recorded
                // individually per course through a separate process or manual distribution
                // This requires splitting the transaction amount based on course prices
                // TODO: Implement cart earnings distribution if needed

                // Clear the cart
                $cartId = $transaction->metadata['cart_id'] ?? null;
                if ($cartId) {
                    $cart = Cart::find($cartId);
                    if ($cart) {
                        $cart->clear();
                    }
                }

                DB::commit();

                return [
                    'success' => true,
                    'type' => 'cart',
                    'courses' => Course::whereIn('id', $courseIds)->get(),
                    'transaction' => $transaction,
                ];
            } else {
                // Single course purchase
                $course = $transaction->course;
                $this->enrollUserInCourse($transaction->user_id, $course);

                // Record instructor earnings
                $this->earningsService->recordEarningFromTransaction($transaction);

                DB::commit();

                return [
                    'success' => true,
                    'type' => 'course',
                    'course' => $course,
                    'transaction' => $transaction,
                ];
            }

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
        if (!$this->paystackService->verifyWebhookSignature(json_encode($payload), $signature)) {
            Log::warning('Invalid Paystack webhook signature');
            return;
        }

        if ($payload['event'] !== 'charge.success') {
            return;
        }

        $txRef = $payload['data']['reference'];
        $transaction = Transaction::where('transaction_id', $txRef)->first();

        if (!$transaction || !$transaction->isPending()) {
            return;
        }

        DB::beginTransaction();

        try {
            $transaction->update([
                'status' => 'successful',
                'payment_ref' => $payload['data']['reference'] ?? $txRef,
                'payment_type' => $payload['data']['channel'] ?? null,
                'metadata' => $payload['data'],
                'paid_at' => now(),
            ]);

            if ($transaction->course) {
                $this->enrollUserInCourse($transaction->user_id, $transaction->course);
            } else {
                // Handle cart purchase
                $courseIds = $transaction->metadata['course_ids'] ?? [];
                foreach ($courseIds as $courseId) {
                    $course = Course::find($courseId);
                    if ($course) {
                        $this->enrollUserInCourse($transaction->user_id, $course);
                    }
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

    /**
     * Initialize cart payment
     */
    public function initializeCartPayment(User $user, Cart $cart, array $customerData): array
    {
        DB::beginTransaction();

        try {
            $txRef = 'BLI_CART_' . time() . '_' . $user->id . '_' . $cart->id;
            $totalAmount = (float) $cart->total;
            $courseIds = $cart->items->pluck('course_id')->toArray();

            $metadata = [
                'type' => 'cart',
                'cart_id' => $cart->id,
                'course_ids' => $courseIds,
                'items' => $cart->items->filter(fn($item) => $item->course)->map(fn($item) => [
                    'course_id' => $item->course_id,
                    'course_title' => $item->course->title,
                    'price' => $item->price,
                ])->values()->toArray(),
            ];

            $transaction = $this->createTransaction($user, null, $txRef, $totalAmount, $metadata);

            $paymentData = $this->paystackService->initializePayment([
                'email' => $customerData['email'],
                'amount' => $totalAmount * 100, // Convert to kobo
                'reference' => $txRef,
                'callback_url' => route('payment.callback'),
                'metadata' => [
                    'user_id' => $user->id,
                    'cart_id' => $cart->id,
                    'transaction_id' => $transaction->id,
                    'type' => 'cart',
                ],
            ]);

            DB::commit();

            return [
                'success' => true,
                'payment_data' => $paymentData,
            ];

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Cart payment initialization failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'cart_id' => $cart->id,
            ]);

            throw $e;
        }
    }
}
