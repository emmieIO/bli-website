<?php

use App\Http\Controllers\Admin\AssignSpeakerToEvent;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\EventResourceController;
use App\Http\Controllers\Admin\InstructorApplicationController;
use App\Http\Controllers\Admin\InstructorsManagementController;
use App\Http\Controllers\Admin\SpeakersController;
use App\Http\Controllers\Course\CourseCategoryController;
use App\Http\Controllers\Course\CourseController;
use App\Http\Controllers\Course\CourseModuleController;
use App\Http\Controllers\Course\CourseOutcomeController;
use App\Http\Controllers\Course\CourseResourseController;
use App\Http\Controllers\Course\LessonController;
use App\Http\Controllers\SpeakerApplicationController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    // Events Management (NOT resource - has custom methods like massDelete, inviteSpeaker)
    Route::middleware(['permission:manage events'])->group(function () {
        Route::get('/events', [EventController::class, 'index'])->name('events.index');
        Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
        Route::post('/events', [EventController::class, 'store'])->name('events.store');
        Route::get('/events/{event}/show', [EventController::class, 'show'])->name('events.show');
        Route::get('/events/{slug}/edit', [EventController::class, 'edit'])->name('events.edit');
        Route::put('/events/{event}/edit', [EventController::class, 'update'])->name('events.update');
        Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
        Route::delete('/events/mass-delete', [EventController::class, 'massDelete'])->name('events.massDelete');

        // Event-specific actions
        Route::post('/events/{event}/invite-speaker', [EventController::class, 'inviteSpeaker'])->name('events.invite-speaker');
        Route::post('/events/{event}/assign-speaker', AssignSpeakerToEvent::class)->name('events.assign-speaker');
        Route::get('/events/{event}/assign-speakers', [SpeakersController::class, 'showAssignSpeakersPage'])->name('events.assign-speakers');
    });

    // Event Resources (simple operations)
    Route::get('/events/{event}/create-resource', [EventResourceController::class, 'create'])->name('events.resources.create');
    Route::post('/events/{event}/create-resource', [EventResourceController::class, 'store'])->name('events.resources.store');
    Route::delete('/events/{event}/destroy-resource/{resource}', [EventResourceController::class, 'destroy'])->name('events.resources.destroy');

    // Speakers Management (NOT resource - has custom methods like pendingSpeaker, activateSpeaker)
    Route::get('/speakers', [SpeakersController::class, 'index'])->name('speakers.index');
    Route::get('/speakers/pending', [SpeakersController::class, 'pendingSpeaker'])->name('speakers.pending');
    Route::get('/speakers/create', [SpeakersController::class, 'create'])->name('speakers.create');
    Route::post('/speakers', [SpeakersController::class, 'store'])->name('speakers.store');
    Route::get('/speakers/{speaker}/show', [SpeakersController::class, 'show'])->name('speakers.show');
    Route::get('/speakers/{speaker}/edit', [SpeakersController::class, 'edit'])->name('speakers.edit');
    Route::put('/speakers/{speaker}/edit', [SpeakersController::class, 'update'])->name('speakers.update');
    Route::get('/speakers/{speaker}/activate', [SpeakersController::class, 'activateSpeaker'])->name('speakers.activate');
    Route::delete('/speakers/{speaker}/destroy', [SpeakersController::class, 'destroySpeaker'])->name('speakers.destroy');

    // Speaker Applications (NOT resource - custom methods)
    Route::get('/speakers/applications/pending', [SpeakerApplicationController::class, 'pendingApplications'])->name('speakers.applications.pending');
    Route::get('/speakers/applications/approved', [SpeakerApplicationController::class, 'approvedApplications'])->name('speakers.applications.approved');
    Route::get('/speakers/applications/{application}/review', [SpeakerApplicationController::class, 'reviewApplication'])->name('speakers.application.review');
    Route::post('/speakers/applications/{application}/approve', [SpeakerApplicationController::class, 'approveApplication'])->name('speakers.application.approve');
    Route::patch('/speakers/applications/{application}/reject', [SpeakerApplicationController::class, 'rejectApplication'])->name('speakers.application.reject');
    Route::patch('/speakers/applications/{application}/revoke', [SpeakerApplicationController::class, 'revokeApproval'])->name('speakers.application.revoke');

        // Instructors Management (custom methods - NOT pure resource)
    Route::middleware(['permission:manage-instructor-applications'])->group(function () {
        Route::get('/instructors', [InstructorsManagementController::class, 'index'])->name('instructors.index');
        Route::get('/instructors/{instructor}/view', [InstructorsManagementController::class, 'view'])->name('instructors.view');
        Route::get('/instructors/{instructor}/edit', [InstructorsManagementController::class, 'edit'])->name('instructors.edit');
        Route::post('/instructors/{instructor}/update', [InstructorsManagementController::class, 'update'])->name('instructors.update');
        Route::delete('/instructors/{instructor}/destroy', [InstructorsManagementController::class, 'destroyInstructor'])->name('instructors.destroy');
        Route::post('/instructors/{instructor}/restore', [InstructorsManagementController::class, 'restoreInstructor'])->name('instructors.restore');
        Route::get('/instructors/applications', [InstructorApplicationController::class, 'showApplications'])->name('instructors.applications');
        Route::get('/instructors/application-logs', [InstructorsManagementController::class, 'fetchApplicationLogs'])->name('instructors.application-logs');
        Route::get('/instructors/applications/{application}', [InstructorApplicationController::class, 'view'])->name('instructors.applications.view');
        Route::patch('/instructors/applications/{application}/approve', [InstructorApplicationController::class, 'approve'])->name('instructors.applications.approve');
        Route::post('/instructors/applications/{application}/deny', [InstructorApplicationController::class, 'deny'])->name('instructors.applications.deny');
        Route::delete('/instructors/application-logs/{log}', [InstructorsManagementController::class, 'deleteApplicationLog'])->name('instructors.application-logs.delete');
    });

    // User & Role Management (Admin only)
    Route::middleware(['role:admin'])->group(function () {
        // User Management
        Route::get('/users', [\App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('users.index');
        Route::post('/users/{user}/assign-role', [\App\Http\Controllers\Admin\UserManagementController::class, 'assignRole'])->name('users.assign-role');
        Route::delete('/users/{user}/remove-role/{role}', [\App\Http\Controllers\Admin\UserManagementController::class, 'removeRole'])->name('users.remove-role');
        Route::get('/users/statistics', [\App\Http\Controllers\Admin\UserManagementController::class, 'statistics'])->name('users.statistics');

        // Role & Permission Management
        Route::get('/roles', [\App\Http\Controllers\Admin\UserManagementController::class, 'roles'])->name('roles.index');
        Route::post('/roles/update-permissions/{role}', [\App\Http\Controllers\Admin\UserManagementController::class, 'updateRolePermissions'])->name('roles.update-permissions');
        Route::post('/roles/toggle-permission', [\App\Http\Controllers\Admin\UserManagementController::class, 'togglePermission'])->name('roles.toggle-permission');
        Route::post('/roles/reset-defaults', [\App\Http\Controllers\Admin\UserManagementController::class, 'resetToDefaults'])->name('roles.reset-defaults');
        Route::get('/roles/export', [\App\Http\Controllers\Admin\UserManagementController::class, 'exportConfiguration'])->name('roles.export');
        Route::get('/roles/audit-log', [\App\Http\Controllers\Admin\UserManagementController::class, 'auditLog'])->name('roles.audit-log');
    });

    // Blog Management
    Route::middleware(['permission:manage-blog'])->group(function () {
        Route::resource('posts', \App\Http\Controllers\Admin\PostController::class);
    });

    // Instructor Ratings Management
    Route::middleware(['permission:manage-ratings'])->group(function () {
        Route::get('/ratings', [\App\Http\Controllers\Admin\InstructorRatingController::class, 'index'])->name('ratings.index');
        Route::get('/ratings/{rating}', [\App\Http\Controllers\Admin\InstructorRatingController::class, 'show'])->name('ratings.show');
        Route::delete('/ratings/{rating}', [\App\Http\Controllers\Admin\InstructorRatingController::class, 'destroy'])->name('ratings.destroy');
    });

    // Course Categories (appears to be pure CRUD - can use resource)
    Route::resource('courses/category', CourseCategoryController::class);

    // Courses (has custom builder method - NOT pure resource)
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
    Route::get('/courses/builder/{course}', [CourseController::class, 'builder'])->name('courses.builder');

    // Course approval/rejection routes
    Route::post('/courses/{course}/approve', [CourseController::class, 'approve'])->name('courses.approve');
    Route::post('/courses/{course}/reject', [CourseController::class, 'reject'])->name('courses.reject');

    // Course nested resources (appear to be CRUD)
    Route::resource('{course}/requirements', CourseResourseController::class);
    Route::resource('{course}/outcomes', CourseOutcomeController::class);
    Route::resource('{course}/modules', CourseModuleController::class);
    Route::resource('{module}/lessons', LessonController::class);
});
