<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
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
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'linkedin',
        'website',
        'headline',
        'photo',
        'email_verified_at',
    ];

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

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->slug = (string) Str::uuid();
        });
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, "event_attendees", 'user_id', "event_id")
            ->withPivot([
                'status',
                'revoke_count'
            ])
            ->withTimestamps();
    }

    public function instructorProfile()
    {
        return $this->hasOne(InstructorProfile::class, 'user_id');
    }

    public function coursesTeaching()
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    public function isApproved()
    {
        return optional($this->instructorProfile)->is_approved;
    }

    public function eventsCreated()
    {
        return $this->hasMany(Event::class, 'creator_id');
    }

    public function speakers()
    {
        return $this->hasMany(Speaker::class, 'created_by');
    }

    public function speaker()
    {
        return $this->hasOne(Speaker::class, 'user_id');
    }

    public function applicationLogs()
    {
        return $this->hasMany(ApplicationLog::class);
    }

    public function hasSpeakerProfile()
    {
        return $this->speaker !== null;
    }

    public function hasInstructorProfile()
    {
        return $this->instructorProfile !== null;
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function courseEnrollments()
    {
        return $this->belongsToMany(Course::class, 'course_user')
            ->withTimestamps();
    }

    public function lessonProgress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    /**
     * Ratings given by this user to instructors
     */
    public function ratingsGiven()
    {
        return $this->hasMany(InstructorRating::class, 'user_id');
    }

    /**
     * Ratings received by this user as an instructor
     */
    public function ratingsReceived()
    {
        return $this->hasMany(InstructorRating::class, 'instructor_id');
    }

    /**
     * Alias for ratingsReceived (for instructor context)
     */
    public function ratings()
    {
        return $this->ratingsReceived();
    }

    /**
     * Get the average rating for this instructor
     */
    public function getAverageRatingAttribute()
    {
        return $this->ratingsReceived()->avg('rating') ?? 0;
    }

    /**
     * Get the total number of ratings for this instructor
     */
    public function getTotalRatingsAttribute()
    {
        return $this->ratingsReceived()->count();
    }

    /**
     * Get the user's shopping cart
     */
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * Get or create the user's cart
     */
    public function getOrCreateCart(): Cart
    {
        return $this->cart()->firstOrCreate(['user_id' => $this->id]);
    }
}
