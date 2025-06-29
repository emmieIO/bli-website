<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\ProgrammeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('homepage');

// Contact-us
Route::get("/contact-us", function(){
    return view("contact.contact");
})->name('contact-us');

Route::get("/events", [ProgrammeController::class, 'index'])->name("events.index");
Route::get('/events/{slug}', [ProgrammeController::class, "show"])->name("events.show");
Route::get('/events/{slug}/join', [ProgrammeController::class, "join"])->name("events.join");


// Courses
Route::get("/courses", [CourseController::class, "index"])->name("courses.index");

require __DIR__.'\auth.php';
