<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CourseModule extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'slug',
        'description',
        'order',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class, "module_id")->orderBy('order');
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->slug = Str::uuid();
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

}
