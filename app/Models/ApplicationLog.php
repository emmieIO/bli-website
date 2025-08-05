<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationLog extends Model
{
    protected $table = 'application_logs';

    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class, 'performed_by');
    }
}
