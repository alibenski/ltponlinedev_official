<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public function users() {
    return $this->hasMany('App\User'); 
    }
    
    public function language() {
    return $this->belongsTo('App\Language', 'language_id'); 
    }

    public function schedule() {
    return $this->belongsToMany('App\Schedule'); 
    }

    public function repos() {
    return $this->hasMany('App\Repo'); 
    }
}
