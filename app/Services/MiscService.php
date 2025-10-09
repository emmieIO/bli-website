<?php

namespace App\Services;

use App\Enums\ApplicationStatus;
use App\Enums\UserRoles;
use App\Models\Category;
use Composer\Console\Application;

class MiscService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    // public function fetchFiveCategories()
    // {
    //     return Category::with('courses')->limit(5)->get();
    // }

    public function fetchFiveCategories()
    {
        return Category::with([
            'courses' => function ($query) {
                $query->where('status', ApplicationStatus::APPROVED->value);
            }
        ])->limit(5)->get();
    }

    public function isAdmin()
    {
        if (auth()->check() && auth()->user()->hasAnyRole([UserRoles::ADMIN->value, UserRoles::SUPER_ADMIN->value])) {
            return true;
        }
        return false;
    }

}
