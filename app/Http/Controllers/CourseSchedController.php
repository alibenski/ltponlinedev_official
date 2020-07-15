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
use Illuminate\Support\Facades\Auth;
use Session;

class CourseSchedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $term = Session::get('Term');
        $course_schedule = CourseSchedule::orderBy('L', 'asc')->where('Te_Term', $term)->get();

        return view('courses_schedules.index', compact('course_schedule', 'term'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $courses = Course::all();
        $languages = Language::pluck("name", "code")->all();
        $schedules = Schedule::pluck("name", "id")->all();
        $terms = Term::orderBy('Term_Code', 'desc')->get();

        $format = DB::table('tblLTP_Course_Format')->pluck("format_name_en", "id")->all();
        $duration = DB::table('tblLTP_Course_Duration')->pluck("duration_name_en", "id")->all();
        $price = DB::table('tblLTP_Course_Price')->pluck("price", "id")->all();
        $teachers = Teachers::where('In_Out', '1')->get();
        $rooms = Room::all();

        return view('courses_schedules.create', compact('courses', 'languages', 'schedules', 'terms', 'format', 'duration', 'teachers', 'rooms', 'price'));
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
            'course_id' => 'required|alpha_num',
            'term_id' => 'required|integer|',
            'schedule_id' => 'required|array',
            'Tch_ID' => 'required|array',
            'room_id' => 'required|array',
        ));
        
        $course_id = $request->course_id;
        $term_id = $request->term_id;
        $schedule_id = $request->schedule_id;
        $Tch_ID = $request->Tch_ID;
        $room_id = $request->room_id;
        $cs_unique = $request->cs_unique;
        $codex = [];
        //concatenate (implode) Code input before validation   
        //check if $code has no input
        if (empty($code)) {
            //loop based on $room_id count and store in $codex array
            for ($i = 0; $i < count($schedule_id); $i++) {
                $codex[] = array($course_id, $schedule_id[$i], $term_id);
                //implode array elements and pass imploded string value to $codex array as element
                $codex[$i] = implode('-', $codex[$i]);
                //for each $codex array element stored, loop array merge method
                //and output each array element to a string via $request->Code
                foreach ($codex as $value) {
                    $request->merge(['cs_unique' => $value]);
                }
                var_dump($request->cs_unique);
                $this->validate($request, array(
                    'cs_unique' => 'unique:tblLTP_CourseSchedule,cs_unique|',
                ));
            }
        }
        
        //loop for storing Code value to database
        $ingredients = [];
        for ($i = 0; $i < count($schedule_id); $i++) {
            $ingredients[] = new  CourseSchedule([
                'L' => $request->L,
                'Te_Code_New' => $course_id,
                'Te_Term' => $term_id,
                'schedule_id' => $schedule_id[$i],
                'Tch_ID' => $Tch_ID[$i],
                'room_id' => $room_id[$i],
                'cs_unique' => $course_id . '-' . $schedule_id[$i] . '-' . $term_id,
                'Te_Hours' => $request->duration_id,
                'Te_Description' => $request->format_id,
                'Te_Price' => $request->price_id,
                'created_by' => Auth::user()->id,
                'created_at' =>  \Carbon\Carbon::now(),
                'updated_at' =>  \Carbon\Carbon::now(),
            ]);
            foreach ($ingredients as $data) {
                $data->save();
            }

            // fetch and create classroom according to the newly created record(s)  
            $unique_key = $course_id . '-' . $schedule_id[$i] . '-' . $term_id;
            $new_record = CourseSchedule::where('cs_unique', $unique_key);
            
            $record_name = $new_record->first()->course->Description;
            $new_record->update(['name' => 'LTP - '.$record_name.' - '.$course_id]);

            $this->saveClassRoom($new_record->get());
        }

        $request->session()->flash('success', 'Course + Schedule saved!'); 
        return redirect()->back();
    }

    public function saveClassRoom($new_record)
    {
        foreach ($new_record as $value) {
            $new_class = new Classroom;
            $new_class->Code = $value->Te_Code_New . '-' . $value->schedule_id . '-' . $value->Te_Term . '-1';
            $new_class->Te_Term = $value->Te_Term;
            $new_class->cs_unique = $value->cs_unique;
            $new_class->L = $value->L;
            $new_class->Te_Code_New = $value->Te_Code_New;
            $new_class->schedule_id = $value->schedule_id;
            $new_class->sectionNo = 1;
            $new_class->Tch_ID = $value->Tch_ID;

            $schedule_fields = Schedule::find($value->schedule_id);
            if (isset($schedule_fields->day_1)) {
                $new_class->Te_Mon = 2;
                $new_class->Te_Mon_BTime = $schedule_fields->begin_time;
                $new_class->Te_Mon_ETime = $schedule_fields->end_time;
                $new_class->Te_Mon_Room = $value->room_id;
            }
            if (isset($schedule_fields->day_2)) {
                $new_class->Te_Tue = 3;
                $new_class->Te_Tue_BTime = $schedule_fields->begin_time;
                $new_class->Te_Tue_ETime = $schedule_fields->end_time;
                $new_class->Te_Tue_Room = $value->room_id;
            }
            if (isset($schedule_fields->day_3)) {
                $new_class->Te_Wed = 4;
                $new_class->Te_Wed_BTime = $schedule_fields->begin_time;
                $new_class->Te_Wed_ETime = $schedule_fields->end_time;
                $new_class->Te_Wed_Room = $value->room_id;
            }
            if (isset($schedule_fields->day_4)) {
                $new_class->Te_Thu = 5;
                $new_class->Te_Thu_BTime = $schedule_fields->begin_time;
                $new_class->Te_Thu_ETime = $schedule_fields->end_time;
                $new_class->Te_Thu_Room = $value->room_id;
            }
            if (isset($schedule_fields->day_5)) {
                $new_class->Te_Fri = 6;
                $new_class->Te_Fri_BTime = $schedule_fields->begin_time;
                $new_class->Te_Fri_ETime = $schedule_fields->end_time;
                $new_class->Te_Fri_Room = $value->room_id;
            }
            $new_class->save();
        }
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
        $course_schedule = CourseSchedule::find($id);
        $languages = Language::pluck("name", "code")->all();
        $schedules = Schedule::pluck("name", "id")->all();
        $terms = Term::orderBy('Term_Code', 'desc')->get();

        $format = DB::table('tblLTP_Course_Format')->pluck("format_name_en", "id")->all();
        $duration = DB::table('tblLTP_Course_Duration')->pluck("duration_name_en", "id")->all();
        $teachers = Teachers::where('In_Out', '1')->get();
        $rooms = Room::all();

        return view('courses_schedules.edit', compact('course_schedule', 'languages', 'schedules', 'teachers', 'rooms'));
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
        $classroom = Classroom::where('cs_unique', $request->cs_unique)->first();

        $course_schedule = CourseSchedule::find($id);


        dd($classroom, $course_schedule);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $course_schedule = CourseSchedule::find($id);
        
        $classrooms = Classroom::orderBy('id', 'desc')->where('cs_unique', $course_schedule->cs_unique)->get();
        foreach ($classrooms as $classroom) {
            $classroom->Code = $classroom->Code.'_del_'.$classroom->id;
            $classroom->deleted_by = Auth::user()->id;
            $classroom->save();
            $classroom->delete();
        }
        
        $course_schedule->cs_unique = $course_schedule->cs_unique.'_del_'.$course_schedule->id;
        $course_schedule->deleted_by = Auth::user()->id;
        $course_schedule->save();
        $course_schedule->delete();

        $request->session()->flash('warning', 'Record deleted!');
        return redirect()->back();
    }
}
