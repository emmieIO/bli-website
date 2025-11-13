<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            "student",
            "instructor",
            "admin",

        ];

        $rolesPermissions = [
            'admin' => [
                // event management
                'manage events',
                // speaker management
                "create-speaker",
                'view-speaker',
                "edit-speaker",
                'delete-speaker',
                'assign-speaker',
                "manage-instructor-applications",
                'approve-speaker-applications',
                'track-applications',
                'manage-activity-log',

                // Categories
                'category-view',
                'category-create',
                'category-update',
                'category-delete',
                'category-approve',

                // Courses
                'course-view',
                'course-view-any',
                'course-create',
                'course-update',
                'course-update-any',
                'course-submit-for-review',
                'course-publish',
                'course-unpublish',
                'course-delete',
                'course-delete-any',
                'course-assign-instructor',

                // Modules
                'module-view',
                'module-create',
                'module-update',
                'module-delete',
                'module-reorder',
                'module-manage-any',

                // Lessons
                'lesson-view',
                'lesson-create',
                'lesson-update',
                'lesson-delete',
                'lesson-publish',
                'lesson-manage-any',

                // Enrollments
                'enrollment-enroll',
                'enrollment-unenroll',
                'enrollment-view-progress',
                'enrollment-manage-any',

                // Review / Quality Control
                'review-comment-on-course',
                'review-approve-course',
                'review-reject-course',

            ],
            "instructor" => [
                'track-applications',
                'course-view',
                'course-create',
                'course-update',
                'course-delete'
            ],
            'student' => ['track-applications']
        ];

        // Create permissions
        $allPermissions = collect($rolesPermissions)->flatten()->unique();
        foreach ($allPermissions as $permission) {
            \Spatie\Permission\Models\Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }



        foreach ($rolesPermissions as $role => $permissions) {
            $roleModel = Role::firstOrCreate([
                "name" => $role
            ]);
            $roleModel->syncPermissions($permissions);
        }
    }
}
