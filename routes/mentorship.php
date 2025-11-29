<?php

use App\Http\Controllers\Mentorship\AdminMentorshipController;
use App\Http\Controllers\Mentorship\InstructorMentorshipController;
use App\Http\Controllers\Mentorship\StudentMentorshipController;
use App\Http\Controllers\Mentorship\MentorshipSessionController;
use App\Http\Controllers\Mentorship\MentorshipResourceController;
use App\Http\Controllers\Mentorship\MentorshipMilestoneController;
use Illuminate\Support\Facades\Route;

// Student Mentorship Routes
Route::middleware(['auth', 'role:student'])->prefix('student/mentorship')->name('student.mentorship.')->group(function () {
    Route::get('/', [StudentMentorshipController::class, 'index'])->name('index');
    Route::get('/create', [StudentMentorshipController::class, 'create'])->name('create');
    Route::post('/', [StudentMentorshipController::class, 'store'])->name('store');
    Route::get('/{id}', [StudentMentorshipController::class, 'show'])->name('show');
    Route::post('/{id}/cancel', [StudentMentorshipController::class, 'cancel'])->name('cancel');
    Route::post('/{id}/end', [StudentMentorshipController::class, 'end'])->name('end');
});

// Instructor Mentorship Routes
Route::middleware(['auth', 'role:instructor'])->prefix('instructor/mentorship')->name('instructor.mentorship.')->group(function () {
    Route::get('/', [InstructorMentorshipController::class, 'index'])->name('index');
    Route::get('/{id}', [InstructorMentorshipController::class, 'show'])->name('show');
    Route::post('/{id}/approve', [InstructorMentorshipController::class, 'approve'])->name('approve');
    Route::post('/{id}/reject', [InstructorMentorshipController::class, 'reject'])->name('reject');
    Route::post('/{id}/end', [InstructorMentorshipController::class, 'end'])->name('end');
});

// Admin Mentorship Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin/mentorship')->name('admin.mentorship.')->group(function () {
    Route::get('/', [AdminMentorshipController::class, 'index'])->name('index');
    Route::get('/{id}', [AdminMentorshipController::class, 'show'])->name('show');
    Route::post('/{id}/approve', [AdminMentorshipController::class, 'approve'])->name('approve');
    Route::post('/{id}/reject', [AdminMentorshipController::class, 'reject'])->name('reject');
    Route::post('/{id}/end', [AdminMentorshipController::class, 'end'])->name('end');
});

// Mentorship Session Routes (accessible to both student and instructor)
Route::middleware(['auth'])->prefix('mentorship/{mentorshipRequest}')->name('mentorship.')->group(function () {
    // Sessions
    Route::prefix('sessions')->name('sessions.')->group(function () {
        Route::get('/', [MentorshipSessionController::class, 'index'])->name('index');
        Route::get('/create', [MentorshipSessionController::class, 'create'])->name('create');
        Route::post('/', [MentorshipSessionController::class, 'store'])->name('store');
        Route::get('/{session}', [MentorshipSessionController::class, 'show'])->name('show');
        Route::put('/{session}', [MentorshipSessionController::class, 'update'])->name('update');
        Route::post('/{session}/complete', [MentorshipSessionController::class, 'markComplete'])->name('complete');
        Route::delete('/{session}', [MentorshipSessionController::class, 'destroy'])->name('destroy');
    });

    // Resources
    Route::prefix('resources')->name('resources.')->group(function () {
        Route::get('/', [MentorshipResourceController::class, 'index'])->name('index');
        Route::post('/', [MentorshipResourceController::class, 'store'])->name('store');
        Route::get('/{resource}/download', [MentorshipResourceController::class, 'download'])->name('download');
        Route::delete('/{resource}', [MentorshipResourceController::class, 'destroy'])->name('destroy');
    });

    // Milestones
    Route::prefix('milestones')->name('milestones.')->group(function () {
        Route::get('/', [MentorshipMilestoneController::class, 'index'])->name('index');
        Route::post('/', [MentorshipMilestoneController::class, 'store'])->name('store');
        Route::put('/{milestone}', [MentorshipMilestoneController::class, 'update'])->name('update');
        Route::post('/{milestone}/toggle', [MentorshipMilestoneController::class, 'toggleComplete'])->name('toggle');
        Route::delete('/{milestone}', [MentorshipMilestoneController::class, 'destroy'])->name('destroy');
    });
});
