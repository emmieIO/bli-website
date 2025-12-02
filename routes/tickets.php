<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\TicketController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;

// User routes
Route::middleware(['auth', 'verified'])->prefix('user')->name('user.')->group(function () {
    Route::get('/support', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/support/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::get('/support/{ticket}', [TicketController::class, 'show'])->name('tickets.show');

    // Rate-limited ticket creation and replies (5 requests per minute to prevent spam)
    Route::middleware(['throttle:5,1'])->group(function () {
        Route::post('/support', [TicketController::class, 'store'])->name('tickets.store');
        Route::post('/support/{ticket}/reply', [TicketController::class, 'reply'])->name('tickets.reply');
    });
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/support', [AdminTicketController::class, 'index'])->name('tickets.index');
    Route::get('/support/{ticket}', [AdminTicketController::class, 'show'])->name('tickets.show');
    Route::post('/support/{ticket}/reply', [AdminTicketController::class, 'reply'])->name('tickets.reply');
    Route::post('/support/{ticket}/status', [AdminTicketController::class, 'status'])->name('tickets.status');
});
