<?php

namespace App\Models;

use App\Enums\ApplicationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class SpeakerApplication extends Model
{
    use Notifiable;
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        "session_format" => \App\Enums\SessionFormat::class,
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function speaker()
    {
        return $this->belongsTo(Speaker::class);
    }

    public function speakerApplications()
    {
        return $this->hasMany(SpeakerApplication::class);
    }

    public function isApproved()
    {
        return $this->status == ApplicationStatus::APPROVED->value;
    }

    public function isRejected()
    {
        return $this->status == ApplicationStatus::REJECTED->value;
    }
    public function isPending()
    {
        return $this->status == ApplicationStatus::PENDING->value;
    }
}
