<?php

namespace App\Services\Speakers;

use App\Enums\ApplicationStatus;
use App\Enums\SpeakerInviteStatus;
use App\Enums\SpeakerStatus;
use App\Enums\SpeakerWorkspaceStage;
use App\Enums\UserRoles;
use App\Events\SpeakerApplicationApprovedEvent;
use App\Models\Speaker;
use App\Models\SpeakerApplication;
use App\Models\SpeakerInvite;
use Illuminate\Support\Facades\DB;

class SpeakerTransitionService
{
    public function resolveWorkspaceStage(
        ?SpeakerApplication $application,
        ?SpeakerInvite $invite,
        bool $isAssignedSpeaker
    ): ?SpeakerWorkspaceStage {
        return SpeakerWorkspaceStage::resolve($application, $invite, $isAssignedSpeaker);
    }

    public function submitApplication(SpeakerApplication $application): SpeakerApplication
    {
        $application->update([
            'status' => ApplicationStatus::PENDING->value,
            'feedback' => null,
            'reviewed_at' => null,
            'approved_at' => null,
            'rejected_at' => null,
        ]);

        return $application->fresh();
    }

    public function approveApplication(SpeakerApplication $application): SpeakerApplication
    {
        return DB::transaction(function () use ($application) {
            $application->update([
                'status' => ApplicationStatus::APPROVED->value,
                'feedback' => null,
                'approved_at' => now(),
                'rejected_at' => null,
                'reviewed_at' => now(),
            ]);

            $application->speaker->update([
                'status' => SpeakerStatus::ACTIVE->value,
            ]);

            $user = $application->speaker->user;
            if ($user && ! $user->hasRole(UserRoles::SPEAKER->value)) {
                $user->assignRole(UserRoles::SPEAKER->value);
            }

            $application->event->speakers()->syncWithoutDetaching([
                $application->speaker->id,
            ]);

            event(new SpeakerApplicationApprovedEvent($application));

            return $application->fresh();
        });
    }

    public function rejectApplication(SpeakerApplication $application, string $feedback): SpeakerApplication
    {
        return DB::transaction(function () use ($application, $feedback) {
            $speaker = $application->speaker;
            $event = $application->event;

            $application->update([
                'status' => ApplicationStatus::REJECTED->value,
                'feedback' => $feedback,
                'approved_at' => null,
                'rejected_at' => now(),
                'reviewed_at' => now(),
            ]);

            $event->speakers()->detach($speaker->id);

            return $application->fresh();
        });
    }

    public function reopenApplicationReview(SpeakerApplication $application): SpeakerApplication
    {
        return DB::transaction(function () use ($application) {
            $application->event->speakers()->detach($application->speaker_id);

            $application->update([
                'status' => ApplicationStatus::PENDING->value,
                'feedback' => null,
                'approved_at' => null,
                'rejected_at' => null,
                'reviewed_at' => null,
            ]);

            return $application->fresh();
        });
    }

    public function respondToInvitation(
        SpeakerInvite $invite,
        Speaker $speaker,
        SpeakerInviteStatus $response,
        ?string $feedback = null
    ): SpeakerInvite {
        return DB::transaction(function () use ($invite, $speaker, $response, $feedback) {
            $invite->update([
                'responded_at' => now(),
                'status' => $response->value,
                'user_feedback' => $feedback,
            ]);

            if ($response === SpeakerInviteStatus::ACCEPTED) {
                $invite->event?->speakers()->syncWithoutDetaching([$speaker->id]);
            }

            return $invite->fresh();
        });
    }
}
