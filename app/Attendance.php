<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'tblLTP_attendance';

    public function attendanceRemarks() {
        return $this->hasMany('App\AttendanceRemarks', 'attendance_id', 'id'); 
    }
    
}
