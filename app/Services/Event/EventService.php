<?php

namespace App\Services\Event;

use App\Enums\EventRegistrationStatus;
use App\Enums\EventStatus;
use App\Events\EventRegisterEvent;
use App\Events\Events\EventCreated;
use App\Models\Event;
use App\Models\EventRefundRequest;
use App\Models\EventTransitionAudit;
use App\Models\SpeakerInvite;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\EventRefundRequestedNotification;
use App\Notifications\EventRefundRequestReviewedNotification;
use App\Notifications\SpeakerInvitationNotification;
use App\Enums\Permissions\EventPermissionsEnum;
use App\Services\Speakers\SpeakerApplicationService;
use App\Services\Speakers\SpeakerTransitionService;
use App\Traits\HasFileUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class EventService
{
    use HasFileUpload;
    /**
     * Create a new class instance.
     */

    public function __construct(
        protected SpeakerService $speakerService,
        protected SpeakerApplicationService $speakerApplicationService,
        protected SpeakerTransitionService $speakerTransitionService
        ){}

    public function getPublishedEvents(null|string $q=null)
    {
        $events = Event::publiclyVisible()
            ->when($q, function ($query, $searchQuery) {
                $like = '%' . $searchQuery . '%';

                $query->where(function ($nestedQuery) use ($like) {
                    $nestedQuery->where('title', 'like', $like)
                        ->orWhere('mode', 'like', $like)
                        ->orWhere('theme', 'like', $like)
                        ->orWhere('physical_address', 'like', $like);
                });
            })
            ->orderBy('created_at', 'asc')
            ->paginate()
            ->withQueryString();

        return $events;
    }

    public function fetchFeaturedEvents(){
        return Event::query()
            ->where('is_featured', '=', true)
            ->publiclyVisible()
            ->where('start_date', '>', Carbon::now())
            ->orderByDesc('created_at')
            ->take(3)
            ->get();
    }


    public function getEventsCreatedByUser(string|null $filter = null, bool $includePaymentMetrics = true)
    {
        $user = Auth::user();
        if ($user) {
            if ($user->hasRole('admin') || $user->hasRole('super-admin')) {
                $query = Event::query();
            } else {
                $query = $user->eventsCreated();
            }

            if ($filter === 'past') {
                $query->where('end_date', '<', now());
            } elseif ($filter === 'ongoing') {
                $query->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
            } elseif ($filter === 'future') {
                $query->where('start_date', '>', now());
            } elseif ($filter === 'draft') {
                $query->where('status', EventStatus::DRAFT->value);
            } elseif (in_array($filter, EventStatus::values(), true)) {
                $query->where('status', $filter);
            }

            $withCount = [
                'speakers',
                'attendees',
                'speakerApplications',
            ];

            if ($includePaymentMetrics) {
                $withCount['transactions as successful_transactions_count'] = fn ($transactionQuery) => $transactionQuery->where('status', 'successful');
            }

            return $query
                ->withCount($withCount)
                ->orderBy('start_date', 'desc')
                ->paginate()
                ->withQueryString();
        }
        return collect([]);
    }

    public function registerForEvent(int $eventId, ?int $userId = null): bool
    {
        $userId = $userId ?? Auth::id();

        if (! $userId) {
            return false;
        }

        $event = Event::findOrFail($eventId);

        return $this->setRegistrationStatus($event, $userId, EventRegistrationStatus::REGISTERED) === EventRegistrationStatus::REGISTERED;
    }

    public function registerOrWaitlist(Event $event, ?int $userId = null): EventRegistrationStatus|false
    {
        $userId = $userId ?? Auth::id();

        if (! $userId) {
            return false;
        }

        $targetStatus = $event->slotsRemaining() === 'Full'
            ? EventRegistrationStatus::WAITLISTED
            : EventRegistrationStatus::REGISTERED;

        return $this->setRegistrationStatus($event, $userId, $targetStatus);
    }

    public function getEventsImAttending()
    {
        $user = Auth::user();
        if ($user) {
            $events = $user->events()
                ->wherePivotIn('status', EventRegistrationStatus::workspaceAccessibleValues())
                ->with(['resources', 'transactions' => fn ($query) => $query->where('user_id', $user->id)->latest()])
                ->get();
            return $events;
        }
        return collect([]);
    }

    public function getAttendeeEventWorkspace(string $slug): ?Event
    {
        $user = Auth::user();

        if (! $user) {
            return null;
        }

        return $user->events()
            ->with([
                'resources',
                'speakers.user',
                'transactions' => fn ($query) => $query->where('user_id', $user->id)->latest(),
                'refundRequests' => fn ($query) => $query->where('user_id', $user->id)->latest(),
            ])
            ->where('events.slug', $slug)
            ->wherePivotIn('status', EventRegistrationStatus::workspaceAccessibleValues())
            ->first();
    }

    public function requestRefund(string $slug, ?int $userId = null, ?string $reason = null): string|false
    {
        $userId = $userId ?? Auth::id();

        if (! $userId) {
            return false;
        }

        $event = Event::findBySlug($slug)->firstOrFail();
        $registrationStatus = $event->registrationStatusForUserEnum($userId);

        if (! $registrationStatus || ! in_array($registrationStatus, [
            EventRegistrationStatus::REGISTERED,
            EventRegistrationStatus::WAITLISTED,
        ], true)) {
            return false;
        }

        $existingPendingRequest = EventRefundRequest::query()
            ->where('event_id', $event->id)
            ->where('user_id', $userId)
            ->where('status', 'pending')
            ->first();

        if ($existingPendingRequest) {
            return 'already_pending';
        }

        $transaction = Transaction::query()
            ->where('user_id', $userId)
            ->where('payable_id', $event->id)
            ->where('payable_type', Event::class)
            ->where('status', 'successful')
            ->latest()
            ->first();

        if (! $transaction) {
            return false;
        }

        $refundRequest = EventRefundRequest::query()->create([
            'event_id' => $event->id,
            'user_id' => $userId,
            'transaction_id' => $transaction->id,
            'status' => 'pending',
            'reason' => $reason,
            'requested_at' => now(),
        ]);

        $refundRequest->load(['event', 'user']);

        $event->creator?->notify(new EventRefundRequestedNotification($refundRequest));

        User::query()
            ->whereHas('permissions', fn ($query) => $query->where('name', EventPermissionsEnum::VIEW_ANY->value))
            ->where('id', '!=', $event->creator_id)
            ->get()
            ->each(fn (User $user) => $user->notify(new EventRefundRequestedNotification($refundRequest)));

        $this->recordRegistrationAudit(
            event: $event,
            userId: $userId,
            action: 'refund_requested',
            fromStatus: $registrationStatus,
            toStatus: $registrationStatus,
            actorUserId: $userId,
            context: [
                'refund_request_id' => $refundRequest->id,
            ],
        );

        return 'pending_created';
    }

    public function revokeRsvp(string $slug): bool
    {
        $event = Event::findBySlug($slug)->firstOrFail();
        $userId = Auth::id();

        if (! $userId) {
            return false;
        }

        try {
            return DB::transaction(function () use ($event, $userId) {
                $registration = $event->attendees()->where('user_id', $userId)->first();

                if (! $registration) {
                    return false;
                }

                $currentStatus = EventRegistrationStatus::fromValue($registration->pivot->status);

                if (! $currentStatus?->canCancel()) {
                    return false;
                }

                $releasedSeat = $currentStatus->occupiesSeat();

                $event->attendees()->updateExistingPivot($userId, [
                    'status' => EventRegistrationStatus::CANCELLED->value,
                    'revoke_count' => DB::raw('revoke_count + 1'),
                    'updated_at' => now(),
                ]);

                $this->recordRegistrationAudit(
                    event: $event,
                    userId: $userId,
                    action: 'registration_cancelled',
                    fromStatus: $currentStatus,
                    toStatus: EventRegistrationStatus::CANCELLED,
                    actorUserId: $userId,
                    context: [
                        'revoke_count' => (int) (($registration->pivot->revoke_count ?? 0) + 1),
                    ],
                );

                if ($releasedSeat) {
                    $this->promoteOldestWaitlistedAttendee($event, $userId);
                }

                return true;
            });
        } catch (Throwable $exception) {
            Log::error('Failed to revoke RSVP.', [
                'event_id' => $event->id,
                'user_id' => $userId,
                'exception' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    public function promoteWaitlistedAttendee(Event $event, int $userId): bool|string
    {
        try {
            return DB::transaction(function () use ($event, $userId) {
                $event = Event::query()->findOrFail($event->id);
                $registration = $event->attendees()->where('user_id', $userId)->first();

                if (! $registration) {
                    return 'not_found';
                }

                $currentStatus = EventRegistrationStatus::fromValue($registration->pivot->status);

                if ($currentStatus !== EventRegistrationStatus::WAITLISTED) {
                    return 'invalid_status';
                }

                if ($event->slotsRemaining() === 'Full') {
                    return 'no_capacity';
                }

                return $this->promoteSpecificWaitlistedAttendee(
                    event: $event,
                    userId: $userId,
                    actorUserId: Auth::id(),
                    context: [
                        'trigger' => 'manual_promotion',
                    ],
                ) ? true : false;
            });
        } catch (Throwable $exception) {
            Log::error('Failed to promote waitlisted attendee.', [
                'event_id' => $event->id,
                'user_id' => $userId,
                'exception' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    public function refundRegistration(Event $event, int $userId, ?int $actorUserId = null): bool
    {
        try {
            return DB::transaction(function () use ($event, $userId, $actorUserId) {
                $registration = $event->attendees()->where('user_id', $userId)->first();

                if (! $registration) {
                    return false;
                }

                $currentStatus = EventRegistrationStatus::fromValue($registration->pivot->status);

                if (! $currentStatus?->canTransitionTo(EventRegistrationStatus::REFUNDED)) {
                    return false;
                }

                $releasedSeat = $currentStatus->occupiesSeat();

                $event->attendees()->updateExistingPivot($userId, [
                    'status' => EventRegistrationStatus::REFUNDED->value,
                    'updated_at' => now(),
                ]);

                $this->recordRegistrationAudit(
                    event: $event,
                    userId: $userId,
                    action: 'registration_refunded',
                    fromStatus: $currentStatus,
                    toStatus: EventRegistrationStatus::REFUNDED,
                    actorUserId: $actorUserId,
                );

                if ($releasedSeat) {
                    $this->promoteOldestWaitlistedAttendee($event, $actorUserId);
                }

                return true;
            });
        } catch (Throwable $exception) {
            Log::error('Failed to refund event registration.', [
                'event_id' => $event->id,
                'user_id' => $userId,
                'exception' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    public function approveRefundRequest(EventRefundRequest $refundRequest, ?int $actorUserId = null): bool
    {
        try {
            return DB::transaction(function () use ($refundRequest, $actorUserId) {
                $refundRequest->loadMissing(['event', 'user', 'transaction']);

                if (! $refundRequest->isPending()) {
                    return false;
                }

                if ($refundRequest->transaction) {
                    $refundRequest->transaction->forceFill([
                        'status' => 'refunded',
                        'metadata' => array_merge($refundRequest->transaction->metadata ?? [], [
                            'refund_request_id' => $refundRequest->id,
                            'refund_source' => 'admin_review',
                        ]),
                    ])->save();
                }

                $refunded = $this->refundRegistration($refundRequest->event, $refundRequest->user_id, $actorUserId);

                if (! $refunded) {
                    return false;
                }

                $refundRequest->update([
                    'status' => 'approved',
                    'reviewed_by' => $actorUserId,
                    'reviewed_at' => now(),
                ]);

                $refundRequest->user->notify(new EventRefundRequestReviewedNotification($refundRequest->fresh(['event'])));

                return true;
            });
        } catch (Throwable $exception) {
            Log::error('Failed to approve event refund request.', [
                'refund_request_id' => $refundRequest->id,
                'exception' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    public function declineRefundRequest(EventRefundRequest $refundRequest, ?int $actorUserId = null, ?string $adminNote = null): bool
    {
        try {
            return DB::transaction(function () use ($refundRequest, $actorUserId, $adminNote) {
                $refundRequest->loadMissing(['event', 'user']);

                if (! $refundRequest->isPending()) {
                    return false;
                }

                $refundRequest->update([
                    'status' => 'declined',
                    'reviewed_by' => $actorUserId,
                    'reviewed_at' => now(),
                    'admin_note' => $adminNote,
                ]);

                $currentStatus = $refundRequest->event->registrationStatusForUserEnum($refundRequest->user_id);

                $this->recordRegistrationAudit(
                    event: $refundRequest->event,
                    userId: $refundRequest->user_id,
                    action: 'refund_declined',
                    fromStatus: $currentStatus,
                    toStatus: $currentStatus,
                    actorUserId: $actorUserId,
                    context: [
                        'refund_request_id' => $refundRequest->id,
                    ],
                );

                $refundRequest->user->notify(new EventRefundRequestReviewedNotification($refundRequest->fresh(['event'])));

                return true;
            });
        } catch (Throwable $exception) {
            Log::error('Failed to decline event refund request.', [
                'refund_request_id' => $refundRequest->id,
                'exception' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    protected function promoteOldestWaitlistedAttendee(Event $event, ?int $actorUserId = null): bool
    {
        $nextWaitlistedAttendee = $event->attendees()
            ->wherePivot('status', EventRegistrationStatus::WAITLISTED->value)
            ->orderByPivot('created_at', 'asc')
            ->first();

        if (! $nextWaitlistedAttendee) {
            return false;
        }

        return $this->promoteSpecificWaitlistedAttendee(
            event: $event,
            userId: $nextWaitlistedAttendee->id,
            actorUserId: $actorUserId,
            context: [
                'trigger' => 'seat_released',
            ],
        );
    }

    protected function promoteSpecificWaitlistedAttendee(Event $event, int $userId, ?int $actorUserId = null, array $context = []): bool
    {
        $event->attendees()->updateExistingPivot($userId, [
            'status' => EventRegistrationStatus::REGISTERED->value,
            'updated_at' => now(),
        ]);

        $this->recordRegistrationAudit(
            event: $event,
            userId: $userId,
            action: 'waitlist_promoted',
            fromStatus: EventRegistrationStatus::WAITLISTED,
            toStatus: EventRegistrationStatus::REGISTERED,
            actorUserId: $actorUserId,
            context: $context,
        );

        $user = User::query()->find($userId, ['*']);

        if ($user) {
            event(new EventRegisterEvent($event, $user, 'promoted_from_waitlist'));
        }

        return true;
    }

    protected function setRegistrationStatus(Event $event, int $userId, EventRegistrationStatus $targetStatus): EventRegistrationStatus|false
    {
        $existing = $event->attendees()->where('user_id', $userId)->first();

        if ($existing && $existing->pivot->status === $targetStatus->value) {
            return $targetStatus;
        }

        if ($existing) {
            $currentStatus = EventRegistrationStatus::fromValue($existing->pivot->status);

            if (! $currentStatus) {
                Log::warning("User {$userId} has an unknown registration state for event {$event->id}. Registration denied.");
                return false;
            }

            if (! $currentStatus->canTransitionTo($targetStatus)) {
                Log::warning("User {$userId} attempted invalid registration transition for event {$event->id}.", [
                    'from' => $currentStatus->value,
                    'to' => $targetStatus->value,
                ]);
                return false;
            }

            if ($currentStatus === EventRegistrationStatus::CANCELLED && ($existing->pivot->revoke_count ?? 0) >= 4) {
                Log::warning("User {$userId} attempted to re-register for event {$event->id} but has reached the maximum revoke count ({$existing->pivot->revoke_count}). Registration denied.");
                return false;
            }

            $event->attendees()->updateExistingPivot($userId, [
                'status' => $targetStatus->value,
                'updated_at' => now(),
            ]);

            $this->recordRegistrationAudit(
                event: $event,
                userId: $userId,
                action: $targetStatus === EventRegistrationStatus::WAITLISTED ? 'registration_waitlisted' : 'registration_confirmed',
                fromStatus: $currentStatus,
                toStatus: $targetStatus,
                actorUserId: Auth::id(),
            );

            if (
                $targetStatus === EventRegistrationStatus::REGISTERED
                && $currentStatus !== EventRegistrationStatus::REGISTERED
            ) {
                $user = User::query()->find($userId, ['*']);

                if ($user) {
                    event(new EventRegisterEvent($event, $user, 'confirmed'));
                }
            }

            return $targetStatus;
        }

        $event->attendees()->attach($userId, [
            'status' => $targetStatus->value,
            'revoke_count' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->recordRegistrationAudit(
            event: $event,
            userId: $userId,
            action: $targetStatus === EventRegistrationStatus::WAITLISTED ? 'registration_waitlisted' : 'registration_confirmed',
            fromStatus: null,
            toStatus: $targetStatus,
            actorUserId: Auth::id(),
        );

        if ($targetStatus === EventRegistrationStatus::REGISTERED) {
            $user = User::query()->find($userId, ['*']);

            if ($user) {
                event(new EventRegisterEvent($event, $user, 'confirmed'));
            }
        }

        return $targetStatus;
    }

    protected function recordRegistrationAudit(
        Event $event,
        int $userId,
        string $action,
        ?EventRegistrationStatus $fromStatus,
        ?EventRegistrationStatus $toStatus,
        ?int $actorUserId = null,
        array $context = [],
    ): void {
        EventTransitionAudit::query()->create([
            'event_id' => $event->id,
            'user_id' => $userId,
            'actor_user_id' => $actorUserId,
            'action' => $action,
            'from_status' => $fromStatus?->value,
            'to_status' => $toStatus?->value,
            'context' => $context === [] ? null : $context,
        ]);
    }

    public function createEvent(array $validated, UploadedFile|null $program_cover = null)
    {

        try {
            DB::beginTransaction();
            $filepath = null;

            $validated['slug'] = (string) Str::uuid();

            if($program_cover){
                $filepath = $this->uploadFile($program_cover, 'program_covers');
                $validated['program_cover'] = $filepath;
            }

            $validated['status'] = $validated['status'] ?? EventStatus::DRAFT->value;

            $event = Event::create($validated);
            DB::commit();
            event(new EventCreated($event));

            return $event;
        } catch (\Exception $e) {
            DB::rollback();
            if (!empty($filepath) && Storage::disk('public')->exists($filepath)) {
                Storage::disk('public')->delete($filepath);
            }
            Log::error('Event creation failed: ' . $e->getMessage());
            return null;
        }
    }

    protected function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = Event::query()
            ->where('slug', 'like', "{$slug}%", 'and')
            ->count('id');
        return $count ? "{$slug}-{$count}" : $slug;
    }

    public function updateEvent(array $validated, Event $event, UploadedFile|null $program_cover = null)
    {
        DB::beginTransaction();
        try {
            $file_path = $event->program_cover;

            if ($program_cover) {
                $new_file_path = $this->uploadFile($program_cover, 'program_covers');
                if($new_file_path){
                    $validated['program_cover'] = $new_file_path;
                    if(!empty($file_path)){
                        $this->removeFile($file_path);
                    }
                }
            } else {
                $validated['program_cover'] = $file_path;
            }

            $event->fill($validated);
            $event->save();
            DB::commit();
            return $event->fresh();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Event update failed: ' . $e->getMessage());
            return null;
        }
    }

    protected function removeFile(string|null $file_path, string $type = 'public')
    {
        if (!empty($file_path) && Storage::disk($type)->exists($file_path)) {
            Storage::disk($type)->delete($file_path);
        }
    }

    public function deleteEvent(Event $event)
    {
        try {
            $file_path = $event->program_cover;
            $result = DB::transaction(function () use ($event) {
                return $event->deleteOrFail();
            });
            $this->removeFile($file_path);
            return $result;

        } catch (\Exception $e) {
            Log::error('Event deletion failed: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteMany(array $eventIds)
    {
        try {
            // Get all events with their program_cover paths before deletion
            $events = Event::query()
                ->whereIn('id', $eventIds, 'and', false)
                ->get(['id', 'program_cover']);

            $filePaths = $events->pluck('program_cover')->filter()->toArray();

            $result = DB::transaction(function () use ($eventIds) {
                return Event::query()
                    ->whereIn('id', $eventIds, 'and', false)
                    ->delete();
            });

            // Delete all associated files after successful database deletion
            foreach ($filePaths as $filePath) {
                $this->removeFile($filePath);
            }

            return $result;

        } catch (\Exception $e) {
            Log::error('Mass event deletion failed: ' . $e->getMessage());
            return false;
        }
    }

    public function inviteSpeakerToEvent(Event $event, array $data): bool|string
    {
        $speaker = $this->speakerService->findOneSpeaker($data['speaker_id']);
        if ($this->speakerService->speakerAlreadyInvited($event, $speaker)) {
            return "already_invited";
        }
        if($this->speakerService->speakerHasAplication($event, $speaker)) {
            $existingApplication = $this->speakerService->findExistingSpeakerApplication($event, $speaker);
            if ($existingApplication) {
                $this->speakerTransitionService->approveApplication($existingApplication);
                return "speaker_approved";
            }
        }

        try {
            $data['expires_at'] = Carbon::now()->addDay();
            DB::transaction(function () use ($data) {
                $invitation = SpeakerInvite::create($data);

                $invitation->speaker->user->notify(new SpeakerInvitationNotification($invitation));
            });
            return true;
        } catch (Throwable $e) {
            Log::error('Speaker invitation failed: ', [
                'exception' => $e->getMessage(),
            ]);
            return false;
        }
    }


}
