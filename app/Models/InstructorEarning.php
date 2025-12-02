<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstructorEarning extends Model
{
    protected $fillable = [
        'instructor_id',
        'transaction_id',
        'course_id',
        'gross_amount',
        'platform_fee',
        'net_amount',
        'platform_fee_percentage',
        'currency',
        'status',
        'available_at',
        'paid_at',
        'payout_id',
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'platform_fee_percentage' => 'decimal:2',
        'available_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the instructor that owns this earning
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    /**
     * Get the transaction that generated this earning
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Get the course that was sold
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the payout this earning is part of
     */
    public function payout(): BelongsTo
    {
        return $this->belongsTo(InstructorPayout::class, 'payout_id');
    }

    /**
     * Check if earning is available for payout
     */
    public function isAvailable(): bool
    {
        return $this->status === 'available' &&
               $this->available_at !== null &&
               $this->available_at->isPast();
    }

    /**
     * Mark earning as paid
     */
    public function markAsPaid(InstructorPayout $payout): void
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payout_id' => $payout->id,
        ]);
    }

    /**
     * Mark earning as available for withdrawal
     */
    public function markAsAvailable(?int $holdingPeriodDays = 7): void
    {
        $this->update([
            'status' => 'available',
            'available_at' => now()->addDays($holdingPeriodDays),
        ]);
    }
}
