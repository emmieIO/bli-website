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
    });
});
