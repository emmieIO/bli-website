<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateSpeakerRequest;
use App\Http\Requests\UpdateSpeakerRequest;
use App\Models\Event;
use App\Models\Speaker;
use App\Services\Event\SpeakerService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class SpeakersController extends Controller
{
    use AuthorizesRequests;
    public function __construct(protected SpeakerService $speakerService)
    {
    }
    public function index()
    {
        $this->authorize('viewAny', Speaker::class);
        $speakers = $this->speakerService->fetchSpeakers();
        return view("admin.speakers.index", compact('speakers'));
    }

    public function pendingSpeaker()
    {
        $this->authorize('viewAny', Speaker::class);
        $speakers = $this->speakerService->fetchSpeakers('pending');
        return view("admin.speakers.pending-speaker", compact('speakers'));
    }

    public function activateSpeaker(Speaker $speaker)
    {
        if ($this->speakerService->activateSpeaker($speaker)) {
            return back()->with([
                'type' => "success",
                "message" => "Speaker activated successfully"
            ]);
        }
        return back()->with([
            'type' => "error",
            "message" => "Error occured while activating speaker"
        ]);
    }

    public function create()
    {
        $this->authorize('create', Speaker::class);
        return view("admin.speakers.create-speaker");
    }

    public function store(CreateSpeakerRequest $request)
    {
        $this->authorize('create', Speaker::class);
        $validated = $request->validated();
        $photo = $validated['userInfo']['photo'];
        $speaker = $this->speakerService->createSpeaker($validated, $photo);
        if ($speaker) {
            return back()->with([
                "type" => "success",
                "message" => "Speaker created successfully"
            ]);
        }
        return back()->with([
            "type" => "error",
            "message" => "An error occured, Try again later"
        ]);
    }

    public function show(Speaker $speaker)
    {
        return view("admin.speakers.view-speaker", compact("speaker"));
    }

    public function viewSpeakerProfile(Speaker $speaker)
    {
        return view("speakers.speaker-profile", compact("speaker"));
    }

    public function edit(Speaker $speaker)
    {
        return view("admin.speakers.edit-speaker", compact("speaker"));
    }

    public function update(UpdateSpeakerRequest $request, Speaker $speaker)
    {

        $this->authorize('update', $speaker);
        $validated = $request->validated();
        $photo = $request->file('photo');
        $speaker = $this->speakerService->updateSpeaker($validated, $speaker, $photo);

        if ($speaker) {
            return back()->with([
                "type" => "success",
                "message" => "Speaker updated successfully"
            ]);
        }
        return back()->with([
            "type" => "error",
            "message" => "An error occurred, Try again later"
        ]);
    }

    public function destroySpeaker(Request $request, Speaker $speaker)
    {
        $this->authorize('delete', $speaker);
        if ($this->speakerService->deleteSpeaker($speaker)) {
            return back()->with([
                "type" => "success",
                "message" => "Speaker deleted successfully"
            ]);
        }
        return back()->with([
            "type" => "error",
            "message" => "An error occurred, Try again later"
        ]);
    }

    public function showAssignSpeakersPage(Event $event)
    {
        $speakers = $this->speakerService->fetchSpeakers();
        $assignedSpeakerIds = $event->speakers()->pluck('speakers.id')->toArray();
        return view("admin.speakers.assign-speaker", compact("event", 'speakers', "assignedSpeakerIds"));
    }

}
