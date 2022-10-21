<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Identity2File extends Model
{
    protected $table = 'tblLTP_identity_2_files';
    protected $guarded = ['id'];

    public function enrolmentId()
    {
        return $this->belongsToMany('App\Preenrolment', 'enrolment_id');
    }
}
