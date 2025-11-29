<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MentorshipRequest extends Model
{
    protected $fillable = [
        'student_id',
        'instructor_id',
        'message',
        'goals',
        'duration_type',
        'duration_value',
        'status',
        'instructor_response',
        'instructor_approved_at',
        'instructor_approved_by',
        'admin_response',
        'admin_approved_at',
        'admin_approved_by',
        'rejection_reason',
        'rejected_at',
        'rejected_by',
        'started_at',
        'ended_at',
        'meeting_link',
        'meeting_schedule',
        'next_session_at',
    ];

    protected $casts = [
        'instructor_approved_at' => 'datetime',
        'admin_approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'meeting_schedule' => 'array',
        'next_session_at' => 'datetime',
    ];

    protected $appends = [
        'formatted_duration',
        'status_label',
        'status_color',
        'expiration_date',
        'days_remaining',
        'expiration_status',
    ];

    /**
     * Get the student who made the request
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the instructor being requested
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    /**
     * Get the user who approved at instructor level
     */
    public function instructorApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_approved_by');
    }

    /**
     * Get the admin who gave final approval
     */
    public function adminApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_approved_by');
    }

    /**
     * Get the user who rejected the request
     */
    public function rejector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    /**
     * Get the sessions for this mentorship
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(MentorshipSession::class);
    }

    /**
     * Get the resources for this mentorship
     */
    public function resources(): HasMany
    {
        return $this->hasMany(MentorshipResource::class);
    }

    /**
     * Get the milestones for this mentorship
     */
    public function milestones(): HasMany
    {
        return $this->hasMany(MentorshipMilestone::class)->orderBy('order');
    }

    /**
     * Status helper methods
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isInstructorApproved(): bool
    {
        return $this->status === 'instructor_approved';
    }

    public function isAdminApproved(): bool
    {
        return $this->status === 'admin_approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isActive(): bool
    {
        return $this->isAdminApproved() && $this->started_at !== null && $this->ended_at === null;
    }

    /**
     * Scope for filtering by status
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for active mentorships
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'admin_approved')
            ->whereNotNull('started_at')
            ->whereNull('ended_at');
    }

    /**
     * Scope for student's requests
     */
    public function scopeForStudent($query, int $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    /**
     * Scope for instructor's requests
     */
    public function scopeForInstructor($query, int $instructorId)
    {
        return $query->where('instructor_id', $instructorId);
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDurationAttribute(): string
    {
        return $this->duration_value . ' ' . $this->duration_type;
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'instructor_approved' => 'blue',
            'admin_approved' => 'green',
            'rejected' => 'red',
            'cancelled' => 'gray',
            default => 'gray',
        };
    }

    /**
     * Get human-readable status
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pending Instructor Approval',
            'instructor_approved' => 'Pending Admin Approval',
            'admin_approved' => 'Approved - Active',
            'rejected' => 'Rejected',
            'cancelled' => 'Cancelled',
            'ended' => 'Ended',
            default => 'Unknown',
        };
    }

    /**
     * Calculate the expiration date based on started_at and duration
     */
    public function getExpirationDateAttribute(): ?\Carbon\Carbon
    {
        if (!$this->started_at || !$this->isActive()) {
            return null;
        }

        return match($this->duration_type) {
            'week' => $this->started_at->addWeeks($this->duration_value),
            'month' => $this->started_at->addMonths($this->duration_value),
            'year' => $this->started_at->addYears($this->duration_value),
            default => null,
        };
    }

    /**
     * Get days remaining until expiration
     */
    public function getDaysRemainingAttribute(): ?int
    {
        $expirationDate = $this->expiration_date;

        if (!$expirationDate) {
            return null;
        }

        return now()->diffInDays($expirationDate, false);
    }

    /**
     * Check if mentorship has expired
     */
    public function isExpired(): bool
    {
        $expirationDate = $this->expiration_date;

        if (!$expirationDate) {
            return false;
        }

        return now()->isAfter($expirationDate);
    }

    /**
     * Check if mentorship is expiring soon (within 7 days)
     */
    public function isExpiringSoon(): bool
    {
        $daysRemaining = $this->days_remaining;

        if ($daysRemaining === null) {
            return false;
        }

        return $daysRemaining >= 0 && $daysRemaining <= 7;
    }

    /**
     * Get expiration status for display
     */
    public function getExpirationStatusAttribute(): ?array
    {
        if (!$this->isActive()) {
            return null;
        }

        $daysRemaining = $this->days_remaining;

        if ($daysRemaining === null) {
            return null;
        }

        if ($daysRemaining < 0) {
            return [
                'status' => 'expired',
                'message' => 'Expired ' . abs($daysRemaining) . ' day(s) ago',
                'color' => 'red',
            ];
        }

        if ($daysRemaining === 0) {
            return [
                'status' => 'expires_today',
                'message' => 'Expires today',
                'color' => 'red',
            ];
        }

        if ($daysRemaining <= 7) {
            return [
                'status' => 'expiring_soon',
                'message' => 'Expires in ' . $daysRemaining . ' day(s)',
                'color' => 'orange',
            ];
        }

        return [
            'status' => 'active',
            'message' => 'Expires in ' . $daysRemaining . ' day(s)',
            'color' => 'green',
        ];
    }

    /**
     * Scope for expired mentorships
     */
    public function scopeExpired($query)
    {
        return $query->active()
            ->where(function ($q) {
                $q->whereRaw("
                    CASE
                        WHEN duration_type = 'week' THEN DATE_ADD(started_at, INTERVAL duration_value WEEK)
                        WHEN duration_type = 'month' THEN DATE_ADD(started_at, INTERVAL duration_value MONTH)
                        WHEN duration_type = 'year' THEN DATE_ADD(started_at, INTERVAL duration_value YEAR)
                    END < NOW()
                ");
            });
    }

    /**
     * Scope for expiring soon (within 7 days)
     */
    public function scopeExpiringSoon($query)
    {
        return $query->active()
            ->where(function ($q) {
                $q->whereRaw("
                    CASE
                        WHEN duration_type = 'week' THEN DATE_ADD(started_at, INTERVAL duration_value WEEK)
                        WHEN duration_type = 'month' THEN DATE_ADD(started_at, INTERVAL duration_value MONTH)
                        WHEN duration_type = 'year' THEN DATE_ADD(started_at, INTERVAL duration_value YEAR)
                    END BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY)
                ");
            });
    }
}
