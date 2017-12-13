<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FocalPoints extends Model
{
    protected $table = 'tblLTP_FocalPoints';

    public function torgan() {
    return $this->belongsTo('App\Torgan'); 
    }

}