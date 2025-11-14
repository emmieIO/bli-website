<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Certificate extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'certificate_number',
        'completion_date',
        'certificate_url',
        'certificate_data'
    ];

    protected $casts = [
        'completion_date' => 'datetime',
        'certificate_data' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($certificate) {
            $certificate->certificate_number = 'CERT-' . strtoupper(Str::random(10));
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function getVerificationUrlAttribute(): string
    {
        return route('certificates.verify', $this->certificate_number);
    }
}
