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
        $courses = Course::all();
        //$courses = Course::all(['id', 'name']); // selected $key => $value
        $languages = DB::table('languages')->pluck("name","code")->all();

        //get latest semester/term
        $terms = DB::table('LTP_Terms')->orderBy('Term_Code', 'DESC')->first();
        //define user variable as user collection
        $user = Auth::user();
        //define user index number for query 
        $current_user = Auth::user()->indexno;
        //using DB method to query latest CodeIndexID of current_user
        $repos = DB::table('tblLTP_Enrolment')->orderBy('Term', 'desc')
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
           $this->validate($request, array(
                //'unique_code' => 'unique|max:255',
                //'user_id' => 'integer',
                //'name' => 'required|email',
                //'language_id' => 'required|integer',
               // 'course_id' => 'required|integer',
                //'term_id' => 'integer',
            ));
        
        //store in database
        $data = new Repo;  
        $data->L = $request->L;  
        $data->course_id = $request->course_id;  
        $data->schedule_id = $request->schedule_id;
        dd($data);
        //$data->unique_code = "";
        //$data->user_id = $request->user_id;
        //$data->name = $request->name;
        //$data->language_id = $request->language_id;
        //$data->course_id = $request->course_id;
        //$data->term_id = $request->term_id;
                
        //$data->unique_code = $request->term_id.'/'.$request->course_id.'/'.$request->user_id;
        $data->save();    

        // variable course refers to schedule function in Course.php model
        // then syncs the data to schedules MySQL table
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
                //get value directly not via blade view
                //->pluck("schedule_id","schedule_id") removed
                $latest_term = DB::table('LTP_Terms')->orderBy('Term_Code', 'DESC')->value('Term_Code');
                $q->where('Te_Term', $latest_term );
            })
            
            ->get();

            $data = view('ajax-select2',compact('select_schedules'))->render();
            return response()->json(['options'=>$data]);
        }
    }
}
