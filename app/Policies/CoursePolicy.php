<?php

namespace App\Policies;

use App\Enums\ApplicationStatus;
use App\Enums\Permissions\CoursePermissionsEnum;
use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CoursePolicy
{
    /**
     * Determine whether the user can view any courses (Admin only).
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(CoursePermissionsEnum::VIEW_ANY->value);
    }

    /**
     * Determine whether the user can view a specific course.
     */
    public function view(User $user, Course $course): bool
    {
        // Admin can view any course
        if ($user->hasPermissionTo(CoursePermissionsEnum::VIEW_ANY->value)) {
            return true;
        }
        
        // Instructor can view their own courses
        if ($user->hasPermissionTo(CoursePermissionsEnum::VIEW_OWN->value)) {
            return $course->instructor_id === $user->id;
        }
        
        // Student can view enrolled courses
        if ($user->hasPermissionTo(CoursePermissionsEnum::VIEW_ENROLLED->value)) {
            return $course->students()->where('user_id', $user->id)->exists();
        }
        
        return false;
    }

    /**
     * Determine whether the user can create courses.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(CoursePermissionsEnum::CREATE->value);
    }

    /**
     * Determine whether the user can update a course.
     */
    public function update(User $user, Course $course): bool
    {
        // Admin can update any course
        if ($user->hasPermissionTo(CoursePermissionsEnum::UPDATE_ANY->value)) {
            return true;
        }
        
        // Instructor can update their own courses, but only if draft or rejected
        if ($user->hasPermissionTo(CoursePermissionsEnum::UPDATE_OWN->value) 
            && $course->instructor_id === $user->id) {
            return in_array($course->status, [
                ApplicationStatus::DRAFT->value, 
                ApplicationStatus::REJECTED->value
            ]);
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete a course.
     */
    public function delete(User $user, Course $course): bool
    {
        // Admin can delete any course
        if ($user->hasPermissionTo(CoursePermissionsEnum::DELETE_ANY->value)) {
            return true;
        }
        
        // Instructor can delete their own courses, but only if draft
        if ($user->hasPermissionTo(CoursePermissionsEnum::DELETE_OWN->value) 
            && $course->instructor_id === $user->id) {
            return $course->status === ApplicationStatus::DRAFT->value;
        }
        
        return false;
    }

    /**
     * Determine whether the user can submit a course for review.
     */
    public function submitForReview(User $user, Course $course): bool
    {
        return $user->hasPermissionTo(CoursePermissionsEnum::SUBMIT_REVIEW->value) 
            && $course->instructor_id === $user->id
            && in_array($course->status, [ApplicationStatus::DRAFT->value, ApplicationStatus::REJECTED->value]);
    }

    /**
     * Determine whether the user can approve a course.
     */
    public function approve(User $user, Course $course): bool
    {
        return $user->hasPermissionTo(CoursePermissionsEnum::APPROVE->value)
            && $course->status === ApplicationStatus::UNDER_REVIEW->value;
    }

    /**
     * Determine whether the user can reject a course.
     */
    public function reject(User $user, Course $course): bool
    {
        return $user->hasPermissionTo(CoursePermissionsEnum::REJECT->value)
            && $course->status === ApplicationStatus::UNDER_REVIEW->value;
    }

    /**
     * Determine whether the user can publish a course.
     */
    public function publish(User $user, Course $course): bool
    {
        return $user->hasPermissionTo(CoursePermissionsEnum::PUBLISH->value)
            && $course->status === ApplicationStatus::APPROVED->value;
    }

    /**
     * Determine whether the user can unpublish a course.
     */
    public function unpublish(User $user, Course $course): bool
    {
        return $user->hasPermissionTo(CoursePermissionsEnum::UNPUBLISH->value)
            && $course->status === ApplicationStatus::APPROVED->value; // Assuming published courses have approved status
    }

    /**
     * Determine whether the user can enroll in a course.
     */
    public function enroll(User $user, Course $course): bool
    {
        return $user->hasPermissionTo(CoursePermissionsEnum::ENROLL->value)
            && $course->status === ApplicationStatus::APPROVED->value
            && !$course->students()->where('user_id', $user->id)->exists();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Course $course): bool
    {
        return $user->hasPermissionTo(CoursePermissionsEnum::DELETE_ANY->value);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Course $course): bool
    {
        return $user->hasPermissionTo(CoursePermissionsEnum::DELETE_ANY->value);
    }
}
