<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class InstructorPayout extends Model
{
    // Only allow fields that instructors/admins should be able to set
    protected $fillable = [
        'instructor_id',
        'amount',
        'currency',
        'payout_method',
        'bank_name',
        'account_number',
        'account_name',
        'bank_code',
        'payout_email',
        'payout_details',
    ];

    // Protect sensitive fields from mass assignment
    protected $guarded = [
        'id',
        'payout_reference',  // Auto-generated
        'status',            // Controlled by workflow methods
        'notes',             // Admin only
        'external_reference', // Admin only
        'failure_reason',    // Admin only
        'requested_at',      // Auto-set on creation
        'processed_at',      // Auto-set by markAsProcessing()
        'completed_at',      // Auto-set by markAsCompleted()
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payout_details' => 'array',
        'requested_at' => 'datetime',
        'processed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payout) {
            if (empty($payout->payout_reference)) {
                // Generate cryptographically secure reference
                // Format: PO_<16 random chars>_<timestamp>
                $payout->payout_reference = 'PO_' . strtoupper(Str::random(16)) . '_' . time();
            }
            if (empty($payout->requested_at)) {
                $payout->requested_at = now();
            }
        });
    }

    /**
     * Get the instructor that owns this payout
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    /**
     * Get the earnings included in this payout
     */
    public function earnings(): HasMany
    {
        return $this->hasMany(InstructorEarning::class, 'payout_id');
    }

    /**
     * Check if payout is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if payout is processing
     */
    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    /**
     * Check if payout is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Mark payout as processing
     */
    public function markAsProcessing(): void
    {
        $this->update([
            'status' => 'processing',
            'processed_at' => now(),
        ]);
    }

    /**
     * Mark payout as completed
     */
    public function markAsCompleted(?string $externalReference = null): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'external_reference' => $externalReference,
        ]);
    }

    /**
     * Mark payout as failed
     */
    public function markAsFailed(string $reason): void
    {
        $this->update([
            'status' => 'failed',
            'failure_reason' => $reason,
        ]);
    }

    /**
     * Cancel payout
     */
    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }
}
