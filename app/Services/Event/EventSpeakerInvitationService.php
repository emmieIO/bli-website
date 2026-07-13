<?php

namespace App\Services\Event;

use App\Models\Event;
use App\Models\SpeakerInvite;
use App\Notifications\SpeakerInvitationNotification;
use App\Services\Speakers\SpeakerTransitionService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class EventSpeakerInvitationService
{
    public function __construct(
        protected SpeakerService $speakerService,
        protected SpeakerTransitionService $speakerTransitionService
    ) {}

    public function inviteSpeakerToEvent(Event $event, array $data): bool|string
    {
        $speaker = $this->speakerService->findOneSpeaker($data['speaker_id']);
        $data['event_id'] = $event->id;
        $data['email'] = $speaker->user?->email;

        if ($this->speakerService->speakerAlreadyInvited($event, $speaker)) {
            return 'already_invited';
        }

        if ($this->speakerService->speakerHasAplication($event, $speaker)) {
            $existingApplication = $this->speakerService->findExistingSpeakerApplication($event, $speaker);

            if ($existingApplication) {
                $this->speakerTransitionService->approveApplication($existingApplication);

                return 'speaker_approved';
            }
        }

        try {
            $data['sent_at'] = Carbon::now();
            $data['expires_at'] = Carbon::now()->addDays(7);

            DB::transaction(function () use ($data) {
                $invitation = SpeakerInvite::create($data);
                $invitation->speaker->user->notify(new SpeakerInvitationNotification($invitation));
            });

            return true;
        } catch (Throwable $e) {
            Log::error('Speaker invitation failed:', [
                'exception' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
