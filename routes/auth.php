<?php

use App\Http\Controllers\Auth\AuthenticatedController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;


Route::get("/login", [AuthenticatedController::class, "login"])->name('login');
Route::get("/register", [RegisterController::class, "register"])->name('register');
Route::post("/register", [RegisterController::class, "store"])->name('register.store');
Route::get("/forgot-password",[PasswordResetController::class, 'forgotPassword'])->name('password.request');
Route::get("/password-reset",[PasswordResetController::class, 'passwordReset'])->name('password-reset');


Route::group(['middleware'=>['auth']], function(){
    Route::get("/profile",[ AuthenticatedController::class, "showProfile"]);
    Route::get('/email/verify', [AuthenticatedController::class, 'showEmailVerificationNotice'])
    ->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [AuthenticatedController::class, 'verifyEmail'])
        ->middleware(['signed'])
        ->name('verification.verify');
    Route::post('/email/resend', [AuthenticatedController::class, 'resendVerificationEmail'])->name('verification.send');

    Route::get("/dashboard",function(){
        return view('user_dashboard.index');
    })->name('user_dashboard')
    ->middleware(['verified']);

});

