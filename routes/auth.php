<?php

use App\Http\Controllers\Auth\AuthenticatedController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // Login Routes
    Route::get('/login', [AuthenticatedController::class, 'login'])->name('login');
    Route::post('/login', [AuthenticatedController::class, 'authenticate'])->name('login.store');

    // Registration Routes
    Route::get('/register', [RegisterController::class, 'register'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

    // Password Reset Routes
    Route::get('/forgot-password', [PasswordResetController::class, 'forgotPassword'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendPasswordResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'passwordReset'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'setNewPassword'])->name("password.update");

});


// Authenticated User Routes
Route::group(['middleware' => ['auth']], function () {
    // Profile Route
    Route::get('/profile', [AuthenticatedController::class, 'showProfile'])->name("profile");

    // Email Verification Routes
    Route::get('/email/verify', [AuthenticatedController::class, 'showEmailVerificationNotice'])
        ->name('verification.notice');

    Route::post('/email/resend', [AuthenticatedController::class, 'resendVerificationEmail'])
        ->name('verification.send');

    // Dashboard Route (requires verified email)
    Route::get('/dashboard', function () {
        return view('user_dashboard.index');
    })->name('user_dashboard')->middleware(['verified']);

    // Logout Route
    Route::post('invalidate-session', [AuthenticatedController::class, 'logout'])->name('logout');
});
    Route::get('/email/verify/{id}/{hash}', [AuthenticatedController::class, 'verifyEmail'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
