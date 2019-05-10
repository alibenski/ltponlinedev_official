<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FocalPoints extends Model
{
		use SoftDeletes;

    protected $table = 'tblLTP_FocalPoints';

    public function torgan() {
    return $this->belongsTo('App\Torgan'); 
    }

}