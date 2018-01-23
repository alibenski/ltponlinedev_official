<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $table = 'LTP_TEVENTCur';
    protected $fillable = [
        'Code', 'cs_unique','Te_Code_New', 'schedule_id', 'Te_Term',  
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

}
