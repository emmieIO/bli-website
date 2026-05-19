<?php

namespace App\Services\Speakers;

use App\Enums\SpeakerInviteStatus;
use App\Models\Speaker;
use App\Models\SpeakerInvite;

class SpeakerInvitationService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected SpeakerTransitionService $speakerTransitionService
    ) {}

    public function respondInvitation(SpeakerInvite $invite, SpeakerInviteStatus $response, ?string $feedback = null)
    {
        $speaker = auth()->user()->speaker;

        if (! $speaker) {
            abort(403, 'You do not have a speaker profile for this invitation.');
        }

        // Validate invite ownership and expiration
        if ($invite->expires_at < now()) {
            return 'INVITE_EXPIRED';
        }

        if ($invite->speaker_id !== $speaker->id) {
            abort(403, 'You are not allowed to respond to this invite.');
        }

        $this->speakerTransitionService->respondToInvitation($invite, $speaker, $response, $feedback);
    }
}
