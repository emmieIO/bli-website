<?php

namespace App\Services\Instructors;

use App\Enums\UserRoles;
use App\Models\ApplicationLog;
use App\Models\InstructorProfile;
use App\Models\User;
use App\Notifications\InstructorApplicationApproved;
use App\Notifications\InstructorApplicationRejection;
use App\Notifications\InstructorApplicationSubmitted;
use App\Traits\GeneratesApplicationId;
use App\Traits\HasFileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class InstructorApplicationService
{
    use HasFileUpload, GeneratesApplicationId;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function start(string $email): ?User
    {
        $email = strtolower(trim($email));

        return DB::transaction(function () use ($email) {
            $user = User::firstOrCreate(['email' => $email], [
                'name' => 'Please add your name',
                'password' => Hash::make(Str::random(16)),
            ]);
            $profile = InstructorProfile::where('user_id', $user->id)->first();


            if ($profile && in_array($profile->status, ['submitted', 'approved'])) {
                return null; // Abort silently
            }

            if (!$profile) {
                $profile = InstructorProfile::create([
                    // 'application_id' => $this->formatApplicationId($profile->id),
                    'user_id' => $user->id,
                    'status' => 'draft',
                ]);
                $profile->application_id = $this->formatApplicationId($profile->id);
                $profile->save();
            }
            return $user;
        });
    }

    public function savePersonalInfo(array $personalInfo, User $user): bool
    {
        try {
            DB::transaction(function () use ($personalInfo, $user) {
                // Update user name
                $user->update([
                    'name' => $personalInfo['name'],
                    'phone' => $personalInfo['phone']
                ]);

                // Update instructor profile
                $user->instructorProfile()->update([
                    'headline' => $personalInfo['headline'],

                    'bio' => $personalInfo['bio'],
                ]);
            });

            return true;

        } catch (\Throwable $e) {
            \Log::error('Error saving personal info: ' . $e->getMessage(), [
                'user_id' => $user->id ?? null,
                'data' => $personalInfo,
            ]);
            return false;
        }
    }

    public function saveExperience(array $experienceData, User $user): bool
    {

        try {
            DB::transaction(function () use ($experienceData, $user) {
                $user->instructorProfile()->update([
                    "teaching_history" => $experienceData["experience"],
                    "area_of_expertise" => $experienceData['expertise'],
                    "experience_years" => $experienceData["experience_years"],
                    "linkedin_url" => $experienceData['linkedin'],
                    "website" => $experienceData['website']
                ]);
            });
            return true;
        } catch (\Throwable $th) {
            \Log::error('Error saving experience: ' . $th->getMessage(), [
                'user_id' => $user->id ?? null,
                'data' => $experienceData,
            ]);
            return false;
        }

    }

    public function saveInstructorDocs(Request $request, User $user)
    {
        $profile = $user->instructorProfile;
        $oldResumePath = $profile->resume_path;
        try {
            DB::transaction(function () use ($request, $profile, $oldResumePath) {
                $docsToUpdate = [];

                if ($request->hasFile('resume')) {

                    $docsToUpdate['resume_path'] = $this->uploadFile(
                        $request,
                        "resume",
                        'instructors/resumes'
                    );
                }

                $docsToUpdate['intro_video_url'] = $request->video_url;

                if (!empty($docsToUpdate)) {
                    $profile->update($docsToUpdate);

                    if (!empty($docsToUpdate['resume_path']) && $oldResumePath) {
                        $this->deleteFile($oldResumePath);
                    }
                }

            });
            return true;
        } catch (\Throwable $e) {
            if (!empty($request->file('resume'))) {
                $this->deleteFile($request->file('resume')->path());
            }
            \Log::error('Error saving instructor documents: ' . $e->getMessage(), [
                'user_id' => $user->id ?? null,
                'data' => $request->only(['video_url']),
                'has_resume' => $request->hasFile('resume'),
            ]);
            return false;
        }
    }

    public function getIncompleteFields(User $user): array
    {
        $profile = $user->instructorProfile;

        if (!$profile)
            return ['profile'];

        $missing = [];

        if ($user->name === 'Pending Instructor' || !filled($user->name)) {
            $missing[] = 'name';
        }

        if (!filled($profile->headline))
            $missing[] = 'headline';
        if (!filled($profile->bio))
            $missing[] = 'bio';
        if (!filled($profile->teaching_history))
            $missing[] = 'teaching_history';
        if (!filled($profile->experience_years))
            $missing[] = 'experience_years';
        if (!filled($profile->area_of_expertise))
            $missing[] = 'area_of_expertise';
        if (!filled($profile->resume_path))
            $missing[] = 'resume_path';
        if (!filled($profile->intro_video_url))
            $missing[] = 'intro_video_url';

        return $missing;
    }

    public function isApplicationComplete(User $user): bool
    {
        return empty($this->getIncompleteFields($user));
    }

    public function submitApplication(User $user)
    {
        try {
            DB::transaction(function () use ($user) {
                $profile = $user->instructorProfile;
                if ($this->isApplicationComplete($user)) {
                    $profile->update([
                        "status" => "submitted"
                    ]);
                }
            });
            $user->notify(new InstructorApplicationSubmitted());
            return true;
        } catch (\Exception $e) {
            \Log::error('Error submitting instructor application: ' . $e->getMessage(), [
                'user_id' => $user->id ?? null,
            ]);
            return false;
        }
    }

    public function approveApplication(InstructorProfile $application)
    {
        if ($application->is_approved) {
            return true;
        }
        // guest application user
        $user = $application->user;
        if (!$this->isApplicationComplete($user)) {
            return false;
        }
        try {
            DB::transaction(function () use ($user, $application) {
                // assign instructor role
                $user->syncRoles([UserRoles::INSTRUCTOR->value]);
                // update application status to approved
                $application->update([
                    'status' => "approved",
                    'is_approved' => true,
                    "approved_at" => now()
                ]);
            });
            // Generate a secure shorlived password reset link
            $resetUrl = URL::temporarySignedRoute(
                'password.reset',
                now()->addDays(3),
                ["token" => Password::createToken($user), 'email' => $user->email]
            );
            // send approval notification with password reset link
            $user->notify(new InstructorApplicationApproved($resetUrl));
            return true;
        } catch (\Exception $e) {
            \Log::error('Error approving instructor application: ' . $e->getMessage(), [
                'user_id' => $user->id ?? null,
                'application_id' => $application->id ?? null,
            ]);
            return false;
        }
    }

    public function rejectApplication(array $rejectionData, InstructorProfile $application){

        try {
            DB::transaction(function () use ($rejectionData, $application) {
                $user = $application->user;

                // 1. Revoke instructor role
                $user->syncRoles([UserRoles::STUDENT->value]);

                // 2. Update application status
                $application->update([
                    'is_approved' => false,
                    'status' => 'rejected',
                ]);

                // 3. Log rejection
                ApplicationLog::create([
                    'instructor_profile_id' => $application->id,
                    'application_id' => $application->application_id,
                    'performed_by' => auth()->id(),
                    'comment' => $rejectionData['rejection_reason'] ?? 'No reason provided',
                    'action' => 'rejected',
                ]);

                // 4. Send rejection email
                $user->notify(new InstructorApplicationRejection(
                    $rejectionData['rejection_reason'],
                    $application
                ));
            });

            return true;
        } catch (\Throwable $th) {
            \Log::error('Error rejecting instructor application: ' . $th->getMessage(), [
                'application_id' => $application->id ?? null,
                'data' => $rejectionData,
            ]);
            return false;
        }
    }
}
