<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\Auth\AdminAuthController;
use App\Http\Controllers\Admin\EventController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'=>"admin",
    "as"=>"admin."
], function (){


    // Event management
    Route::middleware(['auth', 'permission:manage events'])->group(function(){
        Route::get("/events", [EventController::class, "index"])->name("events.index");
        Route::get("/events/create", [EventController::class, "create"])->name("events.create");
        Route::post("/events", [EventController::class, "store"])->name("events.store");
        Route::get("/events/{event}/show", [EventController::class, "show"])->name("events.show");
        Route::get('/events/{slug}/edit', [EventController::class, "edit"])->name("events.edit");
        Route::put("/events/{event}/update", [EventController::class, 'update'])->name('events.update');
        Route::delete('/events/mass-delete', [EventController::class, 'massDelete'])->name('events.massDelete');
        Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    });
});
