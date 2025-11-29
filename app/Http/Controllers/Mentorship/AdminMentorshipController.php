<?php

namespace App\Http\Controllers\Mentorship;

use App\Contracts\Repositories\MentorshipRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Services\MentorshipService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminMentorshipController extends Controller
{
    public function __construct(
        private MentorshipService $mentorshipService,
        private MentorshipRepositoryInterface $mentorshipRepository
    ) {}

    /**
     * Display all mentorship requests for admin
     */
    public function index(Request $request)
    {
        $filters = $request->only(['status', 'search']);
        $requests = $this->mentorshipRepository->getAll($filters);
        $pending = $this->mentorshipRepository->getPendingAdminApproval();
        $statistics = $this->mentorshipService->getStatistics(auth()->user());

        return Inertia::render('Admin/Mentorship/Index', [
            'requests' => $requests, // Paginator is automatically handled by Inertia
            'pending' => $pending->values()->all(),
            'statistics' => $statistics,
            'filters' => $filters,
        ]);
    }

    /**
     * Display a specific mentorship request for admin review
     */
    public function show(int $id)
    {
        $mentorshipRequest = $this->mentorshipRepository->getById($id);

        if (!$mentorshipRequest) {
            abort(404, 'Mentorship request not found');
        }

        return Inertia::render('Admin/Mentorship/Show', [
            'mentorshipRequest' => $mentorshipRequest,
        ]);
    }

    /**
     * Give final approval to a mentorship request (admin level)
     */
    public function approve(Request $request, int $id)
    {
        $validated = $request->validate([
            'response' => 'nullable|string|max:500',
        ]);

        $mentorshipRequest = $this->mentorshipRepository->getById($id);

        if (!$mentorshipRequest) {
            abort(404, 'Mentorship request not found');
        }

        try {
            $this->mentorshipService->adminApprove(
                $mentorshipRequest,
                auth()->user(),
                $validated['response'] ?? null
            );

            return back()->with([
                'message' => 'Mentorship request approved! The mentorship is now active.',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            return back()->with([
                'message' => $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    /**
     * Reject a mentorship request
     */
    public function reject(Request $request, int $id)
    {
        $validated = $request->validate([
            'reason' => 'required|string|min:10|max:500',
        ]);

        $mentorshipRequest = $this->mentorshipRepository->getById($id);

        if (!$mentorshipRequest) {
            abort(404, 'Mentorship request not found');
        }

        try {
            $this->mentorshipService->reject(
                $mentorshipRequest,
                auth()->user(),
                $validated['reason']
            );

            return back()->with([
                'message' => 'Mentorship request rejected.',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            return back()->with([
                'message' => $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    /**
     * End an active mentorship
     */
    public function end(int $id)
    {
        $mentorshipRequest = $this->mentorshipRepository->getById($id);

        if (!$mentorshipRequest) {
            abort(404, 'Mentorship request not found');
        }

        try {
            $this->mentorshipService->endMentorship($mentorshipRequest, auth()->user());

            return back()->with([
                'message' => 'Mentorship ended successfully.',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            return back()->with([
                'message' => $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }
}
