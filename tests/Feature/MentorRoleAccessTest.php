<?php

namespace Tests\Feature;

use App\Models\InstructorProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MentorRoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_instructor_role_does_not_implicitly_grant_mentor_access(): void
    {
        Role::findOrCreate('student', 'web');
        Role::findOrCreate('instructor', 'web');
        Role::findOrCreate('mentor', 'web');

        $user = User::factory()->create();
        $user->assignRole(['student', 'instructor']);
        InstructorProfile::create(['user_id' => $user->id])
            ->forceFill(['is_approved' => true, 'status' => 'approved'])
            ->save();

        $this->assertTrue($user->fresh()->hasRole('student'));
        $this->assertTrue($user->fresh()->canAccessInstructorArea());
        $this->assertFalse($user->fresh()->canAccessMentorArea());

        $user->assignRole('mentor');

        $this->assertTrue($user->fresh()->canAccessMentorArea());
    }
}
