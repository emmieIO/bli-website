<?php

use App\Actions\JoinEventAction;
use App\Http\Controllers\Events\EventCalenderController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SpeakerApplicationController;
use App\Http\Controllers\SpeakerInvitationController;
use App\Http\Controllers\UserDashBoard\DashboardController;
use App\Http\Controllers\UserDashBoard\RevokeRsvpAction;
use App\Http\Controllers\UserDashBoard\ShowMyEventsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    // Main dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('user_dashboard');

    // Global search
    Route::get('/search', [SearchController::class, 'search'])->name('search');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // User's events management
    Route::get('/user/events', ShowMyEventsController::class)->name('user.events');
    Route::post('/events/{slug}/join', JoinEventAction::class)->name('events.join');
    Route::delete('/events/user/{slug}/revoke-rsvp', RevokeRsvpAction::class)->name('user.revoke.event');

    // Event utilities
    Route::get('/events/{event}/calendar', [EventCalenderController::class, 'download'])->name('events.calendar');

    // Speaker invitations and applications
    Route::get('/events/invitations', [SpeakerInvitationController::class, 'index'])->name('invitations.index');
    Route::get('/events/invitations/{invite}/show', [SpeakerInvitationController::class, 'show'])
        ->name('invitations.show')
        ->middleware('signed');

    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Signed route group for speaker applications (auth not required for some)
Route::middleware('signed')->group(function () {
    Route::get('/events/{event}/apply-to-speak', [SpeakerApplicationController::class, 'apply'])
        ->name('event.speakers.apply');
    Route::post('/events/{event}/apply-to-speak', [SpeakerApplicationController::class, 'store'])
        ->name('event.speakers.store');
    Route::get('/events/{event}/invitations/{invite}/respond', [SpeakerApplicationController::class, 'inviteRespondView'])
        ->name('invitations.respond');
    Route::post('/events/{event}/invitations/{invite}/respond', [SpeakerInvitationController::class, 'acceptInvitation'])
        ->name('invitations.accept');
});
