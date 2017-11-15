<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Course;
use App\User;
use App\Schedule;
use App\Classroom;
use App\Term;
use App\Room;
use DB;
use Carbon;

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
        
        if ( empty( $code ) ) {
            for ($i=0; $i < count($room_id); $i++) { 
            $codex[] = array($course_id, $schedule_id, $term_id, $room_id[$i]);
            $codex[$i] = implode('/', $codex[$i]);
            $request->merge( array( 'Code' => $codex[$i] ) );
            }
var_dump($request->Code);

            dd($codex);
            //$codex = preg_replace( '/\s+/m', ',', $codex );
            //$codex = explode( ',', $codex );
            
            // THIS IS KEY!
            // Replacing the old input string with
            // with an array of emails.
            $request->merge( array( 'Code' => $codex[$i] ) );
var_dump($request->Code);

            dd($request->Code);
            



        }dd($request->Code);

        $ingredients = [];
        
        for ($i = 0; $i < count($room_id); $i++) {
            $ingredients[] = new  Classroom([
                'schedule_id' => $schedule_id,
                'Te_Code' => $course_id,
                'Te_Term' => $term_id,
                'Code' => $course_id.'/'.$schedule_id.'/'.$term_id.'/'.$room_id[$i],
                "created_at" =>  \Carbon\Carbon::now(),
                "updated_at" =>  \Carbon\Carbon::now(),
          ]);
        }

        $request->session()->flash('success', 'Entry has been saved!'); //laravel 5.4 version

        return redirect()->route('classrooms.index');
        //var_dump($ingredients[0]);
        //var_dump($ingredients[1]);
        //dd($ingredients[1]);
        //Classroom::insert($ingredients);
        //DB::table('LTP_TEVENTCur')->insert($ingredients);
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
