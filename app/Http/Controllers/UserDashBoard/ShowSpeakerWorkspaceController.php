<?php

namespace App\Http\Controllers\UserDashBoard;

use App\Enums\SpeakerWorkspaceStage;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\SpeakerApplication;
use App\Models\SpeakerInvite;
use App\Services\Speakers\SpeakerTransitionService;
use Inertia\Inertia;

class ShowSpeakerWorkspaceController extends Controller
{
    public function __construct(
        protected SpeakerTransitionService $speakerTransitionService
    ) {}

    public function __invoke(string $slug)
    {
        $user = auth()->user();
        $speakerId = $user?->speaker?->id;

        abort_unless($user, 403);

        $event = Event::with(['speakers.user'])->findBySlug($slug)->firstOrFail();
        $application = SpeakerApplication::with('speaker')
            ->where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();
        $invite = $speakerId
            ? SpeakerInvite::where('event_id', $event->id)->where('speaker_id', $speakerId)->first()
            : null;

        $isAssignedSpeaker = $speakerId
            ? $event->speakers->contains('id', $speakerId)
            : false;

        $stage = $this->speakerTransitionService->resolveWorkspaceStage($application, $invite, $isAssignedSpeaker);

        abort_unless($stage, 404);

        return Inertia::render('Speakers/Workspace', [
            'event' => $event,
            'application' => $application,
            'invite' => $invite,
            'stage' => $stage->value,
            'workspaceApplyUrl' => route('speaker.events.apply', $event->slug),
        ]);
    }
}
