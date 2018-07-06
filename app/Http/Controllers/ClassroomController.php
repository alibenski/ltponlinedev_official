<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Course;
use App\User;
use App\Schedule;
use App\Classroom;
use App\Term;
use App\Room;
use App\Teachers;
use DB;
use Carbon\Carbon;
use Validator;
use Response;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $classrooms = Classroom::orderBy('id','desc')->paginate(10);
        $rooms = Room::all();
        $teachers = Teachers::where('In_Out', '1')->get();
        return view('classrooms.index')->withClassrooms($classrooms)->withRooms($rooms)->withTeachers($teachers);
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $course_id = $request->input('course_id');
        $schedule_id = $request->input('schedule_id');
        $term_id = $request->input('term_id');
        $room_id = $request->input('room_id');
        $code = $request->input('Code');
        $codex = [];     
        //concatenate (implode) Code input before validation   
        //check if $code has no input
        if ( empty( $code ) ) {
            //loop based on $room_id count and store in $codex array
            for ($i=0; $i < count($room_id); $i++) { 
                $codex[] = array($course_id, $schedule_id, $term_id, $room_id[$i]);
                //implode array elements and pass imploded string value to $codex array as element
                $codex[$i] = implode('-', $codex[$i]);
                //for each $codex array element stored, loop array merge method
                //and output each array element to a string via $request->Code
                foreach ($codex as $value) {
                    $request->merge( [ 'Code' => $value ] );
                }
                        var_dump($request->Code);
                        $this->validate($request, array(
                            'Code' => 'unique:LTP_TEVENTCur,Code|',
                        ));
            }
        }
                        $this->validate($request, array(
                            'term_id' => 'required|',
                            'schedule_id' => 'required|',
                            'course_id' => 'required|',
                            'room_id' => 'required|',   
                        ));
        //loop for storing Code value to database
        $ingredients = [];        
        for ($i = 0; $i < count($room_id); $i++) {
            $ingredients[] = new  Classroom([
                'schedule_id' => $schedule_id,
                'Te_Code' => $course_id,
                'Te_Term' => $term_id,
                'Code' => $course_id.'-'.$schedule_id.'-'.$term_id.'-'.$room_id[$i],
                "created_at" =>  \Carbon\Carbon::now(),
                "updated_at" =>  \Carbon\Carbon::now(),
                ]);
                    foreach ($ingredients as $data) {
                        $data->save();
                    }
        }
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
    
    protected $rules =
    [
        'room_id' => 'required|',
    ];

    public function update(Request $request, $id)
    {
            $validator = Validator::make(Input::all(), $this->rules);
            if ($validator->fails()) {
                return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
            } else {
                $classroom = Classroom::findOrFail($id);
                $classroom->room_id = $request->room_id;
                $classroom->Tch_ID = $request->teacher_id;
                // $classroom->save();
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
