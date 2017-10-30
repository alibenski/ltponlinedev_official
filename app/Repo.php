<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Repo extends Model
{
    protected $table = 'LTP_PASHQTcur';
    protected $fillable = [
        'CodeIndexID', 'Code', 'Course_Code', 'Term', 'INDEXID', 'EMAIL', 'Language_Code', 
    ];
    //declare the foreign key on the 3rd parameter of the function
    //in this case, field Course_Code inside table PASH is associated to foreign key Te_Code
    //which is a field in table LTP_Terms (Model: Term) 
    public function courses() {
    return $this->belongsTo('App\Course', 'Course_Code', 'Te_Code'); 
	}

    public function languages() {
    return $this->belongsTo('App\Language', 'Language_Code', 'code'); 
    }

    public function users() {
    return $this->belongsTo('App\User', 'INDEXID'); 
    }

	public function terms() {
    return $this->belongsTo('App\Term', 'Term', 'Term_Code'); 
    }

}
