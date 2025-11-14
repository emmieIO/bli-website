<?php

namespace App\Enums\Permissions;

enum CoursePermissionsEnum: string
{
    // Admin Course Permissions
    case VIEW_ANY = 'course-view-any';
    case UPDATE_ANY = 'course-update-any';
    case DELETE_ANY = 'course-delete-any';
    case APPROVE = 'course-approve';
    case REJECT = 'course-reject';
    case PUBLISH = 'course-publish';
    case UNPUBLISH = 'course-unpublish';
    case ASSIGN_INSTRUCTOR = 'course-assign-instructor';
    
    // Instructor Course Permissions
    case CREATE = 'course-create';
    case VIEW_OWN = 'course-view-own';
    case UPDATE_OWN = 'course-update-own';
    case DELETE_OWN = 'course-delete-own';
    case SUBMIT_REVIEW = 'course-submit-review';
    
    // Student Course Permissions
    case ENROLL = 'course-enroll';
    case VIEW_ENROLLED = 'course-view-enrolled';
}
