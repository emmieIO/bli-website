<?php

namespace App\Services;

use App\Enums\UserRoles;

class MiscService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function isAdmin()
    {
        if (auth()->check() && auth()->user()->hasAnyRole([UserRoles::ADMIN->value, UserRoles::SUPER_ADMIN->value])) {
            return true;
        }
        return false;
    }

}
