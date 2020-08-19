<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamResult extends Model
{
    use SoftDeletes;

    protected $table = 'tblLTP_Placement_Exam_Results';

    public function placementForms()
    {
        return $this->belongsTo('App\PlacementForm', 'placement_id');
    }
}
