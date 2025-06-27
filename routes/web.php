<?php

use App\Http\Controllers\ProgrammeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('homepage');

Route::get("/events", [ProgrammeController::class, 'index'])->name("events.index");
