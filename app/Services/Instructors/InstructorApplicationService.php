<?php

namespace App\Services\Instructors;

use App\Models\InstructorProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InstructorApplicationService
{
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
                'name' => 'Pending Instructor',
                'password' => Hash::make(Str::random(16)),
            ]);

            $profile = InstructorProfile::where('user_id', $user->id)->first();

            if ($profile && in_array($profile->status, ['submitted', 'approved'])) {
                return null; // Abort silently
            }

            if (!$profile) {
                InstructorProfile::create([
                    'user_id' => $user->id,
                    'status' => 'draft',
                ]);
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
        dd($experienceData);
        try {
            DB::transaction(function () use ($experienceData, $user) {
                $user->instructorProfile()->update([
                    "teaching_history" => $experienceData["experience"],
                    "area_of_expertise"=> $experienceData['expertise'],
                    "linkedin" => $experienceData['linkedin'],
                    "website" => $experienceData['website']
                ]);
            });
            return true;
        } catch (\Throwable $th) {
            return false;
        }

    }
}
