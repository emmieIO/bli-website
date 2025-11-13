<?php

use App\Http\Controllers\Course\UserCourseController;
use Illuminate\Support\Facades\Route;

// Public courses browsing (NOT resource - has custom logic)
Route::get('/courses', [UserCourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course}', [UserCourseController::class, 'show'])->name('courses.show');


