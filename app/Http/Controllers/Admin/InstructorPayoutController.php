<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstructorPayout;
use App\Notifications\InstructorPayoutStatusChangedNotification;
use App\Services\Instructor\InstructorEarningsService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class InstructorPayoutController extends Controller
{
    use AuthorizesRequests;
    public function __construct(
        private InstructorEarningsService $earningsService
    ) {}

    /**
     * Display all payout requests
     */
    public function index(Request $request)
    {
        // Authorization check
        $this->authorize('viewAny', InstructorPayout::class);

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
        // Authorization check
        $this->authorize('view', $payout);

        $payout->load(['instructor', 'earnings.course']);

        return Inertia::render('Admin/Payouts/Show', [
            'payout' => $payout,
        ]);
    }

    /**
     * Mark payout as processing
     */
    public function markAsProcessing(Request $request, InstructorPayout $payout)
    {
        // Authorization check
        $this->authorize('markAsProcessing', $payout);

        if ($payout->status !== 'pending') {
            return back()->withErrors(['error' => 'Only pending payouts can be marked as processing.']);
        }

        $oldStatus = $payout->status;
        $payout->markAsProcessing();

        // Audit log
        Log::info('Payout marked as processing by admin', [
            'payout_id' => $payout->id,
            'payout_reference' => $payout->payout_reference,
            'admin_id' => $request->user()->id,
            'admin_name' => $request->user()->name,
            'admin_email' => $request->user()->email,
            'instructor_id' => $payout->instructor_id,
            'amount' => $payout->amount,
        ]);

        // Notify instructor
        try {
            $payout->instructor->notify(new InstructorPayoutStatusChangedNotification(
                $payout,
                $oldStatus,
                'processing'
            ));
        } catch (\Exception $e) {
            Log::error('Failed to send payout status notification', [
                'payout_id' => $payout->id,
                'error' => $e->getMessage(),
            ]);
        }

        return back()->with('success', 'Payout marked as processing.');
    }

    /**
     * Mark payout as completed
     */
    public function markAsCompleted(Request $request, InstructorPayout $payout)
    {
        // Authorization check
        $this->authorize('markAsCompleted', $payout);

        $validated = $request->validate([
            'external_reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        if (!in_array($payout->status, ['pending', 'processing'])) {
            return back()->withErrors(['error' => 'Only pending or processing payouts can be completed.']);
        }

        $oldStatus = $payout->status;
        $payout->markAsCompleted($validated['external_reference'] ?? null);

        if (isset($validated['notes'])) {
            $payout->update(['notes' => $validated['notes']]);
        }

        // Mark all associated earnings as paid
        $earningsCount = $payout->earnings()->count();
        $payout->earnings()->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // Audit log
        Log::info('Payout marked as completed by admin', [
            'payout_id' => $payout->id,
            'payout_reference' => $payout->payout_reference,
            'admin_id' => $request->user()->id,
            'admin_name' => $request->user()->name,
            'admin_email' => $request->user()->email,
            'instructor_id' => $payout->instructor_id,
            'amount' => $payout->amount,
            'external_reference' => $validated['external_reference'] ?? null,
            'earnings_updated' => $earningsCount,
        ]);

        // Notify instructor
        try {
            $payout->instructor->notify(new InstructorPayoutStatusChangedNotification(
                $payout,
                $oldStatus,
                'completed',
                $validated['external_reference'] ?? null
            ));
        } catch (\Exception $e) {
            Log::error('Failed to send payout status notification', [
                'payout_id' => $payout->id,
                'error' => $e->getMessage(),
            ]);
        }

        return back()->with('success', 'Payout marked as completed and earnings updated.');
    }

    /**
     * Mark payout as failed
     */
    public function markAsFailed(Request $request, InstructorPayout $payout)
    {
        // Authorization check
        $this->authorize('markAsFailed', $payout);

        $validated = $request->validate([
            'failure_reason' => 'required|string|max:1000',
        ]);

        if (!in_array($payout->status, ['pending', 'processing'])) {
            return back()->withErrors(['error' => 'Only pending or processing payouts can be failed.']);
        }

        $oldStatus = $payout->status;
        $payout->markAsFailed($validated['failure_reason']);

        // Return earnings to available status
        $earningsCount = $payout->earnings()->count();
        $payout->earnings()->update([
            'status' => 'available',
        ]);

        // Audit log
        Log::warning('Payout marked as failed by admin', [
            'payout_id' => $payout->id,
            'payout_reference' => $payout->payout_reference,
            'admin_id' => $request->user()->id,
            'admin_name' => $request->user()->name,
            'admin_email' => $request->user()->email,
            'instructor_id' => $payout->instructor_id,
            'amount' => $payout->amount,
            'failure_reason' => $validated['failure_reason'],
            'earnings_returned' => $earningsCount,
        ]);

        // Notify instructor
        try {
            $payout->instructor->notify(new InstructorPayoutStatusChangedNotification(
                $payout,
                $oldStatus,
                'failed',
                $validated['failure_reason']
            ));
        } catch (\Exception $e) {
            Log::error('Failed to send payout status notification', [
                'payout_id' => $payout->id,
                'error' => $e->getMessage(),
            ]);
        }

        return back()->with('success', 'Payout marked as failed and earnings returned to available.');
    }

    /**
     * Cancel payout
     */
    public function cancel(Request $request, InstructorPayout $payout)
    {
        // Authorization check
        $this->authorize('delete', $payout);

        if ($payout->status !== 'pending') {
            return back()->withErrors(['error' => 'Only pending payouts can be cancelled.']);
        }

        $oldStatus = $payout->status;
        $payout->update(['status' => 'cancelled']);

        // Return earnings to available status
        $earningsCount = $payout->earnings()->count();
        $payout->earnings()->update([
            'status' => 'available',
        ]);

        // Audit log
        Log::info('Payout cancelled by admin', [
            'payout_id' => $payout->id,
            'payout_reference' => $payout->payout_reference,
            'admin_id' => $request->user()->id,
            'admin_name' => $request->user()->name,
            'admin_email' => $request->user()->email,
            'instructor_id' => $payout->instructor_id,
            'amount' => $payout->amount,
            'earnings_returned' => $earningsCount,
        ]);

        // Notify instructor
        try {
            $payout->instructor->notify(new InstructorPayoutStatusChangedNotification(
                $payout,
                $oldStatus,
                'cancelled'
            ));
        } catch (\Exception $e) {
            Log::error('Failed to send payout status notification', [
                'payout_id' => $payout->id,
                'error' => $e->getMessage(),
            ]);
        }

        return back()->with('success', 'Payout cancelled and earnings returned to available.');
    }
}
