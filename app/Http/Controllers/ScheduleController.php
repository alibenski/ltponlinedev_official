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
        $schedules = Schedule::all();
        $days = Day::pluck("Week_Day_Name","Week_Day_Name")->all(); 
        $btimes = Time::pluck("Begin_Time","Begin_Time")->all();
        $etimes = Time::pluck("End_Time","End_Time")->all(); 
        return view('schedules.create')->withSchedules($schedules)->withDays($days)->withBtimes($btimes)->withEtimes($etimes);
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
                'end_day' => 'bail|required|different:begin_day',
                'begin_time' => 'required',
                'end_time' => 'required',
            )); 

        // Save the data to db
        $schedule = new Schedule;
        $schedule->begin_day = $request->begin_day;
        $schedule->end_day = $request->end_day;
        $schedule->begin_time = $request->begin_time;
        $schedule->end_time = $request->end_time;
        $schedule->name = $request->begin_day. '  ' .$request->end_day. ': ' .date('h:i:sa', strtotime($request->begin_time)). ' - ' .date('h:i:sa', strtotime($request->end_time));
        $schedule->save();         
        // Set flash data with message
        $request->session()->flash('success', 'New entry has been saved!');
        // Redirect to flash data to posts.show
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
        $days = Day::pluck("Week_Day_Name","Week_Day_Name")->all(); 
        $btimes = Time::pluck("Begin_Time","Begin_Time")->all();
        $etimes = Time::pluck("End_Time","End_Time")->all(); 
        //$exists = $course->schedule->contains($schedule_id);
        return view('schedules.edit')->withSchedule($schedule)->withDays($days)->withBtimes($btimes)->withEtimes($etimes);
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
                'begin_day' => 'required',
                'end_day' => 'required',
                'begin_time' => 'required',
                'end_time' => 'required',
            )); 

        // Save the data to db
        $schedule = Schedule::find($id);
        //$schedule->name = $request->input('sched_name');
        $schedule->begin_day = $request->input('begin_day');
        $schedule->end_day = $request->input('end_day');
        $schedule->begin_time = $request->input('begin_time');
        $schedule->end_time = $request->input('end_time');
        $schedule->name = $request->input('begin_day'). ' & ' .$request->input('end_day'). ': ' .date('h:i:sa', strtotime($request->input('begin_time'))). ' - ' .date('h:i:sa', strtotime($request->input('end_time')));
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
