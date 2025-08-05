<?php

namespace App\Services\Instructors;

use App\Models\ApplicationLog;
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
                ->where('is_approved', true)
                ->paginate($perPage);
        });
    }

    public function fetchApplicationLogs(){
        $logs = ApplicationLog::with('user')->latest()->paginate()->withQueryString();

        return $logs;
    }

    public function deleteApplicationLog(ApplicationLog $log){
        try {
            return $log->delete();
        } catch (\Throwable $th) {
            \Log::error('Failed to delete application log', [
                'exception' => $th,
                'log_id' => $log->id ?? null,
            ]);
            return false;
        }
    }

}
