<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $guarded =['id'];

    public static function boot(){
        parent::boot();
        static::creating(function($model){
            $model->slug = Str::uuid();
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
