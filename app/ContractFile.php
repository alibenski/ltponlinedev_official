<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContractFile extends Model
{
    protected $table = 'tblLTP_contract_files';
    protected $guarded = ['id'];

    public function enrolmentId()
    {
        return $this->belongsTo('App\Preenrolment', 'enrolment_id');
    }
}
