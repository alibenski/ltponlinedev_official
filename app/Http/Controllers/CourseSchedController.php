<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Course;
use App\Language;
use App\User;
use App\Schedule;
use App\Classroom;
use App\CourseSchedule;
use App\Term;
use App\Room;
use DB;
use Carbon\Carbon;

class CourseSchedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get current year and date
        $now_date = Carbon::now()->toDateString();
        $now_year = Carbon::now()->year;

        //query the current term based on year and Term_End column is greater than today's date
        //whereYear('Term_End', $now_year)  
        $terms = Term::orderBy('Term_Code', 'desc')
                        ->whereDate('Term_End', '>=', $now_date)
                        ->get()->min();
        //query the next term based Term_Begin column is greater than today's date and then get min
        $next_term = Term::orderBy('Term_Code', 'desc')
                        ->where('Term_Code', '=', $terms->Term_Next)->get()->min();

        $course_schedule = Classroom::orderBy('Te_Term', 'DESC')->where('Te_Term', $next_term->Term_Code)->paginate(20);

        return view('courses_schedules.index')->withCourse_schedule($course_schedule)->withTerms($terms)->withNext_term($next_term);
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
        //dd($next_season);


        return view('courses_schedules.create')->withCourses($courses)->withLanguages($languages)->withSchedules($schedules)->withTerms($terms)->withFormat($format)->withDuration($duration);
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
        $term_id = $request->term;
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
                            'cs_unique' => 'unique:LTP_TEVENTCur,cs_unique|',
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
            $ingredients[] = new  Classroom([
                'Te_Code_New' => $course_id,
                'Te_Term' => $term_id,
                'schedule_id' => $schedule_id[$i],
                'cs_unique' => $course_id.'-'.$term_id.'-'.$schedule_id[$i],
                "created_at" =>  \Carbon\Carbon::now(),
                "updated_at" =>  \Carbon\Carbon::now(),
                ]);
                    foreach ($ingredients as $data) {
                        $data->save();
                    }
        }
        //query newly saved course+schedule entries to produce needed csv extract
        $get_courses = Classroom::where('Te_Code_New', $request->course_id)
            ->where('Te_Term', $term_id )->get();
        $get_courses_first = Classroom::where('Te_Code_New', $request->course_id)
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
            $ingredients_csv = CourseSchedule::updateOrCreate(
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
