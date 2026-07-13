<?php

namespace App\Models;


use App\Enums\SpeakerStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;

class Speaker extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'created_by',
        'title',
        'organization',
        'bio',
        'photo',
        'linkedin',
        'website',
        'status',
    ];

    protected $casts = [
        'status' => SpeakerStatus::class,
    ];

    protected $with=['user'];

    public function routeNotificationForMail(){
        return $this->user?->email;
    }
    public function events (){
        return $this->belongsToMany(Event::class, 'event_speaker');
    }

    public function creator(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function speakerApplications(){
        return $this->hasMany(SpeakerApplication::class);
    }

    public function invitations()
    {
        return $this->hasMany(SpeakerInvite::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function isActive(){
        return $this->status == SpeakerStatus::ACTIVE->value;
    }
}
