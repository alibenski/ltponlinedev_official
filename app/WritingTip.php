<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WritingTip extends Model
{
    protected $table = 'tblLTP_writing_tips';

    public function languages() {
    return $this->belongsTo('App\Language', 'L', 'code'); 
    }
}
