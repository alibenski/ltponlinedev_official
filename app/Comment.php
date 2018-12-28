<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'tblLTP_comments';

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id' );
    }

    public function pash()
    {
        return $this->belongsTo('App\Repo', 'pash_id', 'id');
    }
}
