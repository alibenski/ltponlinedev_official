<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    //name of table is case sensitive
    protected $table = 'LTP_CR_LIST';

    public function users() {
    return $this->hasMany('App\User'); 
    }
    
    public function language() {
    return $this->belongsTo('App\Language', 'L', 'code'); 
    }

    public function schedule() {
    return $this->belongsToMany('App\Schedule'); 
    }

    public function repos() {
    return $this->hasMany('App\Repo'); 
    }
}
