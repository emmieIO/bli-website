<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseOutcome extends Model
{
    protected $fillable = [
        'course_id',
        'outcome',
        'order',
    ];

    public function course(){
        return $this->belongsTo(Course::class);
    }
}
