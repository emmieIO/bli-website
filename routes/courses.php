<?php

use App\Http\Controllers\Course\{
    CourseController,
    CourseModuleController,
    CourseOutcomeController,
    CourseCategoryController,
    CourseResourseController,
    LessonController};
use App\Http\Controllers\Course\UserCourseController;
use Illuminate\Support\Facades\Route;


// Course category routes
Route::group([
    "as" => "admin.",
    "middleware" => ["auth"]
], function(){
    Route::resource('admin/courses/category', CourseCategoryController::class);
    Route::resource('admin/courses', CourseController::class);
    Route::resource('admin/{course}/requirements', CourseResourseController::class);
    Route::resource('admin/{course}/outcomes', CourseOutcomeController::class);
    Route::resource("admin/{course}/modules", CourseModuleController::class);
    Route::resource("admin/{module}/lessons", LessonController::class);
    Route::get('admin/courses/builder/{course}', [CourseController::class, 'builder'])->name('courses.builder');
});


Route::resource("/courses", UserCourseController::class);


