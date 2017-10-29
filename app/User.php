<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function courses() {
    return $this->belongsTo('App\Course', 'course_id'); 
    }

    public function languages() {
    return $this->belongsTo('App\Language', 'language_id'); 
    }
    
    public function repos() {
    return $this->hasMany('App\Repo'); 
    }
}
