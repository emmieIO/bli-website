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
            'section' => 'Overview',
            'title' => 'Dashboard',
            'icon' => 'chart-area',
            'route' => 'user_dashboard',
            'variant' => 'accent',
        ],
        [
            'section' => 'Events',
            'title' => 'My Events',
            'icon' => 'calendar-heart',
            'route' => 'user.events',
        ],
        [
            'section' => 'Events',
            'title' => 'My Invitations',
            'icon' => 'send',
            'route' => 'invitations.index',
            'permission' => 'view-own-invitations',
        ],
        [
            'section' => 'Commerce',
            'title' => 'Transaction History',
            'icon' => 'receipt',
            'route' => 'transactions.index',
            'permission' => 'view-own-transaction-history',
        ],
        [
            'section' => 'Support',
            'title' => 'My Support Tickets',
            'icon' => 'life-buoy',
            'route' => 'user.tickets.index',
            'exclude_permission' => 'manage-tickets', // Hide from admins who manage tickets
        ],
        [
            'section' => 'Support',
            'title' => 'Support Manager',
            'icon' => 'ticket',
            'route' => 'admin.tickets.index',
            'permission' => 'manage-tickets',
        ],
        [
            'section' => 'Mentorship',
            'title' => 'My Mentorship',
            'icon' => 'user-group',
            'route' => 'student.mentorship.index',
            'permission' => ['mentorship-view-own'],
        ],
        [
            'section' => 'Mentorship',
            'title' => 'Mentorship Requests',
            'icon' => 'academic-cap',
            'route' => 'instructor.mentorship.index',
            'permission' => ['mentorship-view-instructor'],
        ],
        [
            'section' => 'Administration',
            'title' => 'Event Manager',
            'icon' => 'calendar',
            'permission' => ['event-view-any', 'event-create'],
            'children' => [
                [
                    'title' => 'All Events',
                    'route' => 'admin.events.index',
                    'permission' => 'event-view-any',
                ],
                [
                    'title' => 'Create Event',
                    'route' => 'admin.events.create',
                    'permission' => 'event-create',
                ],
            ],
        ],
        [
            'section' => 'Administration',
            'title' => 'Speaker Manager',
            'icon' => 'mic',
            'route' => 'admin.speakers.index',
            'permission' => ['create-speaker', 'view-speaker'],
        ],
        [
            'section' => 'Administration',
            'title' => 'Instructor Manager',
            'icon' => 'users',
            'route' => 'admin.instructors.index',
            'permission' => 'manage-instructor-applications',
        ],
        [
            'section' => 'Administration',
            'title' => 'Blog Manager',
            'icon' => 'blog',
            'route' => 'admin.posts.index',
            'permission' => 'manage-blog',
        ],
        [
            'section' => 'Administration',
            'title' => 'Instructor Ratings',
            'icon' => 'star',
            'route' => 'admin.ratings.index',
            'permission' => 'manage-ratings',
        ],
        [
            'section' => 'Administration',
            'title' => 'Transaction Audit',
            'icon' => 'receipt', // Using 'receipt' icon for transaction audit
            'route' => 'admin.transactions-audit.index',
            'permission' => 'view-transaction-audit',
        ],
        [
            'section' => 'Administration',
            'title' => 'Mentorship Manager',
            'icon' => 'users-cog',
            'route' => 'admin.mentorship.index',
            'permission' => ['mentorship-manage-any'],
        ],
        [
            'section' => 'Administration',
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
