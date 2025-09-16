<?php

namespace App\Http\Controllers;

use App\Enums\ApplicationStatus;
use App\Http\Requests\SpeakerApplicationRequest;
use App\Models\Event;
use App\Models\SpeakerApplication;
use App\Models\SpeakerInvite;
use App\Services\Speakers\SpeakerApplicationService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class SpeakerApplicationController extends Controller
{
    use AuthorizesRequests;
    public function __construct(public SpeakerApplicationService $service)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function apply(Event $event)
    {
        $this->authorize('applyToSpeak', $event);
        $existing = $this->service->getExistingApplication($event);

        if ($existing && $existing->isApproved()) {
            return redirect()->signedRoute('events.show', $event->slug)->with([
                "type" => "warning",
                "message" => "You have already been approved to speak at this event."
            ]);
        }

        $application = $existing;
        return view('speakers.apply', compact("event", "application"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function pendingApplications()
    {
        $applications = $this->service->fetchPendingSpeakerApplications();
        return view("admin.speakers.speaker-application.pending", compact('applications'));
    }
    public function approvedApplications()
    {
        $applications = $this->service->fetchApprovedSpeakerApplications();
        return view("admin.speakers.speaker-application.approved", compact("applications"));
    }

    public function reviewApplication(SpeakerApplication $application)
    {
        return view("admin.speakers.speaker-application.review", compact('application'));
    }

    public function approveApplication(SpeakerApplication $application)
    {
        $this->authorize('approveApplication', $application);

        if ($this->service->approveSpeakerApplication($application)) {
            return back()->with([
                'type' => 'success',
                'message' => 'Speaker application approved successfully.'
            ]);
        }
        return back()->with([
            'type' => 'error',
            'message' => 'Failed to approve speaker application.'
        ]);
    }

    public function rejectApplication(Request $request, SpeakerApplication $application)
    {
        $this->authorize('approveApplication', $application);
        $validated = $request->validate([
            'feedback' => 'required|string|min:50|max:1000'
        ]);

        if ($this->service->rejectSpeakerApplication($application, $validated['feedback'])) {
            return back()->with([
                'type' => 'success',
                'message' => 'Speaker application rejected successfully.'
            ]);
        }
        return back()->with([
            'type' => 'error',
            'message' => 'Failed to reject speaker application.'
        ]);
    }

    public function revokeApproval(Request $request, SpeakerApplication $application)
    {
        dd($application);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SpeakerApplicationRequest $request, Event $event)
    {
        $this->authorize('applyToSpeak', $event);
        $existingApplication = $this->service->getExistingApplication($event);

        if ($existingApplication && $existingApplication->isPending()) {
            return redirect()->signedRoute('events.show', $event->slug)->with([
                "type" => "warning",
                "message" => "You already have a pending application for this event."
            ]);
        }
        $data = $request->validated();
        $photo = $request->file("photo");

        if ($this->service->apply($data, $event, $photo)) {
            return redirect()->back()->with(
                [
                    "type" => 'success',
                    "message" => 'Application submitted successfully.'
                ]
            );
        } else {
            return redirect()->back()->with([
                "type" => 'error',
                "message" => 'Failed to submit application.'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function inviteRespondView(Event $event, SpeakerInvite $invite)
    {
        return view('user_dashboard.invite-respond', compact('event', 'invite'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
