<?php

namespace App\Models;

use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model {
    use HasFactory, HasUUID;
    protected $guarded = [];

    protected $casts = [
        'metadata' => 'array',
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

    public function attendees() {
        return $this->belongsToMany( User::class, 'event_attendees', 'event_id', 'user_id' )->withTimestamps();
    }

    public function scopeFindBySlug( $query, $slug ) {
        return $query->where( 'slug', $slug );
    }

    public function creator() {
        return $this->belongsTo( User::class );
    }

    public function speakers() {
        return $this->belongsToMany( Speaker::class, 'event_speaker' );
    }

    public function attendeesCount() {
        return $this->attendees()->count();
    }

    public function resources() {
        return $this->hasMany( EventResource::class );
    }

    public function recentRegistrations() {
        return $this->attendees()
        ->withPivot( 'created_at' )
        ->orderByPivot( 'created_at', 'desc' )
        ->limit( 5 )
        ->get();
    }

}
