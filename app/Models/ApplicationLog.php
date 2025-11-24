<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationLog extends Model
{
    protected $table = 'application_logs';

    protected $fillable = [
        'instructor_profile_id',
        'application_id',
        'performed_by',
        'action',
        'comment',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'performed_by');
    }
}
