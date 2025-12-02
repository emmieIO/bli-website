<?php

namespace App\Http\Controllers\Mentorship;

use App\Http\Controllers\Controller;
use App\Models\MentorshipRequest;
use App\Models\MentorshipSession;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MentorshipSessionController extends Controller
{
    public function index(MentorshipRequest $mentorshipRequest)
    {
        $sessions = $mentorshipRequest->sessions()
            ->orderBy('session_date', 'desc')
            ->get();

        return Inertia::render('Mentorship/Sessions/Index', [
            'mentorshipRequest' => $mentorshipRequest->load(['student', 'instructor']),
            'sessions' => $sessions,
        ]);
    }

    public function create(MentorshipRequest $mentorshipRequest)
    {
        return Inertia::render('Mentorship/Sessions/Create', [
            'mentorshipRequest' => $mentorshipRequest->load(['student', 'instructor']),
        ]);
    }

    public function store(Request $request, MentorshipRequest $mentorshipRequest)
    {
        // Security: Verify user is either student or instructor
        if (auth()->id() !== $mentorshipRequest->student_id &&
            auth()->id() !== $mentorshipRequest->instructor_id) {
            abort(403, 'Unauthorized to create sessions for this mentorship');
        }

        $validated = $request->validate([
            'session_date' => 'required|date',
            'duration' => 'required|integer|min:1|max:480',
            'notes' => 'nullable|string',
            'topics_covered' => 'nullable|string',
            'recording_link' => 'nullable|url',
        ]);

        $mentorshipRequest->sessions()->create($validated);

        return redirect()
            ->route('mentorship.sessions.index', $mentorshipRequest)
            ->with('success', 'Session added successfully');
    }

    public function show(MentorshipRequest $mentorshipRequest, MentorshipSession $session)
    {
        if ($session->mentorship_request_id !== $mentorshipRequest->id) {
            abort(404);
        }

        return Inertia::render('Mentorship/Sessions/Show', [
            'mentorshipRequest' => $mentorshipRequest->load(['student', 'instructor']),
            'session' => $session,
        ]);
    }

    public function update(Request $request, MentorshipRequest $mentorshipRequest, MentorshipSession $session)
    {
        if ($session->mentorship_request_id !== $mentorshipRequest->id) {
            abort(404);
        }

        // Security: Verify user is either student or instructor
        if (auth()->id() !== $mentorshipRequest->student_id &&
            auth()->id() !== $mentorshipRequest->instructor_id) {
            abort(403, 'Unauthorized to update sessions for this mentorship');
        }

        $validated = $request->validate([
            'session_date' => 'required|date',
            'duration' => 'required|integer|min:1|max:480',
            'notes' => 'nullable|string',
            'topics_covered' => 'nullable|string',
            'recording_link' => 'nullable|url',
        ]);

        $session->update($validated);

        return redirect()
            ->route('mentorship.sessions.index', $mentorshipRequest)
            ->with('success', 'Session updated successfully');
    }

    public function markComplete(MentorshipRequest $mentorshipRequest, MentorshipSession $session)
    {
        if ($session->mentorship_request_id !== $mentorshipRequest->id) {
            abort(404);
        }

        // Security: Verify user is either student or instructor
        if (auth()->id() !== $mentorshipRequest->student_id &&
            auth()->id() !== $mentorshipRequest->instructor_id) {
            abort(403, 'Unauthorized to mark sessions complete for this mentorship');
        }

        $session->markAsCompleted();

        return back()->with('success', 'Session marked as completed');
    }

    public function destroy(MentorshipRequest $mentorshipRequest, MentorshipSession $session)
    {
        if ($session->mentorship_request_id !== $mentorshipRequest->id) {
            abort(404);
        }

        // Security: Verify user is either student or instructor
        if (auth()->id() !== $mentorshipRequest->student_id &&
            auth()->id() !== $mentorshipRequest->instructor_id) {
            abort(403, 'Unauthorized to delete sessions for this mentorship');
        }

        $session->delete();

        return redirect()
            ->route('mentorship.sessions.index', $mentorshipRequest)
            ->with('success', 'Session deleted successfully');
    }
}
