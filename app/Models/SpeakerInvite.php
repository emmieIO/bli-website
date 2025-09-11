<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpeakerInvite extends Model
{
    protected $guarded = [];
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function speaker(){
        return $this->belongsTo(Speaker::class);
    }
}
