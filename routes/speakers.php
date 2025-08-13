<?php

use App\Http\Controllers\SpeakerApplicationController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->prefix('events/{event}')->group(function () {
    Route::get('/apply-to-speak', [SpeakerApplicationController::class, 'apply'])
        ->name('event.speakers.apply')
        ->middleware(['signed']);

    Route::post('/apply-to-speak', [SpeakerApplicationController::class, 'store'])
        ->name('event.speakers.store')
        ->middleware(['signed']);
});

