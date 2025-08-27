<?php

namespace App\Policies;

use App\Models\SpeakerApplication;
use App\Models\User;

class SpeakerApplicationPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function approveApplication(User $user, SpeakerApplication $speakerApplication): bool{
        return $user->can('approve-speaker-applications');
    }
}
