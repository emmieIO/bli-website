<?php

use App\Http\Controllers\Instructors\InstructorsController;
use Illuminate\Support\Facades\Route;

Route::prefix('instructors')->name('instructors.')->group(function () {
    Route::get('/start-application', [InstructorsController::class, 'registerInstructor'])->name('become-an-instructor');
    Route::get('/apply', [InstructorsController::class, 'applicationForm'])->name('application-form');
    Route::get('/apply/step1', [InstructorsController::class, 'applyStep1'])->name('apply.step1');
    Route::get('/apply/step2', [InstructorsController::class, 'applyStep2'])->name('apply.step2');
    Route::get('/apply/step3', [InstructorsController::class, 'applyStep3'])->name('apply.step3');
    Route::post('/apply', [InstructorsController::class, 'store'])->name('apply');
    Route::post('/start-application', [InstructorsController::class, 'startApplication'])->name('start-application');
    Route::get('/resume', [InstructorsController::class, 'resume'])->name('resume');
});

// For the old '/instructor/apply' route (singular), keep it outside the group if still needed:
Route::get('/instructor/apply', [InstructorsController::class, 'applicationForm'])->name('instructors.application-form');
