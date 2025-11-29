<?php

namespace App\Http\Controllers\Mentorship;

use App\Http\Controllers\Controller;
use App\Models\MentorshipMilestone;
use App\Models\MentorshipRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MentorshipMilestoneController extends Controller
{
    public function index(MentorshipRequest $mentorshipRequest)
    {
        $milestones = $mentorshipRequest->milestones()
            ->with('completedBy')
            ->get();

        return Inertia::render('Mentorship/Milestones/Index', [
            'mentorshipRequest' => $mentorshipRequest->load(['student', 'instructor']),
            'milestones' => $milestones,
        ]);
    }

    public function store(Request $request, MentorshipRequest $mentorshipRequest)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'order' => 'nullable|integer',
        ]);

        $mentorshipRequest->milestones()->create($validated);

        return back()->with('success', 'Milestone created successfully');
    }

    public function update(Request $request, MentorshipRequest $mentorshipRequest, MentorshipMilestone $milestone)
    {
        if ($milestone->mentorship_request_id !== $mentorshipRequest->id) {
            abort(404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'order' => 'nullable|integer',
        ]);

        $milestone->update($validated);

        return back()->with('success', 'Milestone updated successfully');
    }

    public function toggleComplete(MentorshipRequest $mentorshipRequest, MentorshipMilestone $milestone)
    {
        if ($milestone->mentorship_request_id !== $mentorshipRequest->id) {
            abort(404);
        }

        if ($milestone->isCompleted()) {
            $milestone->update([
                'completed_at' => null,
                'completed_by' => null,
            ]);
        } else {
            $milestone->markAsCompleted(auth()->user());
        }

        return back()->with('success', 'Milestone status updated');
    }

    public function destroy(MentorshipRequest $mentorshipRequest, MentorshipMilestone $milestone)
    {
        if ($milestone->mentorship_request_id !== $mentorshipRequest->id) {
            abort(404);
        }

        $milestone->delete();

        return back()->with('success', 'Milestone deleted successfully');
    }
}
