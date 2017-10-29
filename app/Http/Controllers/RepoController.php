<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Language;
use App\Course;
use App\User;
use App\Repo;
use App\Term;
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
        $courses = Course::all(['id', 'name']); // selected $key => $value
        $languages = DB::table('languages')->pluck("name","id")->all();

        $terms = DB::table('LTP_Terms')->orderBy('Term_Code', 'DESC')->first();
        $user = Auth::user();
        $current_user = Auth::user()->indexno;
        $repos = DB::table('LTP_PASHQTcur')->orderBy('Term_Code', 'desc')
            ->where('INDEXID', '17942')->value('CodeIndexID');
               dd($current_user);
        return view('form.myform')->withCourses($courses)->withLanguages($languages)->withTerms($terms)->withRepos($repos)->withUser($user);
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
                'unique_code' => 'unique|max:255',
                'user_id' => 'integer',
                'name' => 'required|email',
                'language_id' => 'required|integer',
                'course_id' => 'required|integer',
                'term_id' => 'integer',
            ));
        
        //store in database
        $data = new Repo;        
        $data->unique_code = "";
        $data->user_id = $request->user_id;
        $data->name = $request->name;
        $data->language_id = $request->language_id;
        $data->course_id = $request->course_id;
        $data->term_id = $request->term_id;
                
        $data->unique_code = $request->term_id.'/'.$request->course_id.'/'.$request->user_id;
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
            $courses = DB::table('courses')->where('language_id',$request->language_id)->pluck("name","id")->all();
            $data = view('ajax-select',compact('courses'))->render();
            return response()->json(['options'=>$data]);
        }
    }
}
