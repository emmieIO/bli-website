<?php

namespace App\Services\Instructors;

use App\Models\InstructorProfile;
use Illuminate\Support\Facades\Cache;

class InstructorService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function fetchPaginatedApproved($perPage = 10)
    {
        $page = request()->get('page', 1);
        $filters = request()->only(['search', 'category']);
        $cacheKey = $cacheKey = 'instructors:' . md5(json_encode($filters) . "_page_{$page}_{$perPage}");

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($perPage) {
            return InstructorProfile::with('user')
                // ->where('status', 'approved')
                ->paginate($perPage);
        });
    }

}
