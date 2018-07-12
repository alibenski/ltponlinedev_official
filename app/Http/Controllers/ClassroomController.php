<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Course;
use App\User;
use App\Schedule;
use App\Classroom;
use App\CourseSchedule;
use App\Term;
use App\Room;
use App\Teachers;
use DB;
use Carbon\Carbon;
use Validator;
use Response;
use App\Day;
use App\Time;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $terms = Term::orderBy('Term_Code', 'desc')->get();
        $classrooms = CourseSchedule::orderBy('id','desc')->paginate(10);
        $rooms = Room::all();
        $teachers = Teachers::where('In_Out', '1')->get();
        $btimes = Time::pluck("Begin_Time","Begin_Time")->all();
        $etimes = Time::pluck("End_Time","End_Time")->all(); 
        return view('classrooms.index')->withClassrooms($classrooms)->withRooms($rooms)->withTeachers($teachers)->withTerms($terms)->withBtimes($btimes)->withEtimes($etimes);
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
                        'Te_Mon_Room' => $request->Te_Mon_Room,
                        'Te_Mon_BTime' => $request->Te_Mon_BTime,
                        'Te_Mon_ETime' => $request->Te_Mon_ETime,
                        'Te_Tue' => $request->Te_Tue,
                        'Te_Tue_Room' => $request->Te_Tue_Room,
                        'Te_Tue_BTime' => $request->Te_Tue_BTime,
                        'Te_Tue_ETime' => $request->Te_Tue_ETime,
                        'Te_Wed' => $request->Te_Wed,
                        'Te_Wed_Room' => $request->Te_Wed_Room,
                        'Te_Wed_BTime' => $request->Te_Wed_BTime,
                        'Te_Wed_ETime' => $request->Te_Wed_ETime,
                        'Te_Thu' => $request->Te_Thu,
                        'Te_Thu_Room' => $request->Te_Thu_Room,
                        'Te_Thu_BTime' => $request->Te_Thu_BTime,
                        'Te_Thu_ETime' => $request->Te_Thu_ETime,
                        'Te_Fri' => $request->Te_Fri,
                        'Te_Fri_Room' => $request->Te_Fri_Room,
                        'Te_Fri_BTime' => $request->Te_Fri_BTime,
                        'Te_Fri_ETime' => $request->Te_Fri_ETime,
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
            $validator = Validator::make(Input::all(), $this->rules);
            if ($validator->fails()) {
                return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
            } else {
                $classroom = Classroom::findOrFail($id);
                $classroom->room_id = $request->room_id;
                $classroom->Tch_ID = $request->teacher_id;
                $classroom->save();
                return response()->json($classroom);
            }

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
