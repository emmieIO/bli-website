<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MentorshipResource extends Model
{
    protected $fillable = [
        'mentorship_request_id',
        'uploaded_by',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'description',
        'category',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    public function mentorshipRequest(): BelongsTo
    {
        return $this->belongsTo(MentorshipRequest::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getFileSizeHumanAttribute(): string
    {
        if (!$this->file_size) {
            return 'Unknown';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unitIndex = 0;

        while ($size >= 1024 && $unitIndex < \count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }

        return round($size, 2) . ' ' . $units[$unitIndex];
    }
}
