<?php

namespace App\Enums;

enum SpeakerInviteStatus : string
{
    case PENDING = "pending";
    case REJECTED = "rejected";
    case ACCEPTED = "accepted";
    case CANCELLED = "cancelled";

    public static function fromValue(?string $value): ?self
    {
        return $value ? self::tryFrom($value) : null;
    }
}
