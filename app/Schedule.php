<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    public function course() {
	//LTP_TEVENTCur as the pivot
    return $this->belongsToMany('App\Course', 'LTP_TEVENTCur', 'schedule_id', 'Te_Code'); 
    }

    
}
