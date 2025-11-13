<?php

namespace App\Models;

use App\Enums\ApplicationStatus;
use App\Enums\CourseLevel;
use Illuminate\Database\Eloquent\Model;
use Str;

class Course extends Model
{

    protected $guarded = [];

    protected $casts = [
        'level' => CourseLevel::class,
        'status' => ApplicationStatus::class,
        'price' => 'decimal:2',
    ];
    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->slug = Str::uuid();
        });
    }

    public function getPreviewVideo()
    {
        return asset('storage/' . $this->preview_video_path);
    }


    public function getRouteKeyName()
    {
        return 'slug';
    }
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function requirements()
    {
        return $this->hasMany(CourseRequirement::class)->orderBy('order');
    }

    public function outcomes()
    {
        return $this->hasMany(CourseOutcome::class)->orderBy('order');
    }

    public function modules()
    {
        return $this->hasMany(CourseModule::class)->orderBy('order');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'course_user');
    }
}
