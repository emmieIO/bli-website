<?php

namespace App\Enums;

enum SpeakerInviteStatus : string
{
    case PENDINING = "pending";
    case REJECTED = "rejected";
    case ACCEPTED = "accepted";
    case CANCELLED = "cancelled";
}
