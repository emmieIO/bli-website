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
                                        // Event management
                                        'manage events',
                        
                                        // Speaker management
                                        "create-speaker",
                                        'view-speaker',
                                        "edit-speaker",
                                        'delete-speaker',
                                        'assign-speaker',
                                        "manage-instructor-applications",
                                        'approve-speaker-applications',
                                        'track-applications',
                                        'manage-activity-log',
                        
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
                        
                                                        // System Analytics
                        
                                                        'analytics-view-system',     // System-wide course analytics
                        
                                                        
                        
                                                        // Ticket Management
                        
                                                        'manage-tickets',
                        
                                                    ],
                        
                                                    
                        
                                                    "instructor" => [
                        
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
            ],
            
            'student' => [
                'track-applications',
                'course-enroll',             // Enroll in courses
                'course-view-enrolled',      // View enrolled courses
                'lesson-view-enrolled',      // View lessons in enrolled courses
                'progress-track-own',        // Track own learning progress
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
