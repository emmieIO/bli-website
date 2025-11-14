<?php

use App\Http\Controllers\Course\UserCourseController;
use Illuminate\Support\Facades\Route;

// Public courses browsing (NOT resource - has custom logic)
Route::prefix('courses')->name('courses.')->group(function () {
    Route::get('/', [UserCourseController::class, 'index'])->name('index');
    Route::get('/{course}', [UserCourseController::class, 'show'])->name('show');
    
    // Authenticated learning routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/{course}/learn/{lesson?}', [UserCourseController::class, 'learn'])->name('learn');
    });
});

// Course learning interface for enrolled students
Route::middleware('auth')->group(function () {
    Route::get('/courses/{course}/learn/{lesson?}', [UserCourseController::class, 'learn'])->name('courses.learn');
});


