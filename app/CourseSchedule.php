<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseSchedule extends Model
{
    protected $table = 'tblltp_csv_extract';
    protected $fillable = [
        'course', 'day','time',  
    ];
}
