<?php

namespace App\Services\Instructors;

use App\Models\InstructorProfile;
use App\Models\User;
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
}
