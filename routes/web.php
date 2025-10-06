<?php

use App\Actions\JoinEventAction;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\Events\EventCalenderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProgrammeController;
use App\Http\Controllers\SpeakerInvitationController;
use App\Http\Controllers\UserDashBoard\RevokeRsvpAction;
use App\Http\Controllers\UserDashBoard\ShowMyEventsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('homepage');

Route::get('/privacy', function () {
    return view('legal.privacy');
})->name('privacy-policy');

Route::get('/agreement-terms', function () {
    return view('legal.terms');
})->name('terms-of-service');

// Contact-us
Route::get("/contact", function () {
    return view("contact.contact");
})->name('contact-us');

Route::get("/events", [ProgrammeController::class, 'index'])->name("events.index");
Route::get('/events/{slug}/show', [ProgrammeController::class, "show"])->name("events.show");

Route::group([
    "middleware" => ['auth']
], function () {
    Route::get("/user/events", ShowMyEventsController::class)->name('user.events');
    Route::post('/events/{slug}/join', JoinEventAction::class)->name("events.join");
    Route::delete("events/user/{slug}/revoke-rsvp", RevokeRsvpAction::class)->name("user.revoke.event");
    Route::get('/events/{event}/calendar', [EventCalenderController::class, 'download'])->name('events.calendar');
    Route::get('/events/invitations', [SpeakerInvitationController::class, 'index'])->name('invitations.index');
    Route::get('/events/invitations/{invite}/show', [SpeakerInvitationController::class, 'show'])
        ->name('invitations.show')
        ->middleware('signed');

});


// Courses

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/instructor.php';
require __DIR__ . '/speakers.php';
require __DIR__ . '/courses.php';

