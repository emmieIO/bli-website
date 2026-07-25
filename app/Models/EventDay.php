<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'position',
        'title',
        'theme',
        'start_at',
        'end_at',
        'mode',
        'venue_name',
        'physical_address',
        'meeting_link',
        'notes',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
