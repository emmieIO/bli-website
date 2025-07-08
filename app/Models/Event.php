<?php

namespace App\Models;

use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory, HasUUID;
    protected $guarded = [];

    protected $casts = [
        'metadata' => 'array',
    ];
    public function attendees(){
        return $this->belongsToMany(User::class, "event_attendees", "event_id", "user_id")->withTimestamps();
    }

    public function scopeFindBySlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    
}
