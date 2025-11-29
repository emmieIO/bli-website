<?php

namespace App\Http\Controllers\Mentorship;

use App\Contracts\Repositories\MentorshipRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Services\MentorshipExpirationService;
use App\Services\MentorshipService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StudentMentorshipController extends Controller
{
    public function __construct(
        private MentorshipService $mentorshipService,
        private MentorshipRepositoryInterface $mentorshipRepository,
        private MentorshipExpirationService $expirationService
    ) {}

    /**
     * Display student's mentorship requests
     */
    public function index()
    {
        $user = auth()->user();
        $requests = $this->mentorshipRepository->getForStudent($user);
        $statistics = $this->mentorshipService->getStatistics($user);
        $expiringWarnings = $this->expirationService->getExpiringSoonForUser($user->id, 'student');

        return Inertia::render('Mentorship/Student/Index', [
            'requests' => $requests->values()->all(),
            'statistics' => $statistics,
            'expiringWarnings' => $expiringWarnings,
        ]);
    }

    /**
     * Show form to request mentorship
     */
    public function create()
    {
        $instructors = $this->mentorshipService->getAvailableInstructors();

        return Inertia::render('Mentorship/Student/Create', [
            'instructors' => $instructors,
        ]);
    }

    /**
     * Store a new mentorship request
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'instructor_id' => 'required|exists:users,id',
            'message' => 'required|string|min:10|max:1000',
            'goals' => 'nullable|string|max:1000',
            'duration_type' => 'required|in:monthly,weekly,one-time',
            'duration_value' => 'required|integer|min:1|max:12',
        ]);

        try {
            $instructor = \App\Models\User::findOrFail($validated['instructor_id']);
            $mentorshipRequest = $this->mentorshipService->requestMentorship(
                auth()->user(),
                $instructor,
                $validated
            );

            return redirect()->route('student.mentorship.show', $mentorshipRequest->id)
                ->with([
                    'message' => 'Mentorship request submitted successfully! The instructor will review your request.',
                    'type' => 'success'
                ]);
        } catch (\Exception $e) {
            return back()->with([
                'message' => $e->getMessage(),
                'type' => 'error'
            ])->withInput();
        }
    }

    /**
     * Display a specific mentorship request
     */
    public function show(int $id)
    {
        $mentorshipRequest = $this->mentorshipRepository->getById($id);

        if (!$mentorshipRequest || $mentorshipRequest->student_id !== auth()->id()) {
            abort(404, 'Mentorship request not found');
        }

        // Load sessions, resources, and milestones for approved mentorships
        $sessions = [];
        $resources = [];
        $milestones = [];

        if ($mentorshipRequest->status === 'admin_approved') {
            $sessions = $mentorshipRequest->sessions()
                ->orderBy('session_date', 'desc')
                ->get()
                ->map(function ($session) {
                    return [
                        'id' => $session->id,
                        'session_date' => $session->session_date,
                        'duration' => $session->duration,
                        'notes' => $session->notes,
                        'topics_covered' => $session->topics_covered,
                        'recording_link' => $session->recording_link,
                        'completed_at' => $session->completed_at,
                        'created_at' => $session->created_at,
                    ];
                })->values()->all();

            $resources = $mentorshipRequest->resources()
                ->with('uploader:id,name')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($resource) {
                    return [
                        'id' => $resource->id,
                        'file_name' => $resource->file_name,
                        'file_type' => $resource->file_type,
                        'file_size' => $resource->file_size,
                        'description' => $resource->description,
                        'category' => $resource->category,
                        'created_at' => $resource->created_at,
                        'uploader' => [
                            'name' => $resource->uploader->name,
                        ],
                    ];
                })->values()->all();

            $milestones = $mentorshipRequest->milestones()
                ->with('completedBy:id,name')
                ->orderBy('order')
                ->get()
                ->map(function ($milestone) {
                    return [
                        'id' => $milestone->id,
                        'title' => $milestone->title,
                        'description' => $milestone->description,
                        'due_date' => $milestone->due_date,
                        'order' => $milestone->order,
                        'completed_at' => $milestone->completed_at,
                        'completed_by' => $milestone->completed_by ? [
                            'name' => $milestone->completedBy->name,
                        ] : null,
                        'created_at' => $milestone->created_at,
                    ];
                })->values()->all();
        }

        return Inertia::render('Mentorship/Student/Show', [
            'mentorshipRequest' => $mentorshipRequest,
            'sessions' => $sessions,
            'resources' => $resources,
            'milestones' => $milestones,
        ]);
    }

    /**
     * Cancel a mentorship request
     */
    public function cancel(int $id)
    {
        $mentorshipRequest = $this->mentorshipRepository->getById($id);

        if (!$mentorshipRequest || $mentorshipRequest->student_id !== auth()->id()) {
            abort(404, 'Mentorship request not found');
        }

        try {
            $this->mentorshipService->cancel($mentorshipRequest, auth()->user());

            return back()->with([
                'message' => 'Mentorship request cancelled successfully.',
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

        if (!$mentorshipRequest || $mentorshipRequest->student_id !== auth()->id()) {
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
