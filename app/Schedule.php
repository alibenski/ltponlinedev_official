<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    public function course() {
    return $this->belongsToMany('App\Course', 'course_schedule', 'schedule_id', 'course_id'); 
    }

	public function classroom() {
    return $this->hasMany('App\Classroom'); 
    }
}
