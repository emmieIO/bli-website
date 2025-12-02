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
     */
    public function recordEarningFromTransaction(Transaction $transaction): ?InstructorEarning
    {
        // Only process successful transactions
        if (!$transaction->isSuccessful()) {
            return null;
        }

        // Get the course and instructor
        $course = $transaction->course;
        if (!$course || !$course->instructor_id) {
            Log::warning('Cannot record earnings: No course or instructor', [
                'transaction_id' => $transaction->id,
            ]);
            return null;
        }

        // Check if earning already exists
        $existing = InstructorEarning::where('transaction_id', $transaction->id)->first();
        if ($existing) {
            return $existing;
        }

        $platformCommission = config('services.instructor_payouts.platform_commission', 20.0);
        $holdingPeriodDays = config('services.instructor_payouts.holding_period_days', 7);

        $grossAmount = (float) $transaction->amount;
        $platformFee = round($grossAmount * ($platformCommission / 100), 2);
        $netAmount = $grossAmount - $platformFee;

        try {
            $earning = InstructorEarning::create([
                'instructor_id' => $course->instructor_id,
                'transaction_id' => $transaction->id,
                'course_id' => $course->id,
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
                'instructor_id' => $course->instructor_id,
                'net_amount' => $netAmount,
            ]);

            return $earning;
        } catch (\Exception $e) {
            Log::error('Failed to record instructor earning', [
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id,
            ]);
            return null;
        }
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
     * Mark pending earnings as available (run via scheduled job)
     */
    public function markPendingEarningsAsAvailable(): int
    {
        $count = InstructorEarning::where('status', 'pending')
            ->where('available_at', '<=', now())
            ->update(['status' => 'available']);

        if ($count > 0) {
            Log::info("Marked {$count} earnings as available for payout");
        }

        return $count;
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
