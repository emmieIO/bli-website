<?php

use App\Http\Controllers\Admin\SpeakersController;
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
    Route::post('/invitations/{invite}/respond', [SpeakerInvitationController::class, 'acceptInvitation'])
        ->name('');

    Route::post('/apply-to-speak', [SpeakerApplicationController::class, 'store'])
        ->name('event.speakers.store')
        ->middleware(['signed']);
});
// register as speaker routes and invitation response routes
Route::prefix("")->group(function () {
    Route::get("/become-a-speaker", [SpeakerUserController::class, 'index'])
        ->name('become-a-speaker')->middleware('guest');
    Route::post('/become-a-speaker', [SpeakerUserController::class, 'store'])
        ->name('become-a-speaker.store');
    Route::get('/speakers/{speaker}/profile', [SpeakersController::class, 'viewSpeakerProfile'])->name("speakers.profile");
});


Route::group([
    'prefix' => "admin",
    "as" => "admin."
], function () {
    Route::middleware(['auth'])->group(function () {
        Route::get('/speakers', [SpeakersController::class, 'index'])->name("speakers.index");
        Route::get('/speakers/pending', [SpeakersController::class, 'pendingSpeaker'])->name("speakers.pending");
        Route::post('/speakers', [SpeakersController::class, 'store'])->name("speakers.store");
        Route::get('/speakers/{speaker}/activate', [SpeakersController::class, "activateSpeaker"])->name('speakers.activate');
        Route::delete('/speakers/{speaker}/destroy', [SpeakersController::class, 'destroySpeaker'])->name("speakers.destroy");
        Route::get('/events/{event}/assign-speakers', [SpeakersController::class, 'showAssignSpeakersPage'])->name('events.assign-speakers');
        Route::get('/speakers/create', [SpeakersController::class, 'create'])->name("speakers.create");
        Route::get('/speakers/{speaker}/edit', [SpeakersController::class, 'edit'])->name("speakers.edit");
        Route::get('/speakers/{speaker}/show', [SpeakersController::class, 'show'])->name("speakers.show");

    });
});