<?php

namespace App\Enums;

enum EventState: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Upcoming = 'upcoming';
    case Ongoing = 'ongoing';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
}
