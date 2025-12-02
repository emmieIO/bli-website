<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject',
        'status',
        'priority',
        'reference_code',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            $ticket->reference_code = self::generateReferenceCode();
        });
    }

    protected static function generateReferenceCode(): string
    {
        $prefix = 'TKT';
        $date = now()->format('Ymd');

        // Query existing reference codes for today to find the highest sequence
        // Use lockForUpdate to prevent race conditions when creating tickets simultaneously
        $lastTicket = self::where('reference_code', 'like', "{$prefix}-{$date}-%")
            ->orderByRaw('CAST(SUBSTRING(reference_code, -3) AS UNSIGNED) DESC')
            ->lockForUpdate()
            ->first();

        $sequence = $lastTicket ? (intval(substr($lastTicket->reference_code, -3)) + 1) : 1;

        return sprintf('%s-%s-%03d', $prefix, $date, $sequence);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'ticket_replies');
    }
}
