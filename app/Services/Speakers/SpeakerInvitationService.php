<?php

namespace App\Services\Speakers;

use App\Enums\SpeakerInviteStatus;
use App\Models\SpeakerInvite;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SpeakerInvitationService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
    }
    public function respondInvitation(SpeakerInvite $invite, SpeakerInviteStatus $response, ?string $feedback = null)
    {
        $speaker = auth()->user()->speaker->id;

        // Validate invite ownership and expiration
        if ($invite->expires_at < now()) {
            return 'INVITE_EXPIRED';
        }

        if ($invite->speaker_id !== $speaker->id) {
            abort(403, 'You are not allowed to respond to this invite.');
        }

        DB::transaction(function () use ($invite, $response, $feedback, $speaker) {
            $invite->update([
                'responded_at' => now(),
                'status' => $response->value,
                'user_feedback' => $feedback,
            ]);

            // dispatch domain specific function
            match($response) {
                SpeakerInviteStatus::ACCEPTED => $this->handleAccepted($invite,$speaker),
                SpeakerInviteStatus::REJECTED => $this->handleRejected($invite,$speaker),
                default => null

            };
        });

    }

    private function handleAccepted(SpeakerInvite $invite, User $speaker)
    {
        $event = $invite->event;
        if ($event){
            $event->speakers()->syncWithoutDetaching($speaker->id);
        }
    }
    private function handleRejected(SpeakerInvite $invite, User $speaker)
    {
    }


}
