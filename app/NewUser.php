<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewUser extends Model
{
    protected $table = 'tblLTP_New_Users';

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

}
