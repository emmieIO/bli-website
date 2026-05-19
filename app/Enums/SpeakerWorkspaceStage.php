<?php

namespace App\Enums;

use App\Models\SpeakerApplication;
use App\Models\SpeakerInvite;

enum SpeakerWorkspaceStage : string
{
    case INVITED = 'invited';
    case APPLIED = 'applied';
    case UNDER_REVIEW = 'under_review';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case WITHDRAWN = 'withdrawn';

    public static function resolve(
        ?SpeakerApplication $application,
        ?SpeakerInvite $invite,
        bool $isAssignedSpeaker
    ): ?self {
        if (! self::hasAccess($application, $invite, $isAssignedSpeaker)) {
            return null;
        }

        $applicationStatus = ApplicationStatus::fromValue($application?->status);
        $inviteStatus = SpeakerInviteStatus::fromValue($invite?->status);

        if ($applicationStatus === ApplicationStatus::REJECTED) {
            return self::REJECTED;
        }

        if (
            $isAssignedSpeaker
            || $applicationStatus === ApplicationStatus::APPROVED
            || $inviteStatus === SpeakerInviteStatus::ACCEPTED
        ) {
            return self::APPROVED;
        }

        if (
            $applicationStatus === ApplicationStatus::PENDING
            || $applicationStatus === ApplicationStatus::UNDER_REVIEW
        ) {
            return self::UNDER_REVIEW;
        }

        if ($applicationStatus === ApplicationStatus::DRAFT) {
            return self::APPLIED;
        }

        if ($inviteStatus === SpeakerInviteStatus::REJECTED) {
            return self::WITHDRAWN;
        }

        return self::INVITED;
    }

    public static function hasAccess(
        ?SpeakerApplication $application,
        ?SpeakerInvite $invite,
        bool $isAssignedSpeaker
    ): bool {
        return $application !== null || $invite !== null || $isAssignedSpeaker;
    }

    public function allowsApplicationContinuation(): bool
    {
        return $this === self::INVITED || $this === self::APPLIED;
    }
}
