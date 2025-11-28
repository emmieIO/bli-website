<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Lesson extends Model
{
    protected $fillable = [
        'module_id',
        'title',
        'slug',
        'type',
        'description',
        'vimeo_id',
        'preview_vimeo_id',
        'is_preview',
        'content_path',
        'assignment_instructions',
        'order',
        'video_status',
        'video_error',
        'video_uploaded_at',
    ];

    protected $casts = [
        'video_uploaded_at' => 'datetime',
    ];

    protected $appends = [
        'video_url',
    ];

    /**
     * Get the video URL for Vimeo videos
     */
    public function getVideoUrlAttribute(): ?string
    {
        if ($this->type === 'video' && $this->vimeo_id) {
            // Return Vimeo player URL for iframe embedding
            return "https://player.vimeo.com/video/{$this->vimeo_id}";
        }

        // For non-Vimeo videos or other types, return content_path
        if ($this->type === 'video' && $this->content_path) {
            return $this->content_path;
        }

        return null;
    }

    public function courseModule()
    {
        return $this->belongsTo(CourseModule::class, 'module_id');
    }

    public function course()
    {
        return $this->courseModule->course();
    }

    public function lessonProgress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->slug = Str::uuid();
        });
    }
}
