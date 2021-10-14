<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewUser extends Model
{
    protected $table = 'tblLTP_New_Users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'indexno_new', 'title', 'profile', 'name', 'nameFirst', 'nameLast', 'email', 'dob', 'attachment_id', 'attachment_id_2', 'approved_account', 'updated_by', 'gender', 'org', 'country_mission', 'ngo_name', 'contact_num', 
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
    	'dob',
    ];

	public function filesId() {
        return $this->belongsTo('App\FileNewUser', 'attachment_id'); 
    }
    
    public function filesId2() {
        return $this->belongsTo('App\FileNewUser', 'attachment_id_2'); 
    } 

    public function countryMission() {
        return $this->belongsTo('App\Country', 'country_mission'); 
    } 

    public function newUserComments() {
        return $this->hasMany('App\NewUserComments', 'new_user_id', 'id'); 
    } 

}
