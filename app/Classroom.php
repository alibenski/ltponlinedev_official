<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $table = 'LTP_TEVENTCur';
    protected $fillable = [
        'Code', 'cs_unique', 'Te_Term', 'L', 'Te_Code_New', 'schedule_id', 'sectionNo', 'Tch_ID', 'Te_Mon', 'Te_Mon_Room', 'Te_Mon_BTime', 'Te_Mon_ETime', 'Te_Tue', 'Te_Tue_Room', 'Te_Tue_BTime', 'Te_Tue_ETime', 'Te_Wed', 'Te_Wed_Room', 'Te_Wed_BTime', 'Te_Wed_ETime', 'Te_Thu', 'Te_Thu_Room', 'Te_Thu_BTime', 'Te_Thu_ETime', 'Te_Fri', 'Te_Fri_Room', 'Te_Fri_BTime', 'Te_Fri_ETime', 
    ];

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'Updated';


    //declare the foreign key on the 3rd parameter of the function
    //in this case, field Te_Code inside table PASH is associated to foreign key Te_Code
    //which is a field in table LTP_Terms (Model: Term) 
    public function course() {
    return $this->belongsTo('App\Course', 'Te_Code_New', 'Te_Code_New'); 
    }

    public function scheduler() {
    return $this->belongsTo('App\Schedule', 'schedule_id'); 
    }

	public function terms() {
    return $this->belongsTo('App\Term', 'Te_Term', 'Term_Code'); 
    }

    public function rooms() {
    return $this->belongsTo('App\Room', 'room_id'); 
    }

    public function teachers() {
    return $this->belongsTo('App\Teachers', 'Tch_ID', 'Tch_ID'); 
    }
}
