<?php

use App\Http\Controllers\Admin\InstructorApplicationController;
use App\Http\Controllers\Course\InstructorCourseController;
use App\Http\Controllers\Instructors\InstructorsController;
use App\Models\Course;
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
    Route::post('/create-course', [InstructorCourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}', [InstructorCourseController::class, 'show'])->name('courses.show');
    Route::get('/courses/{course}/edit', [InstructorCourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [InstructorCourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [InstructorCourseController::class, 'destroy'])->name('courses.destroy');
    Route::get('/courses/{course}/builder', [InstructorCourseController::class, 'builder'])->name('courses.builder');
    Route::post('/courses/{course}/submit-for-review', [InstructorCourseController::class, 'submitForReview'])->name('courses.submit-for-review');
    
    // Course Module Management
    Route::post('/courses/{course}/modules', [InstructorCourseController::class, 'storeModule'])->name('courses.modules.store');
    Route::put('/courses/{course}/modules/{module}', [InstructorCourseController::class, 'updateModule'])->name('courses.modules.update');
    Route::delete('/courses/{course}/modules/{module}', [InstructorCourseController::class, 'deleteModule'])->name('courses.modules.delete');
    Route::post('/courses/{course}/modules/reorder', [InstructorCourseController::class, 'reorderModules'])->name('courses.modules.reorder');
    
    // Course Lesson Management
    Route::get('/courses/{course}/modules/{module}/lessons/create', [InstructorCourseController::class, 'createLesson'])->name('courses.lessons.create');
    Route::post('/courses/{course}/modules/{module}/lessons', [InstructorCourseController::class, 'storeLesson'])->name('courses.lessons.store');
    Route::get('/courses/{course}/modules/{module}/lessons/{lesson}/edit', [InstructorCourseController::class, 'editLesson'])->name('courses.lessons.edit');
    Route::put('/courses/{course}/modules/{module}/lessons/{lesson}', [InstructorCourseController::class, 'updateLesson'])->name('courses.lessons.update');
    Route::delete('/courses/{course}/modules/{module}/lessons/{lesson}', [InstructorCourseController::class, 'deleteLesson'])->name('courses.lessons.delete');
    Route::post('/courses/{course}/modules/{module}/lessons/reorder', [InstructorCourseController::class, 'reorderLessons'])->name('courses.lessons.reorder');

    // Earnings & Payouts
    Route::get('/earnings', [\App\Http\Controllers\Instructor\InstructorEarningsController::class, 'index'])->name('earnings.index');
    Route::get('/earnings/payout', [\App\Http\Controllers\Instructor\InstructorEarningsController::class, 'createPayout'])->name('earnings.payout.create');
    Route::post('/earnings/payout', [\App\Http\Controllers\Instructor\InstructorEarningsController::class, 'storePayout'])->name('earnings.payout.store');
});

