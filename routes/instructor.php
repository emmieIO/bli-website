<?php

use App\Http\Controllers\Admin\InstructorApplicationController;
use App\Http\Controllers\Course\InstructorCourseController;
use App\Http\Controllers\Instructors\InstructorsController;
use Illuminate\Support\Facades\Route;

// Instructor application workflow (keep existing signed route logic)
Route::get('/instructors/apply', [InstructorsController::class, 'showApplicationForm'])
    ->name('instructors.application-form')
    ->middleware('signed');

Route::get('/instructors/view-application/{user}', [InstructorApplicationController::class, 'viewOwnApplication'])
    ->name('instructors.view-application')
    ->middleware('signed');

Route::post('/instructors/apply/{user}', [InstructorsController::class, 'submitApplication'])
    ->name('instructors.submit-application');

Route::post('/instructors/start-application', [InstructorsController::class, 'startApplication'])
    ->name('instructors.start-application');

Route::get('/instructors/resume-application/{application}', [InstructorsController::class, 'resume'])
    ->name('instructors.application.resume');

// For the old '/instructor/apply' route (singular), keep it for backward compatibility:
Route::get('/instructor/apply', [InstructorsController::class, 'showApplicationForm'])
    ->name('instructor.application-form');

// Authenticated instructor dashboard and courses
Route::prefix('instructor')->name('instructor.')->middleware(['auth', 'role:instructor'])->group(function () {
    Route::get('/my-courses', [InstructorCourseController::class, 'index'])->name('courses.index');
    Route::get('/create-course', [InstructorCourseController::class, 'create'])->name('courses.create');
    // Add other instructor course methods as they're implemented
});

