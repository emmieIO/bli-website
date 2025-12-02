<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

// Payment routes
Route::middleware(['auth'])->group(function () {
    // Checkout page for a course
    Route::get('/courses/{course}/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');

    // Payment callback (after payment on Flutterwave)
    Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');

    // Verify/Resume pending payment
    Route::get('/payment/verify/{reference}', [PaymentController::class, 'verifyPayment'])->name('payment.verify');
});

// Rate-limited payment initialization routes (10 requests per minute)
Route::middleware(['auth', 'throttle:10,1'])->group(function () {
    // Initialize payment
    Route::post('/courses/{course}/payment/initialize', [PaymentController::class, 'initializePayment'])->name('payment.initialize');

    // Initialize cart payment
    Route::post('/cart/payment/initialize', [PaymentController::class, 'initializeCartPayment'])->name('payment.cart.initialize');
});

// Webhook endpoint (no auth required - Flutterwave will call this)
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');
