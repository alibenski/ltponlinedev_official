<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseDuration extends Model
{
    use SoftDeletes;

    protected $table = 'tblLTP_Course_Duration';
}
