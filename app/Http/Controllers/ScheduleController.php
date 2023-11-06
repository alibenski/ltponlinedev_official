<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Course;
use App\User;
use App\Schedule;
use App\Day;
use App\Time;
use DB;
use Session;

class ScheduleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $schedules = Schedule::all();
        return view('schedules.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $schedules = Schedule::all();
        $days = Day::pluck("Week_Day_Name", "Week_Day_Name")->all();
        $btimes = Time::pluck("Begin_Time", "Begin_Time")->all();
        $etimes = Time::orderBy("End_Time")->pluck("End_Time", "End_Time")->all();
        return view('schedules.create', compact('schedules', 'days', 'btimes', 'etimes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, array(
            'begin_day' => 'bail|required|',
            'begin_time' => 'required',
            'end_time' => 'required',
            'standard_format' => 'required',
        ));

        $countDays = count($request->begin_day);
        $implodeBeginDay = implode(' & ', $request->begin_day);
        $time_combination = date('h:ia', strtotime($request->begin_time)) . ' - ' . date('h:ia', strtotime($request->end_time));

        $implodeName = $implodeBeginDay . '  : ' . $time_combination;
        $request->merge(['name' => $implodeName]);

        $this->validate($request, array(
            'name' => 'unique:schedules,name|',
        ));

        $arrayBeginDayFr = [];
        foreach ($request->begin_day as $value) {
            $arrayBeginDayFr[] = __('days.' . $value, [], 'fr');
        }
        $implodeBeginDayFr = implode(' & ', $arrayBeginDayFr);

        // Save the data to db
        $schedule = new Schedule;
        $schedule->begin_day = $implodeBeginDay;
        $schedule->begin_day_fr = $implodeBeginDayFr;

        for ($i = 0; $i < $countDays; $i++) {
            if ($request->begin_day[$i] == 'Monday') {
                $schedule->day_1 = 2;
            }
            if ($request->begin_day[$i] == 'Tuesday') {
                $schedule->day_2 = 3;
            }
            if ($request->begin_day[$i] == 'Wednesday') {
                $schedule->day_3 = 4;
            }
            if ($request->begin_day[$i] == 'Thursday') {
                $schedule->day_4 = 5;
            }
            if ($request->begin_day[$i] == 'Friday') {
                $schedule->day_5 = 6;
            }
            if ($request->begin_day[$i] == 'Saturday') {
                $schedule->day_6 = 7;
            }
        }

        $schedule->standard_format = $request->standard_format;
        $schedule->begin_time = $request->begin_time;
        $schedule->end_time = $request->end_time;
        $schedule->name = $implodeBeginDay . '  : ' . $time_combination;
        $schedule->name_fr = $implodeBeginDayFr . '  : ' . $time_combination;
        $schedule->time_combination = $time_combination;
        $schedule->save();

        // Set flash data with message
        $request->session()->flash('success', 'New entry has been saved!');
        // Redirect with flash data 
        return redirect()->route('schedules.index');
    }

    public function storeNonStandardSchedule(Request $request)
    {
        $this->validate($request, array(
            'begin_time' => 'required',
            'end_time' => 'required',
            'standard_format' => 'required',
        ));

        $time_combination = date('h:ia', strtotime($request->begin_time)) . ' - ' . date('h:ia', strtotime($request->end_time));
        $implodeName = $request->sched_name . ': ' . $time_combination;
        $request->merge(['name' => $implodeName]);

        $this->validate($request, array(
            'sched_name' => 'unique:schedules,name|',
            'sched_name_fr' => 'required',
        ));

        // Save the data to db
        $schedule = new Schedule;
        $schedule->begin_day = $request->sched_name;
        $schedule->begin_day_fr = $request->sched_name_fr;

        if ($request->begin_day) {
            $countDays = count($request->begin_day);
            for ($i = 0; $i < $countDays; $i++) {
                if ($request->begin_day[$i] == 'Monday') {
                    $schedule->day_1 = 2;
                }
                if ($request->begin_day[$i] == 'Tuesday') {
                    $schedule->day_2 = 3;
                }
                if ($request->begin_day[$i] == 'Wednesday') {
                    $schedule->day_3 = 4;
                }
                if ($request->begin_day[$i] == 'Thursday') {
                    $schedule->day_4 = 5;
                }
                if ($request->begin_day[$i] == 'Friday') {
                    $schedule->day_5 = 6;
                }
                if ($request->begin_day[$i] == 'Saturday') {
                    $schedule->day_6 = 7;
                }
            }
        } else {
            $schedule->day_1 = 2;
            $schedule->day_2 = 3;
            $schedule->day_3 = 4;
            $schedule->day_4 = 5;
            $schedule->day_5 = 6;
        }

        $schedule->standard_format = $request->standard_format;
        $schedule->begin_time = $request->begin_time;
        $schedule->end_time = $request->end_time;
        $schedule->name = $request->sched_name . ': ' . $time_combination;
        $schedule->name_fr = $request->sched_name_fr . ': ' . $time_combination;
        $schedule->time_combination = $time_combination;
        $schedule->save();

        // Set flash data with message
        $request->session()->flash('success', 'New entry has been saved!');
        // Redirect with flash data
        return redirect()->route('schedules.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $schedule = Schedule::find($id);
        $days = Day::pluck("Week_Day_Name", "Week_Day_Name")->all();
        $btimes = Time::pluck("Begin_Time", "Begin_Time")->all();
        $etimes = Time::pluck("End_Time", "End_Time")->all();
        //$exists = $course->schedule->contains($schedule_id);
        return view('schedules.edit', compact('schedule', 'days', 'btimes', 'etimes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, array(
            'begin_day' => 'bail|required|',
            'begin_time' => 'required',
            'end_time' => 'required',
            'standard_format' => 'required',
        ));

        $implodeBeginDay = implode(' & ', $request->begin_day);
        $time_combination = date('h:ia', strtotime($request->begin_time)) . ' - ' . date('h:ia', strtotime($request->end_time));
        $implodeName = $implodeBeginDay . '  : ' . $time_combination;
        $request->merge(['name' => $implodeName]);

        // Validate data
        $this->validate($request, array(
            'name' => 'unique:schedules,name|',
        ));

        $arrayBeginDayFr = [];
        foreach ($request->begin_day as $value) {
            $arrayBeginDayFr[] = __('days.' . $value, [], 'fr');
        }
        $implodeBeginDayFr = implode(' & ', $arrayBeginDayFr);

        // Save the data to db
        $schedule = Schedule::find($id);
        $schedule->begin_day = $implodeBeginDay;
        $schedule->begin_day_fr = $implodeBeginDayFr;

        // set fields to null first
        $schedule->day_1 = null;
        $schedule->day_2 = null;
        $schedule->day_3 = null;
        $schedule->day_4 = null;
        $schedule->day_5 = null;
        $schedule->day_6 = null;

        $countDays = count($request->begin_day);
        for ($i = 0; $i < $countDays; $i++) {
            if ($request->begin_day[$i] == 'Monday') {
                $schedule->day_1 = 2;
            }
            if ($request->begin_day[$i] == 'Tuesday') {
                $schedule->day_2 = 3;
            }
            if ($request->begin_day[$i] == 'Wednesday') {
                $schedule->day_3 = 4;
            }
            if ($request->begin_day[$i] == 'Thursday') {
                $schedule->day_4 = 5;
            }
            if ($request->begin_day[$i] == 'Friday') {
                $schedule->day_5 = 6;
            }
            if ($request->begin_day[$i] == 'Saturday') {
                $schedule->day_6 = 7;
            }
        }

        $schedule->standard_format = $request->standard_format;
        $schedule->begin_time = $request->input('begin_time');
        $schedule->end_time = $request->input('end_time');
        $schedule->name = $implodeBeginDay . '  : ' . $time_combination;
        $schedule->name_fr = $implodeBeginDayFr . '  : ' . $time_combination;
        $schedule->save();

        $request->session()->flash('success', 'Changes have been saved!');

        return redirect()->route('schedules.index');
    }

    public function updateNonStandardSchedule(Request $request, $id)
    {
        $this->validate($request, array(
            'sched_name_fr' => 'required',
            'begin_time' => 'required',
            'end_time' => 'required',
            'standard_format' => 'required',
        ));
        $time_combination = date('h:ia', strtotime($request->begin_time)) . ' - ' . date('h:ia', strtotime($request->end_time));
        $implodeName = $request->sched_name . ': ' . $time_combination;
        $request->merge(['name' => $implodeName]);

        $this->validate($request, array(
            'sched_name' => 'unique:schedules,name|',
        ));

        // Save the data to db
        $schedule = Schedule::find($id);
        $schedule->begin_day = $request->sched_name;
        $schedule->begin_day_fr = $request->sched_name_fr;

        // set fields to null first
        $schedule->day_1 = null;
        $schedule->day_2 = null;
        $schedule->day_3 = null;
        $schedule->day_4 = null;
        $schedule->day_5 = null;
        $schedule->day_6 = null;

        if ($request->begin_day) {
            $countDays = count($request->begin_day);
            for ($i = 0; $i < $countDays; $i++) {
                if ($request->begin_day[$i] == 'Monday') {
                    $schedule->day_1 = 2;
                }
                if ($request->begin_day[$i] == 'Tuesday') {
                    $schedule->day_2 = 3;
                }
                if ($request->begin_day[$i] == 'Wednesday') {
                    $schedule->day_3 = 4;
                }
                if ($request->begin_day[$i] == 'Thursday') {
                    $schedule->day_4 = 5;
                }
                if ($request->begin_day[$i] == 'Friday') {
                    $schedule->day_5 = 6;
                }
                if ($request->begin_day[$i] == 'Saturday') {
                    $schedule->day_6 = 7;
                }
            }
        } else {
            $schedule->day_1 = 2;
            $schedule->day_2 = 3;
            $schedule->day_3 = 4;
            $schedule->day_4 = 5;
            $schedule->day_5 = 6;
        }

        $schedule->standard_format = $request->standard_format;
        $schedule->begin_time = $request->begin_time;
        $schedule->end_time = $request->end_time;
        $schedule->name = $request->sched_name . ': ' . $time_combination;
        $schedule->name_fr = $request->sched_name_fr . ': ' . $time_combination;
        $schedule->time_combination = $time_combination;
        $schedule->save();

        // Set flash data with message
        $request->session()->flash('success', 'Changes have been saved! [Non-standard Schedule]');
        // Redirect with flash data
        return redirect()->route('schedules.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
