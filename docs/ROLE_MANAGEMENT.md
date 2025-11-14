# Role & Permission Management System

## Overview

This application implements a comprehensive role-based access control (RBAC) system using the Spatie Laravel Permission package. The system provides granular control over user permissions across different areas of the application.

## Roles

### 1. Admin
- **Full system access** including user management, role assignment, and system configuration
- Can manage all courses, events, speakers, and instructors
- Access to administrative dashboard and system settings

### 2. Instructor  
- **Course and event management** within their domain
- Can create, edit courses and events they're assigned to
- Limited administrative capabilities focused on educational content
- Cannot access system-wide administrative functions

### 3. Student
- **Basic user permissions** for course enrollment and participation
- Can view and enroll in courses
- Can attend events and access learning materials
- Cannot create or modify educational content

## Permission Categories

### Course Management (12 permissions)
- `create-course`, `view-course`, `edit-course`, `delete-course`
- `publish-course`, `unpublish-course`
- `create-course-category`, `edit-course-category`, `delete-course-category`
- `create-lesson`, `edit-lesson`, `delete-lesson`

### Event Management (8 permissions)
- `manage events`, `create-event`, `edit-event`, `delete-event`
- `publish-event`, `unpublish-event`, `assign-speaker`, `manage-event-attendees`

### Speaker Management (6 permissions)
- `create-speaker`, `view-speaker`, `edit-speaker`, `delete-speaker`
- `assign-speaker`, `approve-speaker-applications`

### User & System Management (22 permissions)
- User management: `create-user`, `edit-user`, `delete-user`, `view-user-list`
- Role management: `assign-role`, `remove-role`, `create-role`, `edit-role`, `delete-role`
- Permission management: `assign-permission`, `remove-permission`
- System: `manage-instructor-applications`, `track-applications`, `manage-activity-log`
- And more system-level permissions

## Dashboard Interface

### User Management (`/admin/users`)
- **Statistics Dashboard**: Real-time user count by role
- **User Listing**: Complete user directory with role badges
- **Role Assignment**: Modal interface for assigning/removing roles
- **Quick Actions**: Direct role management from user list

### Role & Permission Management (`/admin/roles`)
- **Role Overview**: Visual cards showing role statistics and user counts
- **Permission Matrix**: Interactive table for granular permission control
- **Quick Toggles**: One-click permission enable/disable
- **Visual Feedback**: Color-coded status indicators

## Technical Implementation

### Controller
- `UserManagementController` handles all role/permission operations
- RESTful API endpoints for role assignment and permission management
- JSON responses for AJAX interactions

### Security
- All routes protected by `role:admin` middleware
- CSRF protection on all forms
- Proper authorization checks before role/permission changes

### Database
- Uses Spatie Permission tables: `roles`, `permissions`, `model_has_roles`, `model_has_permissions`
- Seeded with comprehensive permission structure
- Supports role inheritance and permission combinations

## Usage Examples

### Assigning a Role
```php
// In controller or tinker
$user = User::find(1);
$user->assignRole('instructor');
```

### Checking Permissions
```php
// In Blade templates
@can('create-course')
    <button>Create Course</button>
@endcan

// In controllers
if (auth()->user()->can('manage events')) {
    // Allow action
}
```

### Policy Integration
```php
// In CoursePolicy
public function create(User $user)
{
    return $user->hasRole('admin') || 
           ($user->hasRole('instructor') && $user->can('create-course'));
}
```

## Best Practices

1. **Principle of Least Privilege**: Users get only permissions needed for their role
2. **Role-Based Workflows**: Different roles see different interfaces and options
3. **Status-Based Access**: Combine roles with model status for dynamic access control
4. **Audit Trail**: Track role and permission changes for security
5. **Regular Reviews**: Periodically review and update permission assignments

## Future Enhancements

- **Audit Logging**: Track all role/permission changes with timestamps
- **Role Templates**: Predefined permission sets for common scenarios  
- **Bulk Operations**: Assign roles to multiple users simultaneously
- **Permission Presets**: Quick-apply common permission combinations
- **Time-Limited Roles**: Temporary role assignments with expiration
- **Department-Based Permissions**: Granular control by organizational units