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
        return \Inertia\Inertia::render('Admin/Speakers/Index', compact('speakers'));
    }

    public function pendingSpeaker()
    {
        $this->authorize('viewAny', Speaker::class);
        $speakers = $this->speakerService->fetchSpeakers('pending');
        return \Inertia\Inertia::render('Admin/Speakers/Pending', compact('speakers'));
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
        return \Inertia\Inertia::render('Admin/Speakers/Create');
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
        return \Inertia\Inertia::render('Admin/Speakers/View', compact('speaker'));
    }

    public function viewSpeakerProfile(Speaker $speaker)
    {
        return \Inertia\Inertia::render('Speakers/Profile', compact('speaker'));
    }

    public function edit(Speaker $speaker)
    {
        return \Inertia\Inertia::render('Admin/Speakers/Edit', compact('speaker'));
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
        $this->authorize('manageSpeakers', $event);

        $event->load([
            'speakers.user',
            'speakerInvites' => fn ($query) => $query->latest(),
            'speakerInvites.speaker.user',
            'speakerApplications' => fn ($query) => $query->latest(),
            'speakerApplications.user',
            'speakerApplications.speaker.user',
        ]);

        // Confirmed speakers are excluded because inviting them again is never a useful action.
        $confirmedIds = $event->speakers->pluck('id');
        $speakers = Speaker::query()
            ->where('status', 'active')
            ->whereNotIn('id', $confirmedIds)
            ->with('user')
            ->orderByDesc('created_at')
            ->get();

        return \Inertia\Inertia::render('Admin/Speakers/AssignSpeaker', compact('event', 'speakers'));
    }

}
