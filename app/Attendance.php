<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'tblLTP_attendance';

    public function attendanceRemarks()
    {
        return $this->hasMany('App\AttendanceRemarks', 'attendance_id', 'id');
    }

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['availability'];

    public function getAvailabilityAttribute()
    {
        $qry = Attendance::where('pash_id', $this->pash_id)->get();

        // if no attendance has been entered yet, then 0 value
        if ($qry->isEmpty()) {

            $data = 0;
            return response()->json($data);
        }

        $array_attributes = [];
        foreach ($qry as $key => $value) {
            $arr = $value;
            $array_attributes[] = $arr->getAttributes();
        }

        $sumP = [];
        $sumE = [];
        $sumA = [];
        $info = [];
        $collector = [];
        foreach ($array_attributes as $x => $y) {
            $info['pash_id'] = $y['pash_id'];

            foreach ($y as $k => $v) {
                if ($v == 'P') {
                    $sumP[] = 'P';
                }

                if ($v == 'E') {
                    $sumE[] = 'E';
                }

                if ($v == 'A') {
                    $sumA[] = 'A';
                }
            }

            $info['P'] = count($sumP);
            $info['E'] = count($sumE);
            $info['A'] = count($sumA);

            $collector[] = $info;
            // clear contents of array for the next loop
            $sumP = [];
            $sumE = [];
            $sumA = [];
        }

        $data = $collector;

        return $collector;
        // return $this->calculateAvailability();
    }

    public function calculateAvailability()
    {
        return "code here";
    }
}
