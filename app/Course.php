<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    //name of table is case sensitive for some reason
    protected $table = 'LTP_CR_LIST';

    public function classes() {
    return $this->hasMany('App\Classroom'); 
    }

    public function users() {
    return $this->hasMany('App\User'); 
    }
    
    public function language() {
    return $this->belongsTo('App\Language', 'L', 'code'); 
    }


    public function repos() {
    return $this->hasMany('App\Repo'); 
    }

    public function schedule() {
    return $this->belongsToMany('App\Schedule'); 
    }

}