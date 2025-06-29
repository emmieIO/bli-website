<?php

use App\Http\Controllers\Auth\AuthenticatedController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;


Route::get("/login", [AuthenticatedController::class, "login"])->name('login');
Route::get("/register", [RegisterController::class, "register"])->name('register');
Route::get("/forgot-password",[PasswordResetController::class, 'forgotPassword'])->name('password.request');
Route::get("/password-reset",[PasswordResetController::class, 'passwordReset'])->name('password-reset');

Route::get("/dashboard",function(){
    return view('user_dashboard.index');
});
Route::get("/profile",[ AuthenticatedController::class, "showProfile"]);
Route::get('/email/verify', [AuthenticatedController::class, 'showEmailVerificationNotice'])->name('verification.notice');
// Route::get('/email/verify/{id}/{hash}', [AuthenticatedController::class, 'verifyEmail'])
    // ->middleware(['signed'])
    // ->name('verification.verify');
// Route::post('/email/resend', [AuthenticatedController::class, 'resendVerificationEmail'])->name('verification.resend');
