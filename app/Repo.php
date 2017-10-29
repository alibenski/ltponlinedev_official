<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Repo extends Model
{
    protected $table = 'LTP_PASHQTcur';
    protected $fillable = [
        'CodeIndexID', 'Code', 'Course_Code', 'Term_Code', 'INDEXID', 'EMAIL', 'Language_Code', 
    ];

    public function courses() {
    return $this->belongsTo('App\Course', 'Course_Code'); 
	}

    public function languages() {
    return $this->belongsTo('App\Language', 'Language_Code'); 
    }

    public function users() {
    return $this->belongsTo('App\User', 'INDEXID'); 
    }

	public function terms() {
    return $this->belongsTo('App\Term', 'Term_Code'); 
    }

}
