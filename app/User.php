<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'indexno','name', 'email', 'temp_email', 'password', 'approved_account', 'approved_update', 'account_token', 'update_token',
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

    public function sddextr() {
    return $this->hasOne('App\SDDEXTR', 'INDEXNO', 'indexno'); 
    }
}
