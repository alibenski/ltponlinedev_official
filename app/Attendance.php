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

    public function pashRecord()
    {
        return $this->hasOne('App\Repo', 'id', 'pash_id');
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
        // get the term from relationship with Repo
        foreach ($qry as $record) {
            if ($record->pashRecord) {
                $term = $record->pashRecord->Term;
            }
        }

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
        // exclude Wk1_ from sum if term > 240
        if ($term > 240) {
            foreach ($array_attributes as $x => $y) {
                $info['pash_id'] = $y['pash_id'];

                foreach ($y as $k => $v) {
                    if (substr($k, 0, 4) != "Wk1_") {
                        if ($v == 'P') {
                            $sumP[] = 'P';
                        }
                    }
                    if (substr($k, 0, 4) != "Wk1_") {
                        if ($v == 'E') {
                            $sumE[] = 'E';
                        }
                    }
                    if (substr($k, 0, 4) != "Wk1_") {
                        if ($v == 'A') {
                            $sumA[] = 'A';
                        }
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
        } else {
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
