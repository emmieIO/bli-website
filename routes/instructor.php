<?php

use App\Http\Controllers\Admin\InstructorApplicationController;
use App\Http\Controllers\Instructors\InstructorsController;
use Illuminate\Support\Facades\Route;

// Instructor application workflow (keep existing signed route logic)
Route::get('/instructors/apply', [InstructorsController::class, 'showApplicationForm'])
    ->name('instructors.application-form')
    ->middleware('signed');

Route::get('/instructors/view-application/{user}', [InstructorApplicationController::class, 'viewOwnApplication'])
    ->name('instructors.view-application')
    ->middleware('signed');

Route::post('/instructors/apply/{user}/personal', [InstructorsController::class, 'savePersonalInformation'])
    ->name('instructors.save-personal-info');

Route::post('/instructors/apply/{user}/experience', [InstructorsController::class, 'saveExperienceData'])
    ->name('instructors.save-experience');

Route::post('/instructors/apply/{user}/documents', [InstructorsController::class, 'saveInstructorsDocuments'])
    ->name('instructors.save-documents');

Route::post('/instructors/apply/{user}', [InstructorsController::class, 'finalizeApplication'])
    ->name('instructors.submit-application');

Route::post('/instructors/start-application', [InstructorsController::class, 'startApplication'])
    ->name('instructors.start-application');

Route::get('/instructors/resume-application/{application}', [InstructorsController::class, 'resume'])
    ->name('instructors.application.resume');

// For the old '/instructor/apply' route (singular), keep it for backward compatibility:
Route::get('/instructor/apply', [InstructorsController::class, 'showApplicationForm'])
    ->name('instructor.application-form');

// Authenticated instructor dashboard
Route::prefix('instructor')->name('instructor.')->middleware(['auth', 'instructor.access'])->group(function () {
});
