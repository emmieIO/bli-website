<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProgrammeController;
use App\Http\Controllers\SpeakerUserController;
use App\Http\Controllers\Instructors\InstructorsController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\NewsletterController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Public pages
Route::get('/', [HomeController::class, 'index'])->name('homepage');
Route::get('/privacy', fn() => Inertia::render('Legal/Privacy'))->name('privacy-policy');
Route::get('/agreement-terms', fn() => Inertia::render('Legal/Terms'))->name('terms-of-service');
Route::get('/contact', fn() => Inertia::render('Contact'))->name('contact-us');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Newsletter
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::post('/newsletter/unsubscribe', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

// Public events browsing
Route::get('/events', [ProgrammeController::class, 'index'])->name('events.index');
Route::get('/events/{slug}/show', [ProgrammeController::class, 'show'])->name('events.show');

// Public speaker/instructor application entry points
Route::get('/become-a-speaker', [SpeakerUserController::class, 'index'])->name('become-a-speaker');
Route::post('/become-a-speaker', [SpeakerUserController::class, 'store'])->name('become-a-speaker.store');
Route::get('/instructors/start-application', [InstructorsController::class, 'registerInstructor'])->name('instructors.become-an-instructor');

// Instructor ratings
Route::middleware('auth')->group(function () {
    Route::post('/instructors/{instructor}/rate', [\App\Http\Controllers\InstructorRatingController::class, 'store'])->name('instructors.rate');
});
Route::get('/instructors/{instructor}/ratings', [\App\Http\Controllers\InstructorRatingController::class, 'index'])->name('instructors.ratings');

// Load organized route files
require __DIR__ . '/auth.php';
require __DIR__ . '/user.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/instructor.php';
require __DIR__ . '/speakers.php';
require __DIR__ . '/courses.php';
require __DIR__ . '/certificates.php';

