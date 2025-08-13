<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Speaker extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function events (){
        return $this->belongsToMany(Event::class, 'event_speaker');
    }

    public function creator(){
        return $this->belongsTo(User::class);
    }

    // public function speakerApplication(){
    //     return $this->belongsTo(SpeakerApplication::class);
    // }
}
