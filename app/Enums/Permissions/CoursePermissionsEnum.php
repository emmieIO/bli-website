<?php

namespace App\Enums\Permissions;

enum CoursePermissionsEnum: string
{
    case VIEW_ANY = 'course-view-any';
    case ASSIGN_INSTRUCTOR = 'course-assign-instructor';
    case VIEW = 'course-view';
    case CREATE = 'course-create';
    case UPDATE = 'course-update';
    case UPDATE_ANY = 'course-update-any';
    case SUBMIT_FOR_REVIEW = 'course-submit-for-review';
    case PUBLISH = 'course-publish';
    case UNPUBLISH = 'course-unpublish';
    case DELETE = 'course-delete';
    case DELETE_ANY = 'course-delete-any';
}
