<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Course;
use App\User;
use App\Schedule;
use App\Classroom;
use App\Term;
use DB;
use Carbon\Carbon;
use App\PlacementSchedule;
use Exception;

class PlacementScheduleController extends Controller
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
        $terms = Term::orderBy('Term_Code', 'desc')->get();

        $placement_schedule = PlacementSchedule::orderBy('term', 'desc')
            ->orderBy('language_id', 'asc')
            ->paginate(20);

        return view('placement_schedule.index', compact('placement_schedule', 'terms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $terms = Term::orderBy('Term_Code', 'desc')->get();
        $languages = DB::table('languages')->pluck("name","code")->all();

        return view('placement_schedule.create', compact('languages', 'terms'));
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
                'term' => 'required|',
                'L' => 'required|',
                'date_of_plexam' => 'required|',
                'format_id' => 'required|',
            ));

        if ($request->format_id == 1) {
            $message = [
                'required' => 'The online Placement Test Date End field is required.',
            ];
            $this->validate($request, ['date_of_plexam_end' => 'required'], $message);
        }

        $filteredDateArray = [];
        $filteredDateArray = array_filter($request->date_of_plexam);
        if (empty($filteredDateArray)) {
            return redirect()->back()->with('error','No dates submitted.');
        }
        $filteredDateArray = array_values($filteredDateArray);
        try{
            //loop for storing data to database
            $ingredients = [];        
            for ($i = 0; $i < count($filteredDateArray); $i++) {
                $ingredients[] = new  PlacementSchedule([
                    'term' => $request->term,
                    'language_id' => $request->L,
                    'date_of_plexam' => $filteredDateArray[$i],
                    'date_of_plexam_end' => $request->date_of_plexam_end,
                    'is_online' => $request->format_id,
                    ]); 
                        foreach ($ingredients as $data) {
                            $data->save();
                        }
            }
            
            $request->session()->flash('success', 'Entry has been saved!');

            return redirect()->route('placement-schedule.index');
        } catch(Exception $e) {

            return redirect()->back()->with('error','placement test schedule already exists');
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
