<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProgrammeController;
use App\Http\Controllers\SpeakerUserController;
use App\Http\Controllers\Instructors\InstructorsController;
use Illuminate\Support\Facades\Route;

// Public pages
Route::get('/', [HomeController::class, 'index'])->name('homepage');
Route::get('/privacy', fn() => view('legal.privacy'))->name('privacy-policy');
Route::get('/agreement-terms', fn() => view('legal.terms'))->name('terms-of-service');
Route::get('/contact', fn() => view('contact.contact'))->name('contact-us');

// Public events browsing
Route::get('/events', [ProgrammeController::class, 'index'])->name('events.index');
Route::get('/events/{slug}/show', [ProgrammeController::class, 'show'])->name('events.show');

// Public speaker/instructor application entry points
Route::get('/become-a-speaker', [SpeakerUserController::class, 'index'])->name('become-a-speaker');
Route::post('/become-a-speaker', [SpeakerUserController::class, 'store'])->name('become-a-speaker.store');
Route::get('/instructors/start-application', [InstructorsController::class, 'registerInstructor'])->name('instructors.become-an-instructor');

// Load organized route files
require __DIR__ . '/auth.php';
require __DIR__ . '/user.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/instructor.php';
require __DIR__ . '/speakers.php';
require __DIR__ . '/courses.php';
require __DIR__ . '/certificates.php';

