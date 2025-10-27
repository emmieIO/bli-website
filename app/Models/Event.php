<?php

namespace App\Models;

use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Event extends Model
{
    use HasFactory, HasUUID;
    protected $guarded = [];
    protected $casts = [
        'metadata' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];

    protected static function booted()
    {
        static::deleting(function ($event) {
            foreach ($event->resources as $resource) {
                if ($resource->file_path && \Storage::disk('public')->exists($resource->file_path)) {
                    \Storage::disk('public')->delete($resource->file_path);
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

    public function scopeFindBySlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('start_date', '>', Carbon::now())
        ->where('is_published',true);
    }

    public function scopeOngoing(Builder $query): Builder
    {
        return $query->where('start_date', '<=', Carbon::now())
        ->where('end_date', '>=', Carbon::now())
        ->where('is_published',true);

    }

    public function scopeEnded(Builder $query): Builder
    {
        return $query->where('end_date', '<', Carbon::now())
        ->where('is_published',true);

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

    public function slotsRemaining()
    {
        if($this->attendee_slots == null) return 'Unlimited';
        if($this->attendee_slots == 0) return 'Full';
        return $this->attendee_slots - $this->attendees()->count();
    }


    public function resources()
    {
        return $this->hasMany(EventResource::class);
    }

    public function recentRegistrations()
    {
        return $this->attendees()
            ->withPivot('created_at')
            ->orderByPivot('created_at', 'desc')
            ->limit(5)
            ->get();
    }
    public function isCanceled(): bool
    {
        $attendee = $this->attendees()
            ->where('user_id', auth()->id())
            ->first();

        if (!$attendee || !isset($attendee->pivot)) {
            return false;
        }

        return $attendee->pivot->status === 'cancelled'; // make sure spelling matches your DB
    }

    public function isRegistered()
    {
        $attendee = $this->attendees()
            ->where('user_id', auth()->id())
            ->first();

        if (!$attendee || !isset($attendee->pivot)) {
            return false;
        }

        return $attendee->pivot->status === 'registered'; // make sure spelling matches your DB
    }

    public function getRevokeCount()
    {
        $attendee = $this->attendees()
            ->where('user_id', auth()->id())
            ->first();

        if (!$attendee || !isset($attendee->pivot)) {
            return false;
        }

        return $attendee->pivot->revoke_count;
    }

    public function maxRevokes()
    {
        if ($this->getRevokeCount() >= 4) {
            return true;
        }
        return false;
    }

    public function speakerApplications()
    {
        return $this->hasMany(SpeakerApplication::class, 'event_id');
    }


}
