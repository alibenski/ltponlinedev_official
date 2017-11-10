<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Course;
use App\User;
use App\Schedule;
use App\Classroom;
use App\Term;
use DB;

class ClassroomController extends Controller
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
        $classrooms = Classroom::orderBy('id','DESC')->paginate(15);
        return view('classrooms.index')->withClassrooms($classrooms);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $courses = Course::all();
        //$courses = Course::all(['id', 'name']); // selected $key => $value
        $languages = DB::table('languages')->pluck("name","code")->all();
        $schedules = Schedule::pluck("name","id")->all();
        //get latest semester/term
        $terms = Term::orderBy('Term_Code', 'DESC')->first();

        return view('classrooms.create')->withCourses($courses)->withLanguages($languages)->withSchedules($schedules)->withTerms($terms);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd();
        //validate the data
        $this->validate($request, array(
                'Code' => 'unique:LTP_TEVENTCur,Code|',
                'term_id' => 'required|',
                //'schedule_id' => 'required|integer',
                'course_id' => 'required|',
                
            ));

        //store in database
        $courseclass = new Classroom;
        //store data to Classroom model's column  = data from input attr. name
        $courseclass->Te_Term = $request->term_id;
        $courseclass->Te_Code = $request->course_id;
        $courseclass->schedule_id = $request->schedule_id;
        $courseclass->Code = $request->Code;
    dd();
        $courseclass->save();

        $request->session()->flash('success', 'Entry has been saved!'); //laravel 5.4 version

        return redirect()->route('classrooms.index');
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

        return 'edit classrooms page';
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
        $course = Course::find($id);
            $this->validate($request, array(
                //'name' => 'required|max:255',
            )); 

        // Save the data to db
        $course = Course::find($id);

        $course->name = $request->input('name');
        $course->save();         
        // Set flash data with message
        $request->session()->flash('success', 'Changes have been saved!');
        // Redirect to flash data to posts.show
        return redirect()->route('courses.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $course = Course::find($id);
        $course->delete();
        session()->flash('success', 'Post deleted');
        return redirect()->route('courses.index');
    }
}
