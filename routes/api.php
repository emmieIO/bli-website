<?php

use App\Http\Controllers\Api\LessonProgressController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Lesson Progress API Routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/lessons/{lesson}/progress', [LessonProgressController::class, 'updateProgress']);
    Route::post('/lessons/{lesson}/complete', [LessonProgressController::class, 'markCompleted']);
    Route::get('/lessons/{lesson}/progress', [LessonProgressController::class, 'getProgress']);
    Route::get('/courses/{course}/completion-status', [LessonProgressController::class, 'getCourseCompletion']);
});