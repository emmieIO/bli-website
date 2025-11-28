<?php

namespace App\Console\Commands;

use App\Enums\ApplicationStatus;
use App\Enums\Permissions\CoursePermissionsEnum;
use App\Models\Course;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Gate;

class DiagnoseInstructorPermissions extends Command
{
    protected $signature = 'instructor:diagnose {userId} {courseSlug}';
    protected $description = 'Diagnose instructor course permissions for a specific user and course';

    public function handle()
    {
        $userId = $this->argument('userId');
        $courseSlug = $this->argument('courseSlug');

        $this->info("=== Diagnosing Instructor Course Permissions ===\n");

        // Find user
        $user = User::find($userId);
        if (!$user) {
            $this->error("User with ID {$userId} not found!");
            return 1;
        }

        $this->info("User: {$user->name} (ID: {$user->id})");
        $this->info("Email: {$user->email}");
        $this->info("Roles: " . $user->roles->pluck('name')->implode(', '));
        $this->newLine();

        // Find course
        $course = Course::where('slug', $courseSlug)->first();
        if (!$course) {
            $this->error("Course with slug '{$courseSlug}' not found!");
            return 1;
        }

        $this->info("Course: {$course->title}");
        $this->info("Course ID: {$course->id}");
        $this->info("Course Status: {$course->status->value}");
        $this->info("Course Instructor ID: {$course->instructor_id}");
        $this->newLine();

        // Permission checks
        $this->info("=== Permission Checks ===");

        $requiredPermissions = [
            CoursePermissionsEnum::UPDATE_OWN->value => 'Update Own Courses',
            CoursePermissionsEnum::UPDATE_ANY->value => 'Update Any Course (Admin)',
        ];

        foreach ($requiredPermissions as $permission => $description) {
            $has = $user->hasPermissionTo($permission);
            $icon = $has ? '✅' : '❌';
            $this->line("{$icon} {$description}: " . ($has ? 'YES' : 'NO'));
        }
        $this->newLine();

        // Ownership check
        $this->info("=== Ownership Check ===");
        $isOwner = $course->instructor_id === $user->id;
        $icon = $isOwner ? '✅' : '❌';
        $this->line("{$icon} Is Course Owner: " . ($isOwner ? 'YES' : 'NO'));
        $this->newLine();

        // Status check
        $this->info("=== Status Check ===");
        $statusChecks = [
            ['status' => ApplicationStatus::PENDING, 'blocks' => true],
            ['status' => ApplicationStatus::UNDER_REVIEW, 'blocks' => true],
        ];

        foreach ($statusChecks as $check) {
            $matches = $course->status === $check['status'];
            if ($matches) {
                $this->warn("⚠️  Course is {$check['status']->value} - Editing is BLOCKED during review");
            }
        }

        if (!in_array($course->status, [ApplicationStatus::PENDING, ApplicationStatus::UNDER_REVIEW])) {
            $this->info("✅ Course status allows editing");
        }
        $this->newLine();

        // Final authorization check
        $this->info("=== Authorization Result ===");
        $canUpdate = Gate::forUser($user)->allows('update', $course);

        if ($canUpdate) {
            $this->info("✅ USER CAN MANAGE THIS COURSE");
            $this->info("   - Can add/edit/delete modules");
            $this->info("   - Can add/edit/delete lessons");
            $this->info("   - Can access course builder");
        } else {
            $this->error("❌ USER CANNOT MANAGE THIS COURSE");
            $this->newLine();
            $this->warn("Possible reasons:");

            if (!$isOwner) {
                $this->warn("  • User is not the course instructor");
            }

            if (!$user->hasPermissionTo(CoursePermissionsEnum::UPDATE_OWN->value)) {
                $this->warn("  • User lacks 'course-update-own' permission");
            }

            if (in_array($course->status, [ApplicationStatus::PENDING, ApplicationStatus::UNDER_REVIEW])) {
                $this->warn("  • Course is under admin review (status: {$course->status->value})");
                $this->info("    Wait for admin to approve/reject, then you can edit again");
            }
        }

        $this->newLine();
        return 0;
    }
}
