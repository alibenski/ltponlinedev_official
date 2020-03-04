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
        $languages = DB::table('languages')->pluck("name", "code")->all();
        $classrooms = new CourseSchedule;
        // $currentQueries = \Request::query();
        $queries = [];

        $columns = [
            'L', 'Te_Code_New'
        ];

        foreach ($columns as $column) {
            if (\Request::has($column)) {
                $classrooms = $classrooms->where($column, \Request::input($column));
                $queries[$column] = \Request::input($column);
            }
        }

        if (Session::has('Term')) {
            $classrooms = $classrooms->where('Te_Term', Session::get('Term'));
            $queries['Term'] = Session::get('Term');
        }
        $classrooms = $classrooms->orderBy('L', 'asc')->get();
        // ->paginate(20)->appends($queries);

        $rooms = Room::all();
        $teachers = Teachers::where('In_Out', '1')->get();
        $btimes = Time::pluck("Begin_Time", "Begin_Time")->all();
        $etimes = Time::pluck("End_Time", "End_Time")->all();
        return view('classrooms.index', compact('classrooms', 'rooms', 'teachers', 'btimes', 'etimes', 'languages'));
    }

    public function indexCalendar()
    {
        $languages = DB::table('languages')->pluck("name", "code")->all();

        return view('classrooms.index-calendar', compact('languages'));
    }

    public function viewCalendar(Request $request)
    {
        if ($request->ajax()) {
            $language = $request->L;
            $term = $request->term;
            $classrooms = Classroom::orderBy('id', 'desc')->where('Te_Term', $term)
                ->where('L', $language)
                ->get();
    
            $arrayRooms = [];
            foreach ($classrooms as $key => $value) {
                array_push(
                    $arrayRooms,
                    $value->Te_Mon_Room,
                    $value->Te_Tue_Room,
                    $value->Te_Wed_Room,
                    $value->Te_Thu_Room,
                    $value->Te_Fri_Room
                );
            }
            $arrayRooms = array_unique($arrayRooms);
            $arrayRooms = array_filter($arrayRooms);
            $arrayRooms = array_values($arrayRooms);
    
            $rooms = Room::whereIn('id', $arrayRooms)->orderBy('Rl_Room', 'asc')->get();
            
            $data = view('classrooms.view-calendar', compact('rooms', 'term', 'language'))->render();

            return response()->json(['options' => $data]);
        }
    }

    public function ajaxIndexCalendar(Request $request)
    {
        if ($request->ajax()) {
            $language = $request->L;
            $term = $request->term;
            $classrooms = Classroom::orderBy('Te_Term', 'desc')->where('Te_Term', $term)
                ->where('L', $language)
                ->with('course')
                ->with('teachers')
                ->get();

            $array = [];
            $arrayRecurrence = [];
            $arrayRooms = [];
            foreach ($classrooms as $value) {
                array_push(
                    $arrayRooms,
                    $value->Te_Mon_Room,
                    $value->Te_Tue_Room,
                    $value->Te_Wed_Room,
                    $value->Te_Thu_Room,
                    $value->Te_Fri_Room
                );

                $day1 = $value->scheduler->day_1;
                if ($day1 != null) {
                    $day1 = $day1 - 1;
                    array_push($arrayRecurrence, $day1);
                }
                $day2 = $value->scheduler->day_2;
                if ($day2 != null) {
                    $day2 = $day2 - 1;
                    array_push($arrayRecurrence, $day2);
                }
                $day3 = $value->scheduler->day_3;
                if ($day3 != null) {
                    $day3 = $day3 - 1;
                    array_push($arrayRecurrence, $day3);
                }
                $day4 = $value->scheduler->day_4;
                if ($day4 != null) {
                    $day4 = $day4 - 1;
                    array_push($arrayRecurrence, $day4);
                }
                $day5 = $value->scheduler->day_5;
                if ($day5 != null) {
                    $day5 = $day5 - 1;
                    array_push($arrayRecurrence, $day5);
                }

                $array[] = [
                    'id' => $value->id,
                    'title' => $value->course->Description,
                    'start' => Carbon::parse($value->terms->Term_Begin)->toDateString(),
                    'end' => Carbon::parse($value->terms->Term_End)->toDateString(),
                    'startTime' => Carbon::parse($value->scheduler->begin_time)->toTimeString(),
                    'endTime' => Carbon::parse($value->scheduler->end_time)->toTimeString(),
                    'daysOfWeek' => $arrayRecurrence,
                    'startRecur' => Carbon::parse($value->terms->Term_Begin)->toDateString(),
                    'endRecur' => Carbon::parse($value->terms->Term_End)->toDateString(),
                    'teacher' => $value->teachers->Tch_Name,
                    'roomMon' => $value->Te_Mon_Room,
                    'roomTue' => $value->Te_Tue_Room,
                    'roomWed' => $value->Te_Wed_Room,
                    'roomThu' => $value->Te_Thu_Room,
                    'roomFri' => $value->Te_Fri_Room
                ];
                $arrayRecurrence = [];
            }

            $arrayRooms = array_unique($arrayRooms);
            $arrayRooms = array_filter($arrayRooms);
            $arrayRooms = array_values($arrayRooms);

            $array2 = [];
            foreach ($arrayRooms as $v) {
                foreach ($array as $value1) {
                    if ($v == $value1['roomMon']) {
                        $array2[] = ['room' => $v, 'class' => $value1];
                    }
                    if ($v == $value1['roomTue']) {
                        $array2[] = ['room' => $v, 'class' => $value1];
                    }
                    if ($v == $value1['roomWed']) {
                        $array2[] = ['room' => $v, 'class' => $value1];
                    }
                    if ($v == $value1['roomThu']) {
                        $array2[] = ['room' => $v, 'class' => $value1];
                    }
                    if ($v == $value1['roomFri']) {
                        $array2[] = ['room' => $v, 'class' => $value1];
                    }
                }
            }

            $array2 = array_unique($array2, SORT_REGULAR);

            $data = $array2;

            return response()->json(['data' => $data]);
        }
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
        $languages = DB::table('languages')->pluck("name", "code")->all();
        $schedules = Schedule::pluck("name", "id")->all();
        //get latest semester/term
        $terms = Term::orderBy('Term_Code', 'DESC')->first();
        $rooms = Room::pluck("Rl_Room", "Rl_Room")->all();
        return view('classrooms.create', compact('courses', 'languages', 'schedules', 'terms', 'rooms'));
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
        if (empty($code)) {
            //loop based on $sectionNo count and store in $codex array
            for ($i = 0; $i < count($sectionNo); $i++) {
                $codex[] = array($cs_unique, $sectionNo[$i]);
                //implode array elements and pass imploded string value to $codex array as element
                $codex[$i] = implode('-', $codex[$i]);
                //for each $codex array element stored, loop array merge method
                //and output each array element to a string via $request->Code
                foreach ($codex as $value) {
                    $request->merge(['Code' => $value]);
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
        } else {
            //loop for storing Code value to database
            $ingredients = [];
            for ($i = 0; $i < count($sectionNo); $i++) {
                $ingredients[] = new  Classroom([
                    'Code' => $cs_unique . '-' . $sectionNo[$i],
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
        $btimes = Time::pluck("Begin_Time", "Begin_Time")->all();
        $etimes = Time::pluck("End_Time", "End_Time")->all();

        $students = Repo::where('CodeClass', $classroom->Code)->get();
        $schedules = Schedule::orderBy('name', 'asc')->pluck("name", "id")->chunk(5)->all();

        return view('classrooms.edit', compact('schedules', 'students', 'classroom', 'rooms', 'teachers', 'terms', 'btimes', 'etimes'));
    }

    /**
     * Get the days associated to schedule 
     * 
     * @param  Request $request 
     * @return json           
     */
    public function getScheduleDays(Request $request)
    {
        if ($request->ajax()) {
            $scheduleDays = Schedule::where('id', $request->id)->first();
            $rooms = Room::all();
            $data = view('ajax-get-schedule-days', compact('scheduleDays', 'rooms'))->render();
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
        if (!$fliteredInput) {
            $request->session()->flash('warning', 'No input. No changes made!');
            return redirect()->back();
        }
        $classroom = Classroom::findOrFail($id);

        // case 1: change assigned room(s) only
        // if existing schedule == requested schedule, then just change assigned room(s)
        if ($classroom->schedule_id == $request->schedule_id) {
            $result = $this->updateRoomOnly($request, $classroom);
            $classCode = $result[1];

            $request->session()->flash('success', 'Room assignments updated.');
            return redirect()->back();
        }

        $request->session()->flash('error', 'No changes made!');
        return redirect()->back();

        // case 2: if existing schedule != requested schedule

        // check if there are PASH records for the term (if the batch has ran for the selected term)
        $checkPashTerm = Repo::where('Term', $classroom->Te_Term)->first();

        // update CourseSchedule records first to new schedule
        $courseSchedRecord = CourseSchedule::where('cs_unique', $classroom->cs_unique)->get();

        $requestedCSUnique = $classroom->Te_Code_New . '-' . $request->schedule_id . '-' . $classroom->Te_Term;
        // check if requested cs_unique already exists in CourseSchedule table
        $checkCourseSchedDupe = CourseSchedule::where('cs_unique', $requestedCSUnique)->first();

        // if batch ran, then update PASH and Schedule and CourseSchedule Models...
        if ($checkPashTerm) {
            // if cs_unique does not exist in CourseSchedule table, then do this...
            if (!$checkCourseSchedDupe) {
                $this->changeCourseScheduleNotSection($request, $classroom, $courseSchedRecord);
                $request->session()->flash('success', 'Changes have been saved!');
                return redirect()->back();
            } else {
                // else if cs_unique exists in CourseSchedule table, then do this...

                // check that there is only 1 section created for $classroom


                // $result = $this->changeCourseScheduleAddSection($request, $classroom, $courseSchedRecord, $checkCourseSchedDupe);
                // $classCode = $result[1];

                // $request->session()->flash('success', 'The classroom code has been changed to: '.$classCode->Code.' [ '.$classCode->course->Description.' '.$classCode->scheduler->name.' ] and the students have been successfully transferred.');

                $request->session()->flash('warning', 'Sorry, this cannot be done at the moment. Please manually create the class section. No changes made.');
                return redirect()->back();
            }
        } else {

            // if batch has not ran, then only update Classroom and CourseSchedule Models...                           
            $this->updateWithoutPASH($request, $classroom, $courseSchedRecord);
            $request->session()->flash('success', 'Changes have been saved to Classroom and CourseSchedule models!');
            return redirect()->back();
        }

        $request->session()->flash('warning', 'No changes made!');
        return redirect()->back();
    }

    public function updateRoomOnly($request, $classroom)
    {
        // update Classroom Model parameters

        // set day, time, and room fields to null 
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
        $result = [$request, $classroom];

        return $result;
    }

    /**
     * Update Classroom and CourseSchedule Models when changes are done BEFORE the batch run
     * 
     * @param  \Illuminate\Http\Request $request           
     * @param  Object $classroom         
     * @param  Object $courseSchedRecord 
     * @return \Illuminate\Http\Response
     */
    public function updateWithoutPASH($request, $classroom, $courseSchedRecord)
    {
        foreach ($courseSchedRecord as $record) {
            // $record->Tch_ID = $request->Tch_ID;
            $record->schedule_id = $request->schedule_id;
            $record->cs_unique = $classroom->Te_Code_New . '-' . $request->schedule_id . '-' . $classroom->Te_Term;

            $record->save();
        }

        // update Classroom Model parameters
        // $classroom->Tch_ID = $request->Tch_ID;
        $classroom->schedule_id = $request->schedule_id;
        $classroom->cs_unique = $classroom->Te_Code_New . '-' . $request->schedule_id . '-' . $classroom->Te_Term;
        $classroom->Code = $classroom->Te_Code_New . '-' . $request->schedule_id . '-' . $classroom->Te_Term . '-' . $classroom->sectionNo;

        // set day, time, and room fields to null 
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

        return $request;
    }

    /**
     * Update of Classroom schedule parameters cascades to PASH and CourseSchedule Models 
     * when changes are done AFTER the batch run and created section is only 1 
     * 
     * @param  \Illuminate\Http\Request $request           
     * @param  Object $classroom         
     * @param  Object $courseSchedRecord 
     * @return \Illuminate\Http\Response
     */
    public function changeCourseScheduleNotSection($request, $classroom, $courseSchedRecord)
    {
        // Editing Classroom schedule parameters cascades to PASH and CourseSchedule Models (no duplicate or existing cs_unique and section field data) e.g. LPE Written and LPE Oral switched schedules - Florence case
        foreach ($courseSchedRecord as $record) {
            // $record->Tch_ID = $request->Tch_ID;
            $record->schedule_id = $request->schedule_id;
            $record->cs_unique = $classroom->Te_Code_New . '-' . $request->schedule_id . '-' . $classroom->Te_Term;

            $record->save();
        }
        // update PASH records first to new schedule
        $pash_record = Repo::where('CodeClass', $classroom->Code)->get();
        foreach ($pash_record as $value) {
            $value->schedule_id = $request->schedule_id;
            $value->Code = $classroom->Te_Code_New . '-' . $request->schedule_id . '-' . $classroom->Te_Term;
            $value->CodeClass = $classroom->Te_Code_New . '-' . $request->schedule_id . '-' . $classroom->Te_Term . '-' . $classroom->sectionNo;
            $value->CodeIndexID =  $classroom->Te_Code_New . '-' . $request->schedule_id . '-' . $classroom->Te_Term . '-' . $value->INDEXID;
            $value->CodeIndexIDClass =  $classroom->Te_Code_New . '-' . $request->schedule_id . '-' . $classroom->Te_Term . '-' . $classroom->sectionNo . '-' . $value->INDEXID;
            $value->save();
        }

        // update Classroom Model parameters
        // $classroom->Tch_ID = $request->Tch_ID;
        $classroom->schedule_id = $request->schedule_id;
        $classroom->cs_unique = $classroom->Te_Code_New . '-' . $request->schedule_id . '-' . $classroom->Te_Term;
        $classroom->Code = $classroom->Te_Code_New . '-' . $request->schedule_id . '-' . $classroom->Te_Term . '-' . $classroom->sectionNo;

        // set day, time, and room fields to null 
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

        return $request;
    }

    /**
     * Update of Classroom schedule parameters cascades to PASH and CourseSchedule Models with additional section 
     * when changes are done AFTER the batch run
     * 
     * @param  \Illuminate\Http\Request $request           
     * @param  Object $classroom         
     * @param  Object $courseSchedRecord 
     * @return \Illuminate\Http\Response
     */
    public function changeCourseScheduleAddSection($request, $classroom, $courseSchedRecord, $checkCourseSchedDupe)
    {
        // Editing Classroom schedule parameters cascades to PASH Models (Not CourseSchedule) with additional section because of duplicate cs_unique and section field data - Fabienne case
        $csUniqueCode = $checkCourseSchedDupe->cs_unique;

        $latestClassWithCSUniqueCode = Classroom::where('cs_unique', $csUniqueCode)->orderBy('sectionNo', 'desc')->first();
        $incrementSection = $latestClassWithCSUniqueCode->sectionNo;
        $incrementSection++;

        // update PASH records first to new schedule and section number
        $pash_record = Repo::where('CodeClass', $classroom->Code)->get();
        foreach ($pash_record as $value) {
            $value->schedule_id = $request->schedule_id;
            $value->Code = $classroom->Te_Code_New . '-' . $request->schedule_id . '-' . $classroom->Te_Term;
            $value->CodeClass = $classroom->Te_Code_New . '-' . $request->schedule_id . '-' . $classroom->Te_Term . '-' . $incrementSection;
            $value->CodeIndexID =  $classroom->Te_Code_New . '-' . $request->schedule_id . '-' . $classroom->Te_Term . '-' . $value->INDEXID;
            $value->CodeIndexIDClass =  $classroom->Te_Code_New . '-' . $request->schedule_id . '-' . $classroom->Te_Term . '-' . $incrementSection . '-' . $value->INDEXID;
            $value->save();
        }

        // update Classroom Model parameters
        $classroom->schedule_id = $request->schedule_id;
        $classroom->Tch_ID = $request->Tch_ID;
        $classroom->sectionNo = $incrementSection;
        $classroom->cs_unique = $classroom->Te_Code_New . '-' . $request->schedule_id . '-' . $classroom->Te_Term;
        $classroom->Code = $classroom->Te_Code_New . '-' . $request->schedule_id . '-' . $classroom->Te_Term . '-' . $incrementSection;

        // set day, time, and room fields to null 
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
        $result = [$request, $classroom];

        return $result;
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
