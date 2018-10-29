<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminComment extends Model
{
    protected $table = 'tblLTP_AdminComments';

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id' );
    }

    public function enrolmentForm()
    {
        return $this->belongsTo('App\Preenrolment', 'CodeIndexID', 'CodeIndexID');
    }
}
