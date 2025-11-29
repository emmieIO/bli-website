<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MentorshipMilestone extends Model
{
    protected $fillable = [
        'mentorship_request_id',
        'title',
        'description',
        'due_date',
        'order',
        'completed_at',
        'completed_by',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    public function mentorshipRequest(): BelongsTo
    {
        return $this->belongsTo(MentorshipRequest::class);
    }

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    public function markAsCompleted(User $user): void
    {
        $this->update([
            'completed_at' => now(),
            'completed_by' => $user->id,
        ]);
    }

    public function isOverdue(): bool
    {
        if (!$this->due_date || $this->isCompleted()) {
            return false;
        }

        return $this->due_date < now()->startOfDay();
    }
}
