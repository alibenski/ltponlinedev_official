<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Course;
use App\User;
use App\Schedule;
use App\Classroom;
use App\Term;
use DB;

class CourseController extends Controller
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
        $languages = DB::table('languages')->pluck("name","code")->all();

        $courses = new Course;
        $queries = [];
        $columns = [
            'L',
        ];

        
        foreach ($columns as $column) {
            if (\Request::has($column)) {
                $courses = $courses->where($column, \Request::input($column) );
                $queries[$column] = \Request::input($column);
            }

        } 

            if (\Request::has('sort')) {
                $courses = $courses->orderBy('created_at', \Request::input('sort') );
                $queries['sort'] = \Request::input('sort');
            }
        $courses = $courses->whereNotNull('Te_Code_New')->paginate(10)->appends($queries);

        return view('courses.index')->withCourses($courses)->withLanguages($languages);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $course_type = DB::table('tblLTP_Course_Type_Param')->pluck("DescriptionEn","CourseType")->all();
        $course_level_type = DB::table('tblLTP_Course_Level_Type_Param')->pluck("LevelEn","LevelType")->all();
        $course_order = DB::table('tblLTP_Course_Order_Param')->pluck("Order","Order")->all();
        //$courses = Course::all(['id', 'name']); // selected $key => $value
        $languages = DB::table('languages')->pluck("name","code")->all();

        //get latest semester/term
        $terms = DB::table('LTP_Terms')->orderBy('Term_Code', 'DESC')->first();

        return view('courses.create')->withCourse_type($course_type)
                ->withCourse_level_type($course_level_type)
                ->withCourse_order($course_order)
                ->withLanguages($languages)
                ->withTerms($terms);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate the data
        $this->validate($request, array(
                //'name' => 'required|max:255',
            ));

        // store in database
        $course = new Course;
        
        // variable course refers to schedule function in Course.php model
        // then syncs the data to schedules MySQL table
        // $course->schedule()->sync($request->schedules, false);
        $request->session()->flash('success', 'Entry has been saved!'); //laravel 5.4 version

        return redirect()->route('courses.index');
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
        $course = Course::find($id);
        // get first element in Schedule collection
        $schedule_id = Schedule::first(); 
        // variable "exists" returns a boolean for the specific course
        $exists = $course->schedule->contains($schedule_id);
        return view('courses.edit')->withCourse($course)->withExists($exists);
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
                'name' => 'required|max:255',
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
