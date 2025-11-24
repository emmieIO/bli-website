<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpeakerInvite extends Model
{
    protected $fillable = [
        'event_id',
        'speaker_id',
        'email',
        'suggested_topic',
        'suggested_duration',
        'audience_expectations',
        'expected_format',
        'special_instructions',
        'status',
        'user_feedback',
        'admin_feedback',
        'sent_at',
        'responded_at',
        'expires_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'responded_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function speaker(){
        return $this->belongsTo(Speaker::class);
    }

}
