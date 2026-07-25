<?php

namespace App\Models;

use App\Enums\EventRegistrationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class EventGuestAttendee extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'event_id',
        'email',
        'name',
        'status',
        'access_code_hash',
        'access_code_expires_at',
    ];

    protected $casts = [
        'status' => EventRegistrationStatus::class,
        'access_code_expires_at' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function routeNotificationForMail(): string
    {
        return $this->email;
    }
}
