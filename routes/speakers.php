<?php

use App\Http\Controllers\SpeakerApplicationController;
use App\Http\Controllers\SpeakerInvitationController;
use App\Http\Controllers\SpeakerUserController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->prefix('events/{event}')->group(function () {
    Route::get('/apply-to-speak', [SpeakerApplicationController::class, 'apply'])
        ->name('event.speakers.apply')
        ->middleware(['signed']);

        Route::get('/invitations/{invite}/respond', [SpeakerApplicationController::class, 'inviteRespondView'])
        ->name('invitations.respond')
        ->middleware('signed');
        Route::post('/invitations/{invite}/respond', [SpeakerInvitationController::class ,'acceptInvitation'])
        ->name('');

    Route::post('/apply-to-speak', [SpeakerApplicationController::class, 'store'])
        ->name('event.speakers.store')
        ->middleware(['signed']);
});
// register as speaker routes and invitation response routes
Route::prefix("")->group(function(){
    Route::get("/become-a-speaker", [SpeakerUserController::class, 'index'])
    ->name('become-a-speaker');
});
