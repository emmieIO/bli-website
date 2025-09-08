<?php

use App\Http\Controllers\Admin\InstructorApplicationController;
use App\Http\Controllers\Admin\InstructorsManagementController;
use App\Http\Controllers\Instructors\InstructorsController;
use Illuminate\Support\Facades\Route;

Route::prefix('instructors')->name('instructors.')->group(function () {
    Route::get('/start-application', [InstructorsController::class, 'registerInstructor'])->name('become-an-instructor');

    Route::get('/apply', [InstructorsController::class, 'showApplicationForm'])
    ->name('application-form')
    ->middleware('signed');
    Route::get('/view-application/{user}', [InstructorApplicationController::class, 'viewOwnApplication'])
    ->name('view-application')
    ->middleware('signed');

    Route::post('/apply/{user}', [InstructorsController::class, 'submitApplication'])->name('submit-application');
    Route::post('/start-application', [InstructorsController::class, 'startApplication'])->name('start-application');
    Route::get('/resume-application/{application}', [InstructorsController::class, 'resume'])->name('application.resume');
});

// For the old '/instructor/apply' route (singular), keep it outside the group if still needed:
Route::get('/instructor/apply', [InstructorsController::class, 'showApplicationForm'])
->name('instructors.application-form');

// instructor management and application management
Route::prefix("admin/instructors")->name('admin.instructors.')->middleware(['permission:manage-instructor-applications'])->group(function(){
    Route::get("/", [InstructorsManagementController::class, "index"])->name('index');
    Route::get("/{instructorProfile}/view", [InstructorsManagementController::class, 'showInstructor'])->name("view");
    Route::get("/{instructor}/update", [InstructorsManagementController::class, 'editInstructor'])->name("edit");
    Route::put("/{instructor}/update", [InstructorsManagementController::class, 'updateInstructor'])->name("update");
    Route::delete("/{instructorProfile}/destroy", [InstructorsManagementController::class, 'destroyInstructor'])->name("destroy");
    Route::put("/{instructorProfile}/restore", [InstructorsManagementController::class, 'restoreInstructor'])->name("restore");
    Route::get("/applications",[InstructorApplicationController::class, 'showApplications'])->name('applications');
    Route::get("/application-logs",[InstructorsManagementController::class, 'fetchApplicationLogs'])->name('application-logs');
    Route::patch('/applications/{application}/approve', [InstructorApplicationController::class, 'approve'])->name('applications.approve');
    Route::post('/applications/{application}/deny', [InstructorApplicationController::class, 'deny'])->name('applications.deny');
    Route::get('/applications/{application}', [InstructorApplicationController::class, 'view'])->name('applications.view');
    Route::delete('/application-logs/{log}', [InstructorsManagementController::class, 'deleteApplicationLog'])->name('application-logs.delete');
    });

