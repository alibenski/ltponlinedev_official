<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teachers extends Model
{
    protected $table = 'LTP_TEACHERS';
    protected $primaryKey = 'Tch_ID';
    protected $fillable = [
        'Tch_L', 'IndexNo', 'In_Out', 'Tch_Lastname', 'Tch_Firstname', 'email', 'sex', 'Tch_Name',
    ];
    //so Eloquent does not expect primary key to be auto-incrementing 
    public $incrementing = false;

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'Updated';

    public function users() {
        return $this->belongsTo('App\User','IndexNo','indexno'); 
    }
}
