<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;

class Speaker extends Model
{
    use HasFactory, Notifiable;

    protected $guarded = [];

    public function routeNotificationForMail(){
        return $this->email;
    }
    public function events (){
        return $this->belongsToMany(Event::class, 'event_speaker');
    }

    public function creator(){
        return $this->belongsTo(User::class);
    }

    public function speakerApplications(){
        return $this->belongsTo(Event::class);
    }
}
