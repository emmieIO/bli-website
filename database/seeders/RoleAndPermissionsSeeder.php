<?php

namespace Database\Seeders;

use App\Enums\Permissions\EventPermissionsEnum;
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
        $eventAdminPermissions = [
            EventPermissionsEnum::VIEW_ANY->value,
            EventPermissionsEnum::CREATE->value,
            EventPermissionsEnum::UPDATE_ANY->value,
            EventPermissionsEnum::DELETE_ANY->value,
            EventPermissionsEnum::PUBLISH->value,
            EventPermissionsEnum::MANAGE_ATTENDEES->value,
            EventPermissionsEnum::MANAGE_WAITLIST->value,
            EventPermissionsEnum::MANAGE_SPEAKERS->value,
            EventPermissionsEnum::MANAGE_RESOURCES->value,
            EventPermissionsEnum::VIEW_PAYMENTS->value,
            EventPermissionsEnum::SEND_UPDATES->value,
            EventPermissionsEnum::CANCEL->value,
            EventPermissionsEnum::ARCHIVE->value,
        ];

        $eventUserPermissions = [
            EventPermissionsEnum::VIEW->value,
            EventPermissionsEnum::REGISTER->value,
            EventPermissionsEnum::VIEW_OWN_REGISTRATION->value,
            EventPermissionsEnum::JOIN_WAITLIST->value,
            EventPermissionsEnum::APPLY_TO_SPEAK->value,
        ];

        $rolesPermissions = [
                                    'admin' => [
                                        // Dashboard & System
                                        'analytics-view-system',     // System-wide course analytics

                                        // Event management
                                        ...$eventAdminPermissions,
                                        EventPermissionsEnum::APPLY_TO_SPEAK->value,

                                        // Speaker management
                                        "create-speaker",
                                        'view-speaker',
                                        "edit-speaker",
                                        'delete-speaker',
                                        'assign-speaker',
                                        'approve-speaker-applications',
                                        
                                        // Instructor Management
                                        "manage-instructor-applications",
                                        
                                        // Mentorship Management
                                        'mentorship-manage-any',

                                        // Blog Management
                                        'manage-blog',
                        
                                        // Instructor Ratings Management
                                        'manage-ratings',
                        
                                        // User Management
                                        'create-user',
                                        'view-user',
                                        'edit-user',
                                        'delete-user',
                                        'view-user-list',
                        
                                        // Role Management
                                        'assign-role',
                                        'remove-role',
                                        'create-role',
                                        'edit-role',
                                        'delete-role',
                                        'view-role-list',
                        
                                        // Permission Management
                                        'assign-permission',
                                        'remove-permission',
                                        'view-permission-list',
                                        'track-applications',
                                        'manage-activity-log',
                        
                                        // Categories (Admin only)
                                        'category-view',
                                        'category-create',
                                        'category-update',
                                        'category-delete',
                                        'category-approve',
                        
                                        // Course Management (Admin powers)
                                        'course-view-any',           // View all courses system-wide
                                        'course-update-any',         // Edit any course (override instructor)
                                        'course-delete-any',         // Delete any course
                                        'course-approve',            // Approve submitted courses
                                        'course-reject',             // Reject submitted courses
                                        'course-publish',            // Publish approved courses
                                        'course-unpublish',          // Unpublish courses
                                        'course-assign-instructor',  // Reassign course to different instructor
                        
                                        // Module Management (Admin override)
                                        'module-manage-any',         // Manage modules in any course
                        
                                        // Lesson Management (Admin override)
                                        'lesson-manage-any',         // Manage lessons in any course
                        
                                        // Enrollment Management
                                        'enrollment-manage-any',     // Manage all enrollments
                                        'enrollment-view-progress',  // View all student progress
                        
                                        // Ticket Management
                                        'manage-tickets',

                                        // Earnings Management
                                        'earnings-manage-any',   // Manage all instructor earnings and payouts
                                        
                                        // Transaction Audit
                                        'view-transaction-audit',

                                    ],
                        
            
                        'student' => [
                        
            
                            'track-applications',
                        
            
                            'course-enroll',             // Enroll in courses
                        
            
                            'course-view-enrolled',      // View enrolled courses
                        
            
                            'lesson-view-enrolled',      // View lessons in enrolled courses
                        
            
                            'progress-track-own',        // Track own learning progress
                        
            
                            'view-own-transaction-history', // View own transaction history
                        
            
                            'view-own-invitations',      // View own invitations

                            ...$eventUserPermissions,
                        
            
                        ],
                        
            
                        
                        
            
                        'instructor' => [
                        
            
                            // Application tracking
                        
            
                            'track-applications',
                        
            
                            
                        
            
                            // Course Creation & Management (Own courses only)
                        
            
                            'course-create',             // Create new courses (draft state)
                        
            
                            'course-view-own',           // View own courses
                        
            
                            'course-update-own',         // Edit own courses (draft/rejected only)
                        
            
                            'course-submit-review',      // Submit course for admin approval
                        
            
                            'course-delete-own',         // Delete own courses (draft only)
                        
            
                            
                        
            
                            // Course Building (Own courses only)
                        
            
                            'module-create',             // Create modules in own courses
                        
            
                            'module-update',             // Update modules in own courses
                        
            
                            'module-delete',             // Delete modules in own courses
                        
            
                            'module-reorder',            // Reorder modules in own courses
                        
            
                            
                        
            
                            'lesson-create',             // Create lessons in own courses
                        
            
                            'lesson-update',             // Update lessons in own courses
                        
            
                            'lesson-delete',             // Delete lessons in own courses
                        
            
                            'lesson-reorder',            // Reorder lessons in own courses
                        
            
                            
                        
            
                            // Student Management (Own courses only)
                        
            
                            'enrollment-view-own',       // View enrollments in own courses
                        
            
                            'student-progress-view',     // View student progress in own courses
                        
            
                            
                        
            
                            // Analytics (Own courses only)
                        
            
                            'analytics-view-own',        // View analytics for own courses
                        
            
            
                        
            
                            // Earnings & Payouts
                        
            
                            'earnings-view-own',         // View own earnings and request payouts
                        
            
                            'view-own-transaction-history', // View own transaction history
                        
            
                            'view-own-invitations',      // View own invitations

                            ...$eventUserPermissions,
                        
            
                        ]
                        ,

                        'speaker' => [
                            'track-applications',
                            'view-own-invitations',
                            ...$eventUserPermissions,
                        ]
                        
            
            
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
