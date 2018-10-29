<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminCommentPlacement extends Model
{
    protected $table = 'tblLTP_AdminCommentsPlacement';

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id' );
    }

    public function placementForm()
    {
        return $this->belongsTo('App\PlacementForm', 'placement_id', 'id');
    }
}
