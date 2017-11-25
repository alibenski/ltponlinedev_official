<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Preenrolment extends Model
{
    protected $table = 'tblLTP_Enrolment';
    protected $fillable = [
        'CodeIndexID', 'Code', 'Te_Code', 'schedule_id', 'Term', 'INDEXID', 'mgr_email', 'L', 
    ];

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'UpdatedOn';

    //declare the foreign key on the 3rd parameter of the function
    //in this case, field Te_Code inside table PASH is associated to foreign key Te_Code
    //which is a field in table LTP_Terms (Model: Term) 
    public function courses() {
    return $this->belongsTo('App\Course', 'Te_Code', 'Te_Code'); 
	}
    public function schedule() {
    return $this->belongsTo('App\Schedule', 'schedule_id'); 
    }
    public function languages() {
    return $this->belongsTo('App\Language', 'L', 'code'); 
    }
    public function users() {
    return $this->belongsTo('App\User', 'INDEXID', 'indexno'); 
    }
	public function terms() {
    return $this->belongsTo('App\Term', 'Term', 'Term_Code'); 
    }

}