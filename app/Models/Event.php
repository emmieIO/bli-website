<?php

namespace App\Models;

use App\Enums\EventStatus;
use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    use HasFactory, HasUUID;

    /**
     * Get the route key for the model.
     * Use 'slug' for route binding to support SEO-friendly URLs.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected $fillable = [
        'uuid',
        'slug',
        'title',
        'theme',
        'description',
        'program_cover',
        'mode',
        'location',
        'attendee_slots',
        'physical_address',
        'venue',
        'contact_email',
        'start_date',
        'end_date',
        'creator_id',
        'status',
        'is_active',
        'is_published',
        'is_allowing_application',
        'is_featured',
        'entry_fee',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'status' => EventStatus::class,
        'is_active' => 'boolean',
        'is_published' => 'boolean',
        'is_allowing_application' => 'boolean',
        'is_featured' => 'boolean',
    ];

    protected static function booted()
    {
        static::saving(function (Event $event) {
            $status = $event->lifecycleStatus();

            $event->status = $status->value;
            $event->is_published = $status->usesPublishedFlag();
            $event->is_active = $status->isActive();
        });

        static::deleting(function ($event) {
            foreach ($event->resources as $resource) {
                if ($resource->file_path && Storage::disk('public')->exists($resource->file_path)) {
                    Storage::disk('public')->delete($resource->file_path);
                }

                $resource->delete();
            }
            $event->speakers()->detach();
        });
    }

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'event_attendees', 'event_id', 'user_id')
            ->withPivot(['status', 'revoke_count'])
            ->withTimestamps();
    }

    public function scopeFindBySlug(Builder $query, string $slug): Builder
    {
        return $query->where('slug', $slug);
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('start_date', '>', Carbon::now())
            ->publiclyVisible();
    }

    public function scopeOngoing(Builder $query): Builder
    {
        return $query->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->publiclyVisible();

    }

    public function scopeEnded(Builder $query): Builder
    {
        return $query->where('end_date', '<', Carbon::now())
            ->publiclyVisible();

    }

    public function scopePubliclyVisible(Builder $query): Builder
    {
        return $query->whereIn('status', EventStatus::publiclyVisibleValues());
    }

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function speakers()
    {
        return $this->belongsToMany(Speaker::class, 'event_speaker');
    }

    public function attendeesCount()
    {
        return $this->attendees()->count();
    }

    public function resources()
    {
        return $this->hasMany(EventResource::class);
    }

    public function refundRequests()
    {
        return $this->hasMany(EventRefundRequest::class);
    }

    public function recentRegistrations()
    {
        return $this->attendees()
            ->withPivot('created_at')
            ->orderByPivot('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    public function speakerApplications()
    {
        return $this->hasMany(SpeakerApplication::class, 'event_id');
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'payable');
    }

    public function isPubliclyVisible(): bool
    {
        return $this->lifecycleStatus()->isPubliclyVisible();
    }

    public function isRegistrationOpen(): bool
    {
        return $this->lifecycleStatus()->allowsRegistration() && (!$this->end_date || now()->lt($this->end_date));
    }

    public function lifecycleStatus(): EventStatus
    {
        if ($this->status instanceof EventStatus) {
            return $this->status;
        }

        if (is_string($this->status) && $this->status !== '') {
            return EventStatus::from($this->status);
        }

        return EventStatus::fromLegacyFlags(
            (bool) $this->is_published,
            (bool) $this->is_active
        );
    }

}
