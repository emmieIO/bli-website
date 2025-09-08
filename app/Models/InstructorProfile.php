<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstructorProfile extends Model
{
    use SoftDeletes;
    protected $guarded =[];
    protected $with = ["user"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
