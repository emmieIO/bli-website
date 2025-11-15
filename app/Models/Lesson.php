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
    ];

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
