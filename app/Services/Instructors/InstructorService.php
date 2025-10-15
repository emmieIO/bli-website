<?php

namespace App\Services\Instructors;

use App\Enums\ApplicationStatus;
use App\Enums\RoleEnum;
use App\Models\ApplicationLog;
use App\Models\InstructorProfile;
use App\Traits\HasFileUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class InstructorService
{
    use HasFileUpload;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function fetchPaginatedApproved($perPage = 10)
    {
        return InstructorProfile::with('user')
            ->where('is_approved', true)
            ->withTrashed()
            ->paginate($perPage);
    }

    public function fetchApplicationLogs()
    {
        $logs = ApplicationLog::with('user')->latest()->paginate()->withQueryString();

        return $logs;
    }

    public function deleteApplicationLog(ApplicationLog $log)
    {
        try {
            return $log->delete();
        } catch (\Throwable $th) {
            Log::error('Failed to delete application log', [
                'exception' => $th,
                'log_id' => $log->id ?? null,
            ]);
            return false;
        }
    }

    public function createInstructor() {}
    public function updateInstructor(array $data, InstructorProfile $instructorProfile, ?UploadedFile $file = null)
    {
        try {
            DB::transaction(function () use ($instructorProfile, $data, $file) {
                $oldPath = $instructorProfile->resume_path;
                $newPath = $oldPath;
                $user = $instructorProfile->user;

                $data['is_approved'] = $data["application_status"] === ApplicationStatus::APPROVED->value;

                match ($data["application_status"]) {
                    ApplicationStatus::APPROVED->value => $user->syncRoles([RoleEnum::Instructor->value]),
                    default => $user->syncRoles([RoleEnum::Student->value]),
                };

                $data['approved_at'] = $data['is_approved'] ? now() : null;

                if ($file) {
                    $newPath = $file->store('instructors/resume', 'public');
                }
                // update the user if updated
                $user = $instructorProfile->user;
                $user->update([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                ]);

                // upddate the instructor profile if updated
                $instructorProfile->update([
                    'bio' => $data['bio'],
                    'headline' => $data['headline'],
                    'teaching_history' => $data['teaching_history'],
                    'experience_years' => $data['experience_years'],
                    'intro_video_url' => $data['intro_video_url'],
                    'area_of_expertise' => $data['area_of_expertise'],
                    'linkedin_url' => $data['linkedin_url'],
                    'website' => $data['website'],
                    'resume_path' => $newPath,
                    'status' => $data['application_status'],
                    'is_approved' => $data['is_approved'],
                    'approved_at' => $data['approved_at']
                ]);

                if ($file && $oldPath) {
                    DB::afterCommit(function () use ($oldPath) {
                        if (Storage::disk('public')->exists($oldPath)) {
                            Storage::disk('public')->delete($oldPath);
                        }
                    });
                }
            });
            return true;
        } catch (\Throwable $th) {
            Log::error('Instructor update failed', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);
            return false;
        }
    }
    public function deleteInstructor(InstructorProfile $instructorProfile) {
        $resumePath = $instructorProfile->resume_path;
        $user = $instructorProfile->user;
        try {
        DB::transaction(function () use ($instructorProfile, $user) {
            $user->syncRoles([RoleEnum::Student]);
            $instructorProfile->delete();
        });

        // File deletion outside transaction
        // if ($resumePath && Storage::disk('public')->exists($resumePath)) {
        //     Storage::disk('public')->delete($resumePath);
        // }

            return true;
        } catch (\Throwable $th) {
            Log::error('Instructor deletion failed', [
                'error'=> $th->getMessage(),
                'trace'=> $th->getTraceAsString()
            ]);
            return false;
        }
    }

    public function restoreInstructor(InstructorProfile $instructorProfile) {
        if($instructorProfile->deleted_at) {
            $instructorProfile->restore();
            return true;
        }
        return false;
    }
}
