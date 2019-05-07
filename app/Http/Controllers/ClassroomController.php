<?php

namespace App\Http\Controllers;

use App\Classroom;
use App\Course;
use App\CourseSchedule;
use App\Day;
use App\Repo;
use App\Room;
use App\Schedule;
use App\Teachers;
use App\Term;
use App\Time;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Response;
use Validator;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $languages = DB::table('languages')->pluck("name","code")->all();
        $classrooms = new CourseSchedule;
        // $currentQueries = \Request::query();
        $queries = [];

        $columns = [
            'L', 'Te_Code_New'
        ];

        foreach ($columns as $column) {
            if (\Request::has($column)) {
                $classrooms = $classrooms->where($column, \Request::input($column) );
                $queries[$column] = \Request::input($column);
            }

        } 

            if (Session::has('Term')) {
                    $classrooms = $classrooms->where('Te_Term', Session::get('Term') );
                    $queries['Term'] = Session::get('Term');
            }
        $classrooms = $classrooms->orderBy('id','asc')->paginate(10)->appends($queries);

        $rooms = Room::all();
        $teachers = Teachers::where('In_Out', '1')->get();
        $btimes = Time::pluck("Begin_Time","Begin_Time")->all();
        $etimes = Time::pluck("End_Time","End_Time")->all(); 
        return view('classrooms.index')->withClassrooms($classrooms)->withRooms($rooms)->withTeachers($teachers)->withBtimes($btimes)->withEtimes($etimes)->withLanguages($languages);
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
        $rooms = Room::pluck("Rl_Room","Rl_Room")->all();
        return view('classrooms.create')->withCourses($courses)->withLanguages($languages)->withSchedules($schedules)->withTerms($terms)->withRooms($rooms);
    }
    
    protected $rules =
    [
        'sectionNo' => 'required|',
        'Code' => 'unique:LTP_TEVENTCur,Code|',
    ];
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $tecode = $request->input('tecode');
        $L = $request->input('L');
        $cs_unique = $request->input('cs_unique');
        $schedule_id = $request->input('schedule_id');
        $sectionNo = $request->input('sectionNo');
        $teacher_id = $request->input('teacher_id');
        $term_id = $request->input('term_id');
        $sectionNo = $request->input('sectionNo');
        $code = $request->input('Code');
        $codex = [];  

        //concatenate (implode) Code input before validation   
        //check if $code has no input
        if ( empty( $code ) ) {
            //loop based on $sectionNo count and store in $codex array
            for ($i=0; $i < count($sectionNo); $i++) { 
                $codex[] = array($cs_unique, $sectionNo[$i]);
                //implode array elements and pass imploded string value to $codex array as element
                $codex[$i] = implode('-', $codex[$i]);
                //for each $codex array element stored, loop array merge method
                //and output each array element to a string via $request->Code
                foreach ($codex as $value) {
                    $request->merge( [ 'Code' => $value ] );
                }
                        // var_dump($request->Code);
                        // $this->validate($request, array(
                        //     'Code' => 'unique:LTP_TEVENTCur,Code|',
                        // ));
            }
        }

        $validator = Validator::make(Input::all(), $this->rules);
            if ($validator->fails()) {
                return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
            } else{
                //loop for storing Code value to database
                $ingredients = [];        
                for ($i = 0; $i < count($sectionNo); $i++) {
                    $ingredients[] = new  Classroom([
                        'Code' => $cs_unique.'-'.$sectionNo[$i],
                        'Te_Term' => $term_id,
                        'cs_unique' => $cs_unique,
                        'L' => $L,
                        'Te_Code_New' => $tecode,
                        'schedule_id' => $schedule_id,
                        'sectionNo' => $sectionNo[$i],
                        'Tch_ID' => $teacher_id[$i],
                        'Te_Mon' => $request->Te_Mon,
                        'Te_Mon_Room' => $request->Te_Mon_Room[$i],
                        'Te_Mon_BTime' => $request->Te_Mon_BTime[$i],
                        'Te_Mon_ETime' => $request->Te_Mon_ETime[$i],
                        'Te_Tue' => $request->Te_Tue,
                        'Te_Tue_Room' => $request->Te_Tue_Room[$i],
                        'Te_Tue_BTime' => $request->Te_Tue_BTime[$i],
                        'Te_Tue_ETime' => $request->Te_Tue_ETime[$i],
                        'Te_Wed' => $request->Te_Wed,
                        'Te_Wed_Room' => $request->Te_Wed_Room[$i],
                        'Te_Wed_BTime' => $request->Te_Wed_BTime[$i],
                        'Te_Wed_ETime' => $request->Te_Wed_ETime[$i],
                        'Te_Thu' => $request->Te_Thu,
                        'Te_Thu_Room' => $request->Te_Thu_Room[$i],
                        'Te_Thu_BTime' => $request->Te_Thu_BTime[$i],
                        'Te_Thu_ETime' => $request->Te_Thu_ETime[$i],
                        'Te_Fri' => $request->Te_Fri,
                        'Te_Fri_Room' => $request->Te_Fri_Room[$i],
                        'Te_Fri_BTime' => $request->Te_Fri_BTime[$i],
                        'Te_Fri_ETime' => $request->Te_Fri_ETime[$i],
                        ]);
                            foreach ($ingredients as $data) {
                                $data->save();
                            }
                }
                return response()->json($request);
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
        $terms = Term::orderBy('Term_Code', 'desc')->get();
        $classroom = Classroom::where('id', $id)->first();
        $rooms = Room::all();
        $teachers = Teachers::where('In_Out', '1')->orderBy('Tch_Lastname', 'asc')->get();
        $btimes = Time::pluck("Begin_Time","Begin_Time")->all();
        $etimes = Time::pluck("End_Time","End_Time")->all(); 

        $students = Repo::where('CodeClass', $classroom->Code)->get();
        $schedules = Schedule::pluck("name","id")->chunk(5)->all();

        return view('classrooms.edit')->withSchedules($schedules)->withStudents($students)->withClassroom($classroom)->withRooms($rooms)->withTeachers($teachers)->withTerms($terms)->withBtimes($btimes)->withEtimes($etimes);
    }

    public function getScheduleDays(Request $request)
    {
        if ($request->ajax()) {
                $scheduleDays = Schedule::where('id', $request->id)->first();
                $rooms = Room::all();
                $data = view('ajax-get-schedule-days', compact('scheduleDays','rooms'))->render();
                return response()->json([$data]);
            }    
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
        $noTokenMethod = $request->except(['_token', '_method']);
        $fliteredInput = (array_filter($noTokenMethod));
            if(!$fliteredInput) {
                $request->session()->flash('warning', 'No changes made!');
            return redirect()->back();
            }
        $classroom = Classroom::findOrFail($id);

        // update PASH records first to new schedule
        $pash_record = Repo::where('CodeClass', $classroom->Code)->get();

        foreach ($pash_record as $value) {
            $value->schedule_id = $request->schedule_id;
            $value->Code = $classroom->Te_Code_New.'-'.$request->schedule_id.'-'.$classroom->Te_Term;
            $value->CodeClass = $classroom->Te_Code_New.'-'.$request->schedule_id.'-'.$classroom->Te_Term.'-'.$classroom->sectionNo;
            $value->CodeIndexID =  $classroom->Te_Code_New.'-'.$request->schedule_id.'-'.$classroom->Te_Term.'-'.$value->INDEXID;
            $value->CodeIndexIDClass =  $classroom->Te_Code_New.'-'.$request->schedule_id.'-'.$classroom->Te_Term.'-'.$classroom->sectionNo.'-'.$value->INDEXID;
            $value->save();
        }

        // udpate Classroom Model parameters
        $classroom->schedule_id = $request->schedule_id;
        $classroom->Tch_ID = $request->Tch_ID;
        $classroom->cs_unique = $classroom->Te_Code_New.'-'.$request->schedule_id.'-'.$classroom->Te_Term;
        $classroom->Code = $classroom->Te_Code_New.'-'.$request->schedule_id.'-'.$classroom->Te_Term.'-'.$classroom->sectionNo;

        $classroom->Te_Mon = null;
        $classroom->Te_Mon_BTime = null;
        $classroom->Te_Mon_ETime = null;
        $classroom->Te_Mon_Room = null;

        $classroom->Te_Tue = null;
        $classroom->Te_Tue_BTime = null;
        $classroom->Te_Tue_ETime = null;
        $classroom->Te_Tue_Room = null;

        $classroom->Te_Wed = null;
        $classroom->Te_Wed_BTime = null;
        $classroom->Te_Wed_ETime = null;
        $classroom->Te_Wed_Room = null;

        $classroom->Te_Thu = null;
        $classroom->Te_Thu_BTime = null;
        $classroom->Te_Thu_ETime = null;
        $classroom->Te_Thu_Room = null;

        $classroom->Te_Fri = null;
        $classroom->Te_Fri_BTime = null;
        $classroom->Te_Fri_ETime = null;
        $classroom->Te_Fri_Room = null;
        
        $schedule_fields = Schedule::find($request->schedule_id);
            if (isset($schedule_fields->day_1)) {
                $classroom->Te_Mon = 2;
                $classroom->Te_Mon_BTime = $schedule_fields->begin_time;
                $classroom->Te_Mon_ETime = $schedule_fields->end_time;
                $classroom->Te_Mon_Room = $request->Te_Mon_Room;
            }
            if (isset($schedule_fields->day_2)) {
                $classroom->Te_Tue = 3;
                $classroom->Te_Tue_BTime = $schedule_fields->begin_time;
                $classroom->Te_Tue_ETime = $schedule_fields->end_time;
                $classroom->Te_Tue_Room = $request->Te_Tue_Room;
            }
            if (isset($schedule_fields->day_3)) {
                $classroom->Te_Wed = 4;
                $classroom->Te_Wed_BTime = $schedule_fields->begin_time;
                $classroom->Te_Wed_ETime = $schedule_fields->end_time;
                $classroom->Te_Wed_Room = $request->Te_Wed_Room;
            }
            if (isset($schedule_fields->day_4)) {
                $classroom->Te_Thu = 5;
                $classroom->Te_Thu_BTime = $schedule_fields->begin_time;
                $classroom->Te_Thu_ETime = $schedule_fields->end_time;
                $classroom->Te_Thu_Room = $request->Te_Thu_Room;
            }
            if (isset($schedule_fields->day_5)) {
                $classroom->Te_Fri = 6;
                $classroom->Te_Fri_BTime = $schedule_fields->begin_time;
                $classroom->Te_Fri_ETime = $schedule_fields->end_time;
                $classroom->Te_Fri_Room = $request->Te_Fri_Room;
            }


        $classroom->save();
        // $classroom->update($fliteredInput);
        $request->session()->flash('success', 'Changes have been saved!');
        return redirect()->back();
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
