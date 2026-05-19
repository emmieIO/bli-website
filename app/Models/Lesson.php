<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
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
        'resource_url',
    ];

    /**
     * Get the video URL for Vimeo videos
     */
    public function getVideoUrlAttribute(): ?string
    {
        if ($this->type === 'video' && $this->vimeo_id) {
            return "https://player.vimeo.com/video/{$this->vimeo_id}";
        }

        if ($this->type === 'video' && $this->content_path) {
            return $this->resolveStoredMediaUrl($this->content_path, config('courses.lesson_video_disk', 's3'));
        }

        return null;
    }

    public function getResourceUrlAttribute(): ?string
    {
        if (!$this->content_path) {
            return null;
        }

        if ($this->type === 'link') {
            return $this->content_path;
        }

        if ($this->type === 'pdf') {
            return $this->resolveStoredMediaUrl($this->content_path, config('courses.lesson_document_disk', 'public'));
        }

        return $this->getVideoUrlAttribute();
    }

    protected function resolveStoredMediaUrl(string $path, string $diskName): ?string
    {
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $disk = Storage::disk($diskName);

        try {
            return $disk->temporaryUrl(
                $path,
                now()->addMinutes((int) config('courses.signed_url_ttl_minutes', 60))
            );
        } catch (\Throwable) {
            try {
                return $disk->url($path);
            } catch (\Throwable) {
                return null;
            }
        }
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
