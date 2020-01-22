<?php

namespace App\Http\Controllers;

use App\Classroom;
use App\Course;
use App\Schedule;
use App\Term;
use App\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $languages = DB::table('languages')->pluck("name", "code")->all();

        $courses = new Course;
        $queries = [];
        $columns = [
            'L',
        ];


        foreach ($columns as $column) {
            if (\Request::filled($column)) {
                $courses = $courses->where($column, \Request::input($column));
                $queries[$column] = \Request::input($column);
            }
        }

        if (\Request::filled('sort')) {
            $courses = $courses->orderBy('created_at', \Request::input('sort'));
            $queries['sort'] = \Request::input('sort');
        }
        $courses = $courses->whereNotNull('Te_Code_New')->paginate(20)->appends($queries);

        return view('courses.index', compact('courses', 'languages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $course_type = DB::table('tblLTP_Course_Type_Param')->pluck("DescriptionEn","CourseType")->all();
        $course_type = DB::table('tblLTP_Course_Type_Param')->get();
        $course_level_type = DB::table('tblLTP_Course_Level_Type_Param')->pluck("LevelEn", "LevelType")->all();
        $course_order = DB::table('tblLTP_Course_Order_Param')->pluck("Order", "Order")->all();
        //$courses = Course::all(['id', 'name']); // selected $key => $value
        $languages = DB::table('languages')->pluck("name", "code")->all();

        //get latest semester/term
        $terms = DB::table('LTP_Terms')->orderBy('Term_Code', 'DESC')->first();

        return view('courses.create', compact('course_type', 'course_level_type', 'course_order', 'languages', 'terms'));
    }

    public function checkExistingTeCode(Request $request)
    {
        if ($request->ajax()) {
            $checkExistingTeCode = Course::select('Te_Code_New')->whereNotNUll('Te_Code_New')->where('L', $request->L)->get();

            $data = $checkExistingTeCode;
            return response()->json($data);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $TeCodeNew = $request->L . $request->LevelType . $request->CourseType . $request->Order;
        $request->request->add(['Course_Code' => $TeCodeNew]); //add to $request

        // validate the data
        $this->validate($request, array(
            'Course_Code' => 'unique:LTP_CR_LIST,Te_Code_New|',
            'L' => 'required|',
            'CourseType' => 'required|',
            'LevelType' => 'required|',
            'Order' => 'required|',
            'Description' => 'required|',
            'FDescription' => 'required|',
        ));

        // store in database
        $course = new Course;
        $course->Te_Code = $request->L . $request->LevelType . $request->CourseType . $request->Order;
        $course->Te_Code_New = $request->L . $request->LevelType . $request->CourseType . $request->Order;
        $course->L = $request->L;
        $course->Description = $request->Description;
        $course->EDescription = $request->Description;
        $course->FDescription = $request->FDescription;
        $course->created_by = Auth::user()->id;
        $course->save();
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
        return view('courses.edit', compact('course', 'exists'));
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
            'CourseName' => 'required|max:255',
            'FrenchCourseName' => 'required|max:255',
        ));

        // Save the data to db
        $course = Course::find($id);

        $course->Description = $request->CourseName;
        $course->EDescription = $request->CourseName;
        $course->FDescription = $request->FrenchCourseName;
        $course->updated_by = $request->user_id;
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
