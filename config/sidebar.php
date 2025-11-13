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
            'title' => 'My Courses',
            'icon' => 'book-open',
            'route' => 'instructor.courses.index',
            'permission' => ['course-view']
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
                    'title' => 'Course Categories',
                    'route' => 'admin.category.index',
                    'permission' => ['category-view'],
                ],
                [
                    'title' => 'Course Management',
                    'route' => 'admin.courses.index',
                    'permission' => ['course-view-any'],
                ],
            ],
        ],
        [
            'title' => 'Instructor Manager',
            'icon' => 'users',
            'route' => 'admin.instructors.index',
            'permission' => 'manage-instructor-applications',
        ],

    ]
];