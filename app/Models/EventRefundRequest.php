<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventRefundRequest extends Model
{
    protected $fillable = [
        'event_id',
        'user_id',
        'transaction_id',
        'reviewed_by',
        'status',
        'reason',
        'admin_note',
        'requested_at',
        'reviewed_at',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}
