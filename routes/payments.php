<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

// Payment routes
Route::middleware(['auth'])->group(function () {
    // Checkout page for a course
    Route::get('/courses/{course}/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');

    // Initialize payment
    Route::post('/courses/{course}/payment/initialize', [PaymentController::class, 'initializePayment'])->name('payment.initialize');

    // Payment callback (after payment on Flutterwave)
    Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
});

// Webhook endpoint (no auth required - Flutterwave will call this)
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');
