<?php

namespace App\Services;

use App\Enums\ApplicationStatus;
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
        return Category::with(['courses' => function ($query) {
            $query->where('status', ApplicationStatus::APPROVED->value);
        }])->limit(5)->get();
    }
}
