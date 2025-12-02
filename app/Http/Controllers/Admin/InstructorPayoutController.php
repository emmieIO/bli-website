<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstructorPayout;
use App\Services\Instructor\InstructorEarningsService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InstructorPayoutController extends Controller
{
    public function __construct(
        private InstructorEarningsService $earningsService
    ) {}

    /**
     * Display all payout requests
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = InstructorPayout::with(['instructor', 'earnings'])
            ->orderBy('requested_at', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $payouts = $query->paginate(20);

        // Get counts for filter tabs
        $counts = [
            'all' => InstructorPayout::count(),
            'pending' => InstructorPayout::where('status', 'pending')->count(),
            'processing' => InstructorPayout::where('status', 'processing')->count(),
            'completed' => InstructorPayout::where('status', 'completed')->count(),
            'failed' => InstructorPayout::where('status', 'failed')->count(),
        ];

        return Inertia::render('Admin/Payouts/Index', [
            'payouts' => $payouts,
            'counts' => $counts,
            'currentStatus' => $status,
        ]);
    }

    /**
     * Show payout details
     */
    public function show(InstructorPayout $payout)
    {
        $payout->load(['instructor', 'earnings.course']);

        return Inertia::render('Admin/Payouts/Show', [
            'payout' => $payout,
        ]);
    }

    /**
     * Mark payout as processing
     */
    public function markAsProcessing(InstructorPayout $payout)
    {
        if ($payout->status !== 'pending') {
            return back()->withErrors(['error' => 'Only pending payouts can be marked as processing.']);
        }

        $payout->markAsProcessing();

        return back()->with('success', 'Payout marked as processing.');
    }

    /**
     * Mark payout as completed
     */
    public function markAsCompleted(Request $request, InstructorPayout $payout)
    {
        $validated = $request->validate([
            'external_reference' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if (!in_array($payout->status, ['pending', 'processing'])) {
            return back()->withErrors(['error' => 'Only pending or processing payouts can be completed.']);
        }

        $payout->markAsCompleted($validated['external_reference'] ?? null);

        if (isset($validated['notes'])) {
            $payout->update(['notes' => $validated['notes']]);
        }

        // Mark all associated earnings as paid
        $payout->earnings()->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return back()->with('success', 'Payout marked as completed and earnings updated.');
    }

    /**
     * Mark payout as failed
     */
    public function markAsFailed(Request $request, InstructorPayout $payout)
    {
        $validated = $request->validate([
            'failure_reason' => 'required|string',
        ]);

        if (!in_array($payout->status, ['pending', 'processing'])) {
            return back()->withErrors(['error' => 'Only pending or processing payouts can be failed.']);
        }

        $payout->markAsFailed($validated['failure_reason']);

        // Return earnings to available status
        $payout->earnings()->update([
            'status' => 'available',
        ]);

        return back()->with('success', 'Payout marked as failed and earnings returned to available.');
    }

    /**
     * Cancel payout
     */
    public function cancel(InstructorPayout $payout)
    {
        if ($payout->status !== 'pending') {
            return back()->withErrors(['error' => 'Only pending payouts can be cancelled.']);
        }

        $payout->update(['status' => 'cancelled']);

        // Return earnings to available status
        $payout->earnings()->update([
            'status' => 'available',
        ]);

        return back()->with('success', 'Payout cancelled and earnings returned to available.');
    }
}
