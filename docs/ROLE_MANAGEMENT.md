# Role & Permission Management System

## Overview

This application implements a comprehensive role-based access control (RBAC) system using the Spatie Laravel Permission package. The system provides granular control over user permissions across different areas of the application.

## Roles

### 1. Admin
- **Full system access** including user management, role assignment, and system configuration
- Can manage events, speakers, instructors, mentorship, support, content, and settings
- Will manage LMS courses and cohorts once the LMS module is introduced
- Access to the unified dashboard and system settings

### 2. Instructor  
- **Teaching and speaking workspaces** within their approved domain
- Mentor access is assigned separately and does not follow automatically from instructor approval
- Will teach cohorts once the LMS module is introduced
- Limited administrative capabilities focused on formation delivery
- Cannot access system-wide administrative functions

### 3. Student
- **Basic user permissions** for event registration, mentorship, support, and profile management
- Can attend events and access assigned workspaces/resources
- Will view catalogs and enroll in LMS courses once the LMS module is introduced
- Cannot create or modify educational content

## Permission Categories

### Planned LMS Management
- `lms-view-catalog`, `lms-enroll-self`
- `lms-manage-courses`, `lms-teach-cohorts`
- These permissions are reserved for the LMS module and should be added with the LMS migrations/seeders.

### Event Management
- User flow: `event-view`, `event-register`, `event-view-own-registration`, `event-join-waitlist`, `event-apply-to-speak`
- Admin flow: `event-view-any`, `event-create`, `event-update-any`, `event-delete-any`, `event-publish`, `event-cancel`, `event-archive`
- Operations: `event-manage-attendees`, `event-manage-waitlist`, `event-manage-speakers`, `event-manage-resources`, `event-view-payments`, `event-send-updates`

### Speaker Management (6 permissions)
- `create-speaker`, `view-speaker`, `edit-speaker`, `delete-speaker`
- `assign-speaker`, `approve-speaker-applications`

### Mentorship
- Student: `mentorship-view-own`
- Mentor: `mentorship-manage-assigned`
- Admin: `mentorship-manage-any`

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
@can('event-create')
    <button>Create Event</button>
@endcan

// In controllers
if (auth()->user()->can('event-view-any')) {
    // Allow action
}
```

### Policy Integration
```php
// In EventPolicy
public function create(User $user)
{
    return $user->hasRole('admin') || 
           $user->can('event-create');
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
