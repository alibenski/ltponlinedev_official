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
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $schedules = Schedule::all();
        return view('schedules.index')->withSchedules($schedules);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

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
        $days = Day::pluck("Week_Day_Name","Week_Day_Number")->all(); 
        $times = Time::pluck("Begin_Time","id")->all(); 
        //$exists = $course->schedule->contains($schedule_id);
        return view('schedules.edit')->withSchedule($schedule)->withDays($days)->withTimes($times);
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
        // Validate data
        $schedule = Schedule::find($id);
            $this->validate($request, array(
                'sched_name' => 'required|max:255',

            )); 
                //'begin_day' => 'required',
                //'end_day' => 'required',
                //'begin_time' => 'required',
                //'end_time' => 'required',
        // Save the data to db
        $schedule = Schedule::find($id);
        $schedule->name = $request->input('sched_name');
        //$schedule->begin_day = $request->input('begin_day');
        //$schedule->end_day = $request->input('end_day');
        //$schedule->begin_time = $request->input('begin_time');
        //$schedule->end_time = $request->input('end_time');
        //$schedule->name = $request->input('begin_day'). '&' .$request->input('end_day'). ':' .$request->input('begin_time'). '-' .$request->input('end_time');
        $schedule->save();         
        // Set flash data with message
        $request->session()->flash('success', 'Changes have been saved!');
        // Redirect to flash data to posts.show
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
