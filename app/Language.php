<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    public function users() {
    return $this->hasMany('App\User'); 
    }

    public function courses() {
    return $this->hasMany('App\Course'); 
    }

    public function repos() {
    return $this->hasMany('App\Repo'); 
    }
}
