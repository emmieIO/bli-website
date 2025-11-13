<?php

use App\Http\Controllers\Admin\SpeakersController;
use Illuminate\Support\Facades\Route;

// Public speaker profile view
Route::get('/speakers/{speaker}/profile', [SpeakersController::class, 'viewSpeakerProfile'])->name("speakers.profile");