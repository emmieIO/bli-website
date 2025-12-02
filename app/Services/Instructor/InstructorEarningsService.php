<?php

namespace App\Services\Instructor;

use App\Models\Course;
use App\Models\InstructorEarning;
use App\Models\InstructorPayout;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Service for managing instructor earnings and payouts
 */
class InstructorEarningsService
{
    /**
     * Record earnings from a successful transaction
     * Supports both single course transactions and cart items
     */
    public function recordEarningFromTransaction(Transaction $transaction, ?Course $course = null, ?float $amount = null): ?InstructorEarning
    {
        // Only process successful transactions
        if (!$transaction->isSuccessful()) {
            return null;
        }

        // Determine course and amount
        // If explicit course is passed (cart scenario), use it. Otherwise use transaction's course.
        $targetCourse = $course ?? $transaction->course;
        $targetAmount = $amount ?? (float) $transaction->amount;

        if (!$targetCourse || !$targetCourse->instructor_id) {
            Log::warning('Cannot record earnings: No course or instructor', [
                'transaction_id' => $transaction->id,
                'course_id' => $targetCourse?->id,
            ]);
            return null;
        }

        // Check if earning already exists for this specific course in this transaction
        $existing = InstructorEarning::where('transaction_id', $transaction->id)
            ->where('course_id', $targetCourse->id)
            ->first();

        if ($existing) {
            return $existing;
        }

        $platformCommission = (float) config('services.instructor_payouts.platform_commission', 20.0);
        $holdingPeriodDays = (int) config('services.instructor_payouts.holding_period_days', 7);

        $grossAmount = $targetAmount;
        $platformFee = round($grossAmount * ($platformCommission / 100), 2);
        $netAmount = $grossAmount - $platformFee;

        try {
            $earning = InstructorEarning::create([
                'instructor_id' => $targetCourse->instructor_id,
                'transaction_id' => $transaction->id,
                'course_id' => $targetCourse->id,
                'gross_amount' => $grossAmount,
                'platform_fee' => $platformFee,
                'net_amount' => $netAmount,
                'platform_fee_percentage' => $platformCommission,
                'currency' => $transaction->currency,
                'status' => 'pending',
                'available_at' => now()->addDays($holdingPeriodDays),
            ]);

            Log::info('Instructor earning recorded', [
                'earning_id' => $earning->id,
                'instructor_id' => $targetCourse->instructor_id,
                'net_amount' => $netAmount,
                'type' => $course ? 'cart_item' : 'single_purchase'
            ]);

            return $earning;
        } catch (\Exception $e) {
            Log::error('Failed to record instructor earning', [
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id,
                'course_id' => $targetCourse->id,
            ]);
            return null;
        }
    }

    /**
     * Record earnings for cart purchase with multiple courses
     *
     * @param Transaction $transaction The cart transaction
     * @param array $cartItems Array of items with structure: [['course_id' => int, 'price' => float], ...]
     * @return array Array of created earnings
     */
    public function recordEarningsFromCart(Transaction $transaction, array $cartItems): array
    {
        if (!$transaction->isSuccessful()) {
            Log::warning('Cannot record cart earnings: Transaction not successful', [
                'transaction_id' => $transaction->id,
                'status' => $transaction->status,
            ]);
            return [];
        }

        $earnings = [];

        foreach ($cartItems as $item) {
            $courseId = $item['course_id'] ?? null;
            $price = $item['price'] ?? null;

            if (!$courseId || !$price) {
                Log::warning('Skipping cart item: Missing course_id or price', [
                    'transaction_id' => $transaction->id,
                    'item' => $item,
                ]);
                continue;
            }

            $course = Course::find($courseId);
            if (!$course) {
                Log::warning('Skipping cart item: Course not found', [
                    'transaction_id' => $transaction->id,
                    'course_id' => $courseId,
                ]);
                continue;
            }

            // Record earning for this specific course
            $earning = $this->recordEarningFromTransaction($transaction, $course, (float) $price);

            if ($earning) {
                $earnings[] = $earning;
            }
        }

        Log::info('Cart earnings recorded', [
            'transaction_id' => $transaction->id,
            'items_count' => count($cartItems),
            'earnings_created' => count($earnings),
        ]);

        return $earnings;
    }

    /**
     * Get available balance for an instructor
     */
    public function getAvailableBalance(User $instructor): array
    {
        $availableEarnings = InstructorEarning::where('instructor_id', $instructor->id)
            ->where('status', 'available')
            ->where('available_at', '<=', now())
            ->get();

        $pendingEarnings = InstructorEarning::where('instructor_id', $instructor->id)
            ->where('status', 'pending')
            ->get();

        return [
            'available' => $availableEarnings->sum('net_amount'),
            'pending' => $pendingEarnings->sum('net_amount'),
            'total_earned' => InstructorEarning::where('instructor_id', $instructor->id)->sum('net_amount'),
            'total_paid' => InstructorEarning::where('instructor_id', $instructor->id)
                ->where('status', 'paid')
                ->sum('net_amount'),
        ];
    }

    /**
     * Request a payout for available earnings
     */
    public function requestPayout(User $instructor, array $payoutDetails): array
    {
        $minimumPayout = config('services.instructor_payouts.minimum_payout', 5000);

        DB::beginTransaction();

        try {
            // Get available earnings
            $availableEarnings = InstructorEarning::where('instructor_id', $instructor->id)
                ->where('status', 'available')
                ->where('available_at', '<=', now())
                ->get();

            $totalAvailable = $availableEarnings->sum('net_amount');

            if ($totalAvailable < $minimumPayout) {
                return [
                    'success' => false,
                    'message' => "Minimum payout amount is " . number_format($minimumPayout, 2),
                ];
            }

            // Create payout request
            $payout = InstructorPayout::create([
                'instructor_id' => $instructor->id,
                'amount' => $totalAvailable,
                'currency' => $availableEarnings->first()->currency ?? 'NGN',
                'status' => 'pending',
                'payout_method' => $payoutDetails['method'] ?? 'bank_transfer',
                'bank_name' => $payoutDetails['bank_name'] ?? null,
                'account_number' => $payoutDetails['account_number'] ?? null,
                'account_name' => $payoutDetails['account_name'] ?? null,
                'bank_code' => $payoutDetails['bank_code'] ?? null,
                'payout_email' => $payoutDetails['email'] ?? null,
                'payout_details' => $payoutDetails['additional_details'] ?? null,
            ]);

            // Mark earnings as part of this payout
            foreach ($availableEarnings as $earning) {
                $earning->markAsPaid($payout);
            }

            DB::commit();

            Log::info('Payout requested', [
                'payout_id' => $payout->id,
                'instructor_id' => $instructor->id,
                'amount' => $totalAvailable,
            ]);

            // Notify Admins
            try {
                $admins = User::role('admin')->get();
                if ($admins->count() > 0) {
                    \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\InstructorPayoutRequestedNotification($payout));
                    Log::info('Payout notification sent to admins', ['count' => $admins->count()]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to send payout notification to admins', ['error' => $e->getMessage()]);
                // Don't fail the request if notification fails
            }

            return [
                'success' => true,
                'payout' => $payout,
                'message' => 'Payout request submitted successfully',
            ];

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Payout request failed', [
                'error' => $e->getMessage(),
                'instructor_id' => $instructor->id,
            ]);

            return [
                'success' => false,
                'message' => 'Failed to process payout request: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Mark pending earnings as available
     * This can be called on-demand (lazy evaluation) instead of scheduled jobs
     * Perfect for shared hosting environments where cron jobs are unreliable
     */
    public function markPendingEarningsAsAvailable(?int $instructorId = null): int
    {
        $query = InstructorEarning::where('status', 'pending')
            ->where('available_at', '<=', now());

        // If instructor ID provided, only update their earnings
        if ($instructorId) {
            $query->where('instructor_id', $instructorId);
        }

        $count = $query->update(['status' => 'available']);

        if ($count > 0) {
            Log::info("Marked {$count} earnings as available for payout", [
                'instructor_id' => $instructorId ?? 'all',
            ]);
        }

        return $count;
    }

    /**
     * Get available balance for an instructor with automatic status update
     * This ensures status is always current without needing cron jobs
     */
    public function getAvailableBalanceWithUpdate(User $instructor): array
    {
        // First, update any pending earnings that are now available
        $this->markPendingEarningsAsAvailable($instructor->id);

        // Then return the balance
        return $this->getAvailableBalance($instructor);
    }

    /**
     * Get earnings history for an instructor
     */
    public function getEarningsHistory(User $instructor, int $perPage = 15)
    {
        return InstructorEarning::where('instructor_id', $instructor->id)
            ->with(['course', 'transaction'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get payout history for an instructor
     */
    public function getPayoutHistory(User $instructor, int $perPage = 15)
    {
        return InstructorPayout::where('instructor_id', $instructor->id)
            ->orderBy('requested_at', 'desc')
            ->paginate($perPage);
    }
}
