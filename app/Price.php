<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Price extends Model
{
    use SoftDeletes;

    protected $table = 'tblLTP_Course_Price';

    public function courseschedules() {
        return $this->hasMany('App\CourseSchedule', 'Te_Price', 'id'); 
    }
}
