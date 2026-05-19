<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventTransitionAudit extends Model
{
    protected $fillable = [
        'event_id',
        'user_id',
        'actor_user_id',
        'action',
        'from_status',
        'to_status',
        'context',
    ];

    protected $casts = [
        'context' => 'array',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }
}
