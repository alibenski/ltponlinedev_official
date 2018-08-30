<?php

namespace App\Http\Controllers;

use App\Classroom;
use App\Course;
use App\CourseSchedule;
use App\CsvModel;
use App\Language;
use App\Room;
use App\Schedule;
use App\Teachers;
use App\Term;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class CourseSchedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $terms = Term::orderBy('Term_Code', 'desc')->get();
        $course_schedule = CourseSchedule::orderBy('Te_Term', 'DESC')->paginate(10);

        return view('courses_schedules.index')->withCourse_schedule($course_schedule)->withTerms($terms);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $courses = Course::all();
        $languages = Language::pluck("name","code")->all();
        $schedules = Schedule::pluck("name","id")->all();
        $terms = Term::orderBy('Term_Code', 'desc')->get();

        $format = DB::table('tblLTP_Course_Format')->pluck("format_name_en","id")->all();
        $duration = DB::table('tblLTP_Course_Duration')->pluck("duration_name_en","id")->all();
        $teachers = Teachers::where('In_Out', '1')->get();
        $rooms = Room::all();

        return view('courses_schedules.create')->withCourses($courses)->withLanguages($languages)->withSchedules($schedules)->withTerms($terms)->withFormat($format)->withDuration($duration)->withTeachers($teachers)->withRooms($rooms);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $course_id = $request->course_id;
        $term_id = $request->term_id;
        $schedule_id = $request->schedule_id;
        $cs_unique = $request->cs_unique;
        $codex = [];     
        //concatenate (implode) Code input before validation   
        //check if $code has no input
        if ( empty( $code ) ) {
            //loop based on $room_id count and store in $codex array
            for ($i=0; $i < count($schedule_id); $i++) { 
                $codex[] = array($course_id, $term_id, $schedule_id[$i]);
                //implode array elements and pass imploded string value to $codex array as element
                $codex[$i] = implode('-', $codex[$i]);
                //for each $codex array element stored, loop array merge method
                //and output each array element to a string via $request->Code
                foreach ($codex as $value) {
                    $request->merge( [ 'cs_unique' => $value ] );
                }
                        var_dump($request->cs_unique);
                        $this->validate($request, array(
                            'cs_unique' => 'unique:tblLTP_CourseSchedule,cs_unique|',
                        ));
            }
        }
                        $this->validate($request, array(
                            'course_id' => 'required|alpha_num', 
                            'term_id' => 'required|integer|',
                            'schedule_id' => 'required|array',
                        ));
        //loop for storing Code value to database
        $ingredients = [];        
        for ($i = 0; $i < count($schedule_id); $i++) {
            $ingredients[] = new  CourseSchedule([
                'L'=> $request->L,
                'Te_Code_New' => $course_id,
                'Te_Term' => $term_id,
                'schedule_id' => $schedule_id[$i],
                'cs_unique' => $course_id.'-'.$schedule_id[$i].'-'.$term_id,
                'Te_Hours' => $request->duration_id, 
                'Te_Description' => $request->format_id,
                "created_at" =>  \Carbon\Carbon::now(),
                "updated_at" =>  \Carbon\Carbon::now(),
                ]);
                    foreach ($ingredients as $data) {
                        $data->save();
                    }
        }
        //query newly saved course+schedule entries to produce needed csv extract
        $get_courses = CourseSchedule::where('Te_Code_New', $request->course_id)
            ->where('Te_Term', $term_id )->get();
        $get_courses_first = CourseSchedule::where('Te_Code_New', $request->course_id)
            ->where('Te_Term', $term_id )->first();
        $get_course_name = $get_courses_first->course->Description;
        
        $days_arr = [];
        for ($i = 0; $i < count($get_courses); $i++) {
            $days_arr[] = [$get_courses[$i]->scheduler->begin_day]; 
            $days_arr[$i] = implode('>', $days_arr[$i]);
        }
            $implode_days = implode('>', $days_arr);
            var_dump($implode_days);
        
        $times_arr = [];
        for ($i = 0; $i < count($get_courses); $i++) {
            $times_arr[] = [$get_courses[$i]->scheduler->time_combination];
            $times_arr[$i] = implode('>', $times_arr[$i]);
        }
            $implode_times = implode('>', $times_arr);
            var_dump($implode_times);

        //updateOrCreate method used to update record or insert new record
            $ingredients_csv = CsvModel::updateOrCreate(
                ['course' => $get_course_name],
                ['day' => $implode_days,
                'time' => $implode_times,
                ]);
            $ingredients_csv->save();


        $request->session()->flash('success', 'Course + Schedule saved!'); //laravel 5.4 version
        return redirect()->back();

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
        return view('courses_schedules.edit');
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
        //
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
