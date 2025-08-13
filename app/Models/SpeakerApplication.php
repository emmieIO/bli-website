<?php

namespace App\Models;

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

    public function speaker(){
        return $this->belongsTo(Speaker::class);
    }

    public function speakerApplications(){
        return $this->hasMany(SpeakerApplication::class);
    }
}
