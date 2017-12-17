<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Language;
use App\Course;
use App\User;
use App\Repo;
use App\Preenrolment;
use App\Term;
use Session;
use Carbon\Carbon;
use DB;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $student = User::find($id);
        
        $courses = Course::all(); // selected $key => $value
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
}
