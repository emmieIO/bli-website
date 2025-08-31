<?php

use App\Actions\JoinEventAction;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ProgrammeController;
use App\Http\Controllers\UserDashBoard\RevokeRsvpAction;
use App\Http\Controllers\UserDashBoard\ShowMyEventsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('homepage');

Route::get('/privacy-policy', function () {
    return view('legal.privacy');
})->name('privacy-policy');

Route::get('/agreement-terms', function(){
    return view('legal.terms');
})->name('terms-of-service');

// Contact-us
Route::get("/contact-us", function(){
    return view("contact.contact");
})->name('contact-us');

Route::get("/events", [ProgrammeController::class, 'index'])->name("events.index");
Route::get('/events/{slug}', [ProgrammeController::class, "show"])->name("events.show");

Route::group([
    "middleware" =>['auth']
], function(){
    Route::post('/events/{slug}/join', JoinEventAction::class)->name("events.join");
    Route::get("/user/events",ShowMyEventsController::class)->name('user.events');
    Route::delete("events/user/{slug}/revoke-rsvp", RevokeRsvpAction::class)->name("user.revoke.event");
});


// Courses
Route::get("/courses", [CourseController::class, "index"])->name("courses.index");

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/instructor.php';
require __DIR__.'/speakers.php';
require __DIR__.'/courses.php';

