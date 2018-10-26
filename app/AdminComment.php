<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminComment extends Model
{
    protected $table = 'tblLTP_AdminComments';

    public function enrolmentForm()
    {
        return $this->belongsTo('App\Preenrolment', 'CodeIndexID', 'CodeIndexID');
    }

    public function placementForm()
    {
        return $this->belongsTo('App\PlacementFrom');
    }
}
