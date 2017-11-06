<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Language;
use App\Course;
use App\User;
use App\Repo;
use Session;
use DB;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $current_user = Auth::user()->indexno;
        $repos_lang = Repo::orderBy('Term', 'desc')->where('INDEXID', $current_user)->first();
        
        return view('home')->withRepos_lang($repos_lang);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $student = User::find($id); //find function specifically checks primary id
        return view('students.profile')->withStudent($student);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $student = User::find($id);
        
        $courses = Course::all(['id', 'name']); // selected $key => $value
        $languages = DB::table('languages')->pluck("name","id")->all();
        
        //$languages = Language::all(['id', 'name']);
        // $selectedcategory = $post->category_id; // this is directly called in the view
        // $tags = Tag::all(['id', 'name']);
        //$cats = [];
        //foreach ($categories as $category) {
        //    $cats[$category->id] = $category->name;
        //}
        // return the view and pass the variable to the prviously created one
        return view('students.edit')->withStudent($student)->withCourses($courses)->withLanguages($languages);
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
        $student = User::find($id);
        $this->validate($request, array(
                'course_id' => 'required|integer'
            ));        
        
        // Save the data to db
        $student = User::find($id);

        $student->language_id = $request->input('language_id');
        $student->course_id = $request->input('course_id');
        $student->save();         

        // Set flash data with message
        $request->session()->flash('success', 'Enrolment has been submitted.');
        // Redirect to flash data to posts.show
        return redirect()->route('students.show', $student->id);
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