<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseFormat extends Model
{
	use SoftDeletes;
	
    protected $table = 'tblLTP_Course_Format';
}
