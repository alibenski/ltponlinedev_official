<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlacementSchedule extends Model
{
    //name of table is case sensitive for some reason
    protected $table = 'tblLTP_Placement_Schedule';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'term', 'language_id', 'date_of_plexam', 'date_of_plexam_end', 'is_online', 'time_of_plexam', 
    ];

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

    public function preenrol() {
    return $this->hasMany('App\Preenrolment',  'Te_Code', 'Te_Code'); 
    }

    public function schedule() {
    return $this->belongsToMany('App\Schedule'); 
    }

}