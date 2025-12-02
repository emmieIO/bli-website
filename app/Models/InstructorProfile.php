<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstructorProfile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'bio',
        'teaching_history',
        'experience_years',
        'area_of_expertise',
        'website',
        'intro_video_url',
        'resume_path',
        // Note: is_approved, status, approved_at are NOT mass-assignable for security
    ];

    protected $with = ["user"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
