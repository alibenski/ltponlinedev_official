<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Repo extends Model
{
    protected $table = 'LTP_PASHQTcur';
    protected $fillable = [
        'CodeIndexIDClass','CodeClass', 'schedule_id','CodeIndexID', 'Code', 'Te_Code', 'Term', 'INDEXID', 'EMAIL', 'L', 'DEPT', 'PS', 'Comments', 'created_at', 'UpdatedOn', 'flexibleBtn', 'form_counter',
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
    return $this->belongsTo('App\Course', 'Te_Code', 'Te_Code_New'); 
	}

    public function coursesOld() {
    return $this->belongsTo('App\Course', 'Te_Code_old', 'Te_Code'); 
    }

    public function languages() {
    return $this->belongsTo('App\Language', 'L', 'code'); 
    }

    public function users() {
    return $this->belongsTo('App\User', 'INDEXID'); 
    }

	public function terms() {
    return $this->belongsTo('App\Term', 'Term', 'Term_Code'); 
    }

}
