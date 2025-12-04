<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProgrammeController;
use App\Http\Controllers\SpeakerUserController;
use App\Http\Controllers\Instructors\InstructorsController;
use App\Http\Controllers\ContactController;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Public pages
Route::get('/', [HomeController::class, 'index'])->name('homepage');
Route::get('/privacy', fn() => Inertia::render('Legal/Privacy'))->name('privacy-policy');
Route::get('/agreement-terms', fn() => Inertia::render('Legal/Terms'))->name('terms-of-service');
Route::get('/contact', fn() => Inertia::render('Contact'))->name('contact-us');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Public events browsing
Route::get('/events', [ProgrammeController::class, 'index'])->name('events.index');
Route::get('/events/{slug}/show', [ProgrammeController::class, 'show'])->name('events.show');

// Public blog
Route::get('/blog', [\App\Http\Controllers\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [\App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');

// Public speaker/instructor application entry points
Route::get('/become-a-speaker', [SpeakerUserController::class, 'index'])->name('become-a-speaker');
Route::post('/become-a-speaker', [SpeakerUserController::class, 'store'])->name('become-a-speaker.store');
Route::get('/instructors/start-application', [InstructorsController::class, 'registerInstructor'])->name('instructors.become-an-instructor');

// Instructor ratings
Route::middleware('auth')->group(function () {
    Route::post('/instructors/{instructor}/rate', [\App\Http\Controllers\InstructorRatingController::class, 'store'])->name('instructors.rate');
});
Route::get('/instructors/{instructor}/ratings', [\App\Http\Controllers\InstructorRatingController::class, 'index'])->name('instructors.ratings');

// Shopping cart (public website)
Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::middleware('auth')->group(function () {
    Route::post('/cart/add/{course}', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{course}', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [\App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/checkout', [\App\Http\Controllers\CartController::class, 'checkout'])->name('cart.checkout');
    Route::get('/api/cart/count', [\App\Http\Controllers\CartController::class, 'count'])->name('cart.count');
});

Route::get('process-queue', function (Illuminate\Http\Request $request) {
    // Validate token for security
    if ($request->query('token') !== config('queue.token')) {
        abort(403, 'Unauthorized');
    }

    Artisan::call('queue:work', [
        '--stop-when-empty' => true,
        '--max-time' => 3600,
    ]);
    return 'Queue Processed.';
})->middleware('throttle:5,1');

// Load organized route files
Route::middleware('web')->group(function () {
    require __DIR__ . '/auth.php';
    require __DIR__ . '/user.php';
    require __DIR__ . '/admin.php';
    require __DIR__ . '/instructor.php';
    require __DIR__ . '/speakers.php';
    require __DIR__ . '/courses.php';
    require __DIR__ . '/certificates.php';
    require __DIR__ . '/payments.php';
    require __DIR__ . '/mentorship.php';
    require __DIR__ . '/tickets.php';
});


