<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Sidebar Navigation Links
    |--------------------------------------------------------------------------
    |
    | This array defines the structure of your application's sidebar.
    | Each element can be a single link or a dropdown with children.
    |
    | 'permission' key is optional. If not provided, the link is public.
    | It can be a single string or an array of permissions (for OR logic).
    |
    */
    'links' => [

        [
            'title' => 'Dashboard',
            'icon' => 'chart-area',
            'route' => 'user_dashboard',
            'variant' => 'accent',
        ],
        [
            'title' => 'Course Management',
            'icon' => 'book-open',
            'permission' => ['course-view-own', 'course-create'],
            'exclude_permission' => ['course-view-any'], // Hide from admins who have course-view-any
            'children' => [
                [
                    'title' => 'My Courses',
                    'route' => 'instructor.courses.index',
                    'permission' => ['course-view-own'],
                ],
                [
                    'title' => 'Create Course',
                    'route' => 'instructor.courses.create',
                    'permission' => ['course-create'],
                ],
            ],
        ],
        [
            'title' => 'My Events',
            'icon' => 'calendar-heart',
            'route' => 'user.events',
        ],
        [
            'title' => 'My Invitations',
            'icon' => 'send',
            'route' => 'invitations.index',
        ],
        [
            'title' => 'Event Manager',
            'icon' => 'calendar',
            'route' => 'admin.events.index',
            'permission' => 'manage events',
        ],
        [
            'title' => 'Speaker Manager',
            'icon' => 'mic',
            'route' => 'admin.speakers.index',
            'permission' => ['create-speaker', 'view-speaker'],
        ],
        [
            'title' => 'Course Manager',
            'icon' => 'graduation-cap',
            'permission' => ['course-view-any'],
            'children' => [
                [
                    'title' => 'All Courses',
                    'route' => 'admin.courses.index',
                    'permission' => ['course-view-any'],
                ],
                [
                    'title' => 'Course Categories',
                    'route' => 'admin.category.index',
                    'permission' => ['category-view'],
                ],
            ],
        ],
        [
            'title' => 'Instructor Manager',
            'icon' => 'users',
            'route' => 'admin.instructors.index',
            'permission' => 'manage-instructor-applications',
        ],
        [
            'title' => 'Blog Manager',
            'icon' => 'blog',
            'route' => 'admin.posts.index',
            'permission' => 'manage-blog',
        ],
        [
            'title' => 'Instructor Ratings',
            'icon' => 'star',
            'route' => 'admin.ratings.index',
            'permission' => 'manage-ratings',
        ],
        [
            'title' => 'System Management',
            'icon' => 'settings',
            'permission' => ['view-user-list'],
            'children' => [
                [
                    'title' => 'User Management',
                    'route' => 'admin.users.index',
                    'permission' => ['view-user-list'],
                ],
                [
                    'title' => 'Roles & Permissions',
                    'route' => 'admin.roles.index',
                    'permission' => ['view-permission-list'],
                ],
            ],
        ],

    ],
];
