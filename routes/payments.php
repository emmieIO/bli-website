<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

// Payment routes
Route::middleware(['auth'])->group(function () {
    // Checkout page for an event
    Route::get('/events/{event}/checkout', [PaymentController::class, 'checkoutEvent'])->name('events.checkout');

    // Payment callback (after payment on Flutterwave)
    Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');

    // Verify/Resume pending payment
    Route::get('/payment/verify/{reference}', [PaymentController::class, 'verifyPayment'])->name('payment.verify');
});

// Rate-limited payment initialization routes (10 requests per minute)
Route::middleware(['auth', 'throttle:10,1'])->group(function () {
    // Initialize event payment
    Route::post('/events/{event}/payment/initialize', [PaymentController::class, 'initializeEventPayment'])->name('payment.event.initialize');
});

// Webhook endpoint (no auth required - Flutterwave will call this)
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');
