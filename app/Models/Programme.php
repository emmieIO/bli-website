<?php

namespace App\Models;

use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    use HasFactory, HasUUID;
    protected $guarded = [];

    public function attendees(){
        return $this->belongsToMany(User::class, "event_user", "event_id", "user_id")->withTimestamps();
    }

    public function scopeFindBySlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }
}
