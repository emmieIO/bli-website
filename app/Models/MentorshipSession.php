<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MentorshipSession extends Model
{
    protected $fillable = [
        'mentorship_request_id',
        'session_date',
        'duration',
        'notes',
        'topics_covered',
        'recording_link',
        'completed_at',
    ];

    protected $casts = [
        'session_date' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function mentorshipRequest(): BelongsTo
    {
        return $this->belongsTo(MentorshipRequest::class);
    }

    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    public function markAsCompleted(): void
    {
        $this->update(['completed_at' => now()]);
    }
}
