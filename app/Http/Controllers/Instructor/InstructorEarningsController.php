<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Services\Instructor\InstructorEarningsService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InstructorEarningsController extends Controller
{
    public function __construct(
        private InstructorEarningsService $earningsService
    ) {}

    /**
     * Display instructor earnings dashboard
     */
    public function index(Request $request)
    {
        $instructor = $request->user();

        // Get balance with automatic status update (lazy evaluation)
        $balance = $this->earningsService->getAvailableBalanceWithUpdate($instructor);

        // Get earnings history
        $earnings = $this->earningsService->getEarningsHistory($instructor, 15);

        // Get payout history
        $payouts = $this->earningsService->getPayoutHistory($instructor, 10);

        // Get configuration
        $config = [
            'platform_commission' => config('services.instructor_payouts.platform_commission', 20.0),
            'holding_period_days' => config('services.instructor_payouts.holding_period_days', 7),
            'minimum_payout' => config('services.instructor_payouts.minimum_payout', 5000),
        ];

        return Inertia::render('Instructor/Earnings/Index', [
            'balance' => $balance,
            'earnings' => $earnings,
            'payouts' => $payouts,
            'config' => $config,
        ]);
    }

    /**
     * Display payout request form
     */
    public function createPayout(Request $request)
    {
        $instructor = $request->user();

        // Get available balance
        $balance = $this->earningsService->getAvailableBalanceWithUpdate($instructor);

        return Inertia::render('Instructor/Earnings/RequestPayout', [
            'balance' => $balance,
            'minimumPayout' => config('services.instructor_payouts.minimum_payout', 5000),
        ]);
    }

    /**
     * Process payout request
     */
    public function storePayout(Request $request)
    {
        $validated = $request->validate([
            'payout_method' => 'required|in:bank_transfer,payoneer,manual',

            // Bank transfer validation (Nigerian banks)
            'bank_name' => [
                'required_if:payout_method,bank_transfer',
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9\s\-\&\.]+$/', // Only allow alphanumeric, spaces, hyphens, ampersands, dots
            ],
            'account_number' => [
                'required_if:payout_method,bank_transfer',
                'nullable',
                'regex:/^[0-9]{10}$/', // Nigerian bank accounts are exactly 10 digits
            ],
            'account_name' => [
                'required_if:payout_method,bank_transfer',
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-zA-Z\s\.\-\']+$/', // Only allow letters, spaces, dots, hyphens, apostrophes
            ],
            'bank_code' => [
                'nullable',
                'regex:/^[0-9]{3}$/', // Nigerian bank codes are 3 digits
            ],

            // Payoneer validation
            'payout_email' => [
                'required_if:payout_method,payoneer',
                'nullable',
                'email:rfc,dns', // Strict email validation with DNS check
                'max:255',
            ],

            // Additional details
            'additional_details' => [
                'nullable',
                'string',
                'max:1000', // Limit length
            ],
        ]);

        $result = $this->earningsService->requestPayout($request->user(), [
            'method' => $validated['payout_method'],
            'bank_name' => $validated['bank_name'] ?? null,
            'account_number' => $validated['account_number'] ?? null,
            'account_name' => $validated['account_name'] ?? null,
            'bank_code' => $validated['bank_code'] ?? null,
            'email' => $validated['payout_email'] ?? null,
            'additional_details' => $validated['additional_details'] ?? null,
        ]);

        if ($result['success']) {
            return redirect()->route('instructor.earnings.index')
                ->with('success', $result['message']);
        }

        return back()->withErrors(['error' => $result['message']]);
    }
}
