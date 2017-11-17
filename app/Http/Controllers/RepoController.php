<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Language;
use App\Course;
use App\User;
use App\Repo;
use App\Term;
use App\Classroom;
use App\Schedule;
use App\Preenrolment;
use Session;
use DB;

class RepoController extends Controller
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
        $repos = Repo::orderBy('INDEXID', 'asc')->paginate(15);

        $terms = Term::all(['Term_Code', 'Term_Name']);

        return view('form.index')->withRepos($repos)->withTerms($terms);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {  
        //make collection values available
        $courses = Course::all();
        //get values directly from 'languages' table
        $languages = DB::table('languages')->pluck("name","code")->all();
        //get latest semester/term
        $terms = DB::table('LTP_Terms')->orderBy('Term_Code', 'DESC')->first();
        //define user variable as User collection
        $user = Auth::user();
        //define user index number for query 
        $current_user = Auth::user()->indexno;
        //using DB method to query latest CodeIndexID of current_user
        $repos = Repo::orderBy('Term', 'desc')
            ->where('INDEXID', $current_user)->value('CodeIndexID');
        //not using DB method to get latest language course of current_user
        $repos_lang = Repo::orderBy('Term', 'desc')
            ->where('INDEXID', $current_user)->first();

   

        return view('form.myform')->withCourses($courses)->withLanguages($languages)->withTerms($terms)->withRepos($repos)->withRepos_lang($repos_lang)->withUser($user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $index_id = $request->input('index_id');
        $language_id = $request->input('L'); 
        $course_id = $request->input('course_id');
        $term_id = $request->input('term_id');
        //$schedule_id is an array 
        $schedule_id = $request->input('schedule_id');
        $uniquecode = $request->input('CodeIndexID');
        $codex = [];     
        //concatenate (implode) Code input before validation   
        //check if $code has no input
        if ( empty( $uniquecode ) ) {
            //loop based on $room_id count and store in $codex array
            for ($i=0; $i < count($schedule_id); $i++) { 
                $codex[] = array( $course_id,$schedule_id[$i],$term_id,$index_id );
                //implode array elements and pass imploded string value to $codex array as element
                $codex[$i] = implode('/', $codex[$i]);
                //for each $codex array element stored, loop array merge method
                //and output each array element to a string via $request->Code

                foreach ($codex as $value) {
                    $request->merge( [ 'CodeIndexID' => $value ] );
                }
                        var_dump($request->CodeIndexID);
                        $this->validate($request, array(
                            'CodeIndexID' => 'unique:tblLTP_Enrolment,CodeIndexID|',
                            'term_id' => 'required|',
                            'schedule_id' => 'required|',
                            'course_id' => 'required|',       
                        ));
            }                              
        }

        //loop for storing Code value to database
        $ingredients = [];        
        for ($i = 0; $i < count($schedule_id); $i++) {
            $ingredients[] = new  Preenrolment([
                'CodeIndexID' => $course_id.'/'.$schedule_id[$i].'/'.$term_id.'/'.$index_id,
                'Code' => $course_id.'/'.$schedule_id[$i].'/'.$term_id,
                'L' => $language_id,
                'Te_Code' => $course_id,
                'Term' => $term_id,
                'INDEXID' => $index_id,
                "created_at" =>  \Carbon\Carbon::now(),
                "updated_at" =>  \Carbon\Carbon::now(),
                ]);
                    foreach ($ingredients as $data) {
                        $data->save();
                    }
        }

        $request->session()->flash('success', 'Entry has been saved!'); //laravel 5.4 version
        return redirect()->route('home');
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
        //
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

    /**
     * Show the application selectAjax.
     *
     * @return \Illuminate\Http\response
     */
    public function selectAjax(Request $request)
    {
        if($request->ajax()){
            //$courses = DB::table('courses')->where('language_id',$request->language_id)->pluck("name","id")->all();
            $select_courses = DB::table('LTP_CR_LIST')
            ->where('L', $request->L)
            ->pluck("Description","Te_Code")
            ->all();
            
            $data = view('ajax-select',compact('select_courses'))->render();
            return response()->json(['options'=>$data]);
        }
    }

    public function selectAjax2(Request $request)
    {
        if($request->ajax()){

            //$select_schedules = DB::table('LTP_TEVENTCur')
            $select_schedules = Classroom::where('Te_Code', $request->course_id)
            ->where(function($q){ 
                $latest_term = DB::table('LTP_Terms')->orderBy('Term_Code', 'DESC')->value('Term_Code');
                $q->where('Te_Term', $latest_term );
            })

            //Eager Load scheduler function and pluck using "dot" 
            ->with('scheduler')->get()->pluck('scheduler.name', 'schedule_id');

            $data = view('ajax-select2',compact('select_schedules'))->render();
            return response()->json(['options'=>$data]);
        }
    }
}
