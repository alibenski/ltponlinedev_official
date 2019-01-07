<?php

namespace App\Http\Controllers;

use App\Classroom;
use App\NewUser;
use App\Repo;
use App\Teachers;
use App\Term;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TeachersController extends Controller
{
    public function teacherDashboard()
    {
        
        $terms = Term::orderBy('Term_Code', 'desc')->get();       
        $assigned_classes = Classroom::where('Tch_ID', Auth::user()->teachers->Tch_ID)
            ->where('Te_Term', Session::get('Term'))
            ->get();
        
        return view('teachers.teacher_dashboard',compact('terms', 'assigned_classes'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $languages = DB::table('languages')->pluck("name","code")->all();

        $teachers = new Teachers;
        // $currentQueries = \Request::query();
        $queries = [];

        $columns = [
            'Tch_L', 
        ];

        
        foreach ($columns as $column) {
            if (\Request::has($column)) {
                $teachers = $teachers->where($column, \Request::input($column) );
                $queries[$column] = \Request::input($column);
            }

        } 

                if (\Request::has('search')) {
                    $name = \Request::input('search');
                    $teachers = $teachers->with('users')
                        ->whereHas('users', function($q) use ( $name) {
                            return $q->where('name', 'LIKE', '%' . $name . '%')->orWhere('email', 'LIKE', '%' . $name . '%');
                        });
                    $queries['search'] = \Request::input('search');
            } 

        $teachers = $teachers->orderBy('In_Out', 'desc')->orderBy('Tch_Lastname', 'asc')->get();


        return view('teachers.index')->withTeachers($teachers)->withLanguages($languages);
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
     * @param  \App\Teachers  $teachers
     * @return \Illuminate\Http\Response
     */
    public function show(Teachers $teachers)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Teachers  $teachers
     * @return \Illuminate\Http\Response
     */
    public function edit(Teachers $teachers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Teachers  $teachers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Teachers $teachers)
    {
        
    }

    public function ajaxTeacherUpdate(Request $request)
    {
        $teacher = Teachers::where('Tch_ID', $request->Tch_ID)->first();

        $input = $request->all();
        $input = array_filter($input, 'strlen');

        $teacher->fill($input)->save(); 


        $data = $input;
        return response()->json($data);
    }

    public function teacherViewClassrooms()
    {
        return view('teachers.teacher_view_classrooms');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Teachers  $teachers
     * @return \Illuminate\Http\Response
     */
    public function destroy(Teachers $teachers)
    {
        //
    }
}
