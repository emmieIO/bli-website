<?php

use App\Http\Controllers\Auth\AuthenticatedController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\UserController;
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
    // Profile Route (Legacy - consider removing if not used)
    Route::get('/profile-legacy', [AuthenticatedController::class, 'showProfile'])->name("profile.legacy");
    Route::patch('/profile/update-information', [AuthenticatedController::class, "updatePersonalInfo"])->name("profile.update_information");
    Route::patch('/profile/update-password', [PasswordResetController::class, 'updatePassword'])->name('profile.update_password');

    // Email Verification Routes
    Route::get('/email/verify', [AuthenticatedController::class, 'showEmailVerificationNotice'])
        ->name('verification.notice');

    Route::post('/email/resend', [AuthenticatedController::class, 'resendVerificationEmail'])
        ->name('verification.send');

    // update user photo (Legacy - consider removing if not used)
    Route::patch('/profile/update-photo', [UserController::class, 'updatePhoto'])->name('profile.photo.update_legacy');

    // Logout Route
    Route::post('invalidate-session', [AuthenticatedController::class, 'logout'])->name('logout');
    Route::delete('/account/destroy', [AuthenticatedController::class, 'destroyAccount'])->name('account.destroy');
});


Route::get('/email/verify/{id}/{hash}', [AuthenticatedController::class, 'verifyEmail'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');
