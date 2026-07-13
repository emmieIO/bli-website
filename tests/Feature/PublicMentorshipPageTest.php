<?php

namespace Tests\Feature;

use App\Models\InstructorProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PublicMentorshipPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_browse_approved_mentors_only(): void
    {
        Role::findOrCreate('instructor', 'web');
        Role::findOrCreate('mentor', 'web');

        $approved = User::factory()->create(['name' => 'Approved Mentor']);
        $approved->assignRole('instructor');
        $approved->assignRole('mentor');
        InstructorProfile::create([
            'user_id' => $approved->id,
            'bio' => 'Leadership mentor',
            'area_of_expertise' => 'Leadership development',
        ])->forceFill(['is_approved' => true, 'status' => 'approved'])->save();

        $unapproved = User::factory()->create(['name' => 'Unapproved Mentor']);
        $unapproved->assignRole('instructor');
        $unapproved->assignRole('mentor');
        InstructorProfile::create([
            'user_id' => $unapproved->id,
            'bio' => 'Pending profile',
        ]);

        $this->get(route('mentorship.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Mentorship/PublicIndex')
                ->has('mentors', 1)
                ->where('mentors.0.name', 'Approved Mentor')
                ->where('mentors.0.area_of_expertise', 'Leadership development'));
    }
}
