<?php

use App\Http\Controllers\Instructors\InstructorsController;
use Illuminate\Support\Facades\Route;

Route::prefix('instructors')->name('instructors.')->group(function () {
    Route::get('/start-application', [InstructorsController::class, 'registerInstructor'])->name('become-an-instructor');

    Route::get('/apply', [InstructorsController::class, 'showApplicationForm'])
    ->name('application-form')
    ->middleware('signed');

    Route::post('/apply/{user}', [InstructorsController::class, 'submitApplication'])->name('submit-application');
    Route::post('/start-application', [InstructorsController::class, 'startApplication'])->name('start-application');
    Route::get('/resume', [InstructorsController::class, 'resume'])->name('resume');
});

// For the old '/instructor/apply' route (singular), keep it outside the group if still needed:
Route::get('/instructor/apply', [InstructorsController::class, 'showApplicationForm'])
->name('instructors.application-form');
