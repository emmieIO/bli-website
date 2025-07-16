<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AssignSpeakerToEvent;
use App\Http\Controllers\Admin\Auth\AdminAuthController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\EventResourceController;
use App\Http\Controllers\Admin\SpeakersController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'=>"admin",
    "as"=>"admin."
], function (){

    // Event management
    Route::middleware(['auth', 'permission:manage events'])->group(function(){
        Route::post("/events", [EventController::class, "store"])->name("events.store");
        Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
        Route::delete('/events/mass-delete', [EventController::class, 'massDelete'])->name('events.massDelete');
        Route::get("/events", [EventController::class, "index"])->name("events.index");
        Route::get("/events/create", [EventController::class, "create"])->name("events.create");
        Route::get("/events/{event}/show", [EventController::class, "show"])->name("events.show");
        Route::get('/events/{slug}/edit', [EventController::class, "edit"])->name("events.edit");
        Route::put("/events/{event}/update", [EventController::class, 'update'])->name('events.update');
    });

    // Event Speakers Management
    Route::middleware([])->group(function(){
        Route::delete('/speakers/{speaker}/destroy', [SpeakersController::class, 'destroySpeaker'])->name("speakers.destroy");
        Route::get('/events/{event}/assign-speakers', [SpeakersController::class, 'showAssignSpeakersPage'])->name('events.assign-speakers');
        Route::post('/events/{event}/assign-speaker', AssignSpeakerToEvent::class)->name('events.assign-speaker');
        Route::get('/events/{event}/create-resource', [EventResourceController::class, "create"])->name('events.resources.create');
        Route::get('/speakers', [SpeakersController::class, 'index'])->name("speakers.index");
        Route::get('/speakers/create', [SpeakersController::class, 'create'])->name("speakers.create");
        Route::get('/speakers/{speaker}/edit', [SpeakersController::class, 'edit'])->name("speakers.edit");
        Route::get('/speakers/{speaker}/show', [SpeakersController::class, 'show'])->name("speakers.show");
        Route::post('/events/{event}/create-resource', [EventResourceController::class, "store"])->name('events.resources.store');
        Route::delete('/events/{event}/destroy-resource/{resource}', [EventResourceController::class, "destroy"])->name('events.resources.destroy');
        Route::post('/speakers/store', [SpeakersController::class, 'store'])->name("speakers.store");
        Route::put('/speakers/{speaker}/edit', [SpeakersController::class, 'update'])->name("speakers.update");
    });
});
