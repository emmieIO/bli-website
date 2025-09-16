<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function events(){
        return $this->belongsToMany(Event::class, "event_attendees", 'user_id', "event_id")
        ->withPivot([
            'status', 'revoke_count'
        ])
        ->withTimestamps();
    }

    public function instructorProfile()
    {
        return $this->hasOne(InstructorProfile::class, 'user_id');
    }

    public function isApproved(){
        return optional($this->instructorProfile)->is_approved;
    }

    public function eventsCreated(){
        return $this->hasMany(Event::class, 'creator_id');
    }

    public function speakers(){
        return $this->hasMany(Speaker::class, 'created_by');
    }

    public function speaker(){
        return $this->hasOne(Speaker::class,'user_id');
    }

    public function applicationLogs(){
        return $this->hasMany(ApplicationLog::class);
    }
}
