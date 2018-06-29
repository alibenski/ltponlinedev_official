<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teachers extends Model
{
    protected $table = 'LTP_TEACHERS';
    protected $primaryKey = 'Tch_ID';
    //so Eloquent does not expect primary key to be auto-incrementing 
    public $incrementing = false;
}
