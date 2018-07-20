<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Preenrolment;
use App\Repo;
use DB;
use App\Torgan;
use App\Term;

class PreenrolmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $languages = DB::table('languages')->pluck("name","code")->all();
        $org = Torgan::orderBy('Org Name', 'asc')->get(['Org Name','Org Full Name']);
        $terms = Term::orderBy('Term_Code', 'desc')->get();



        if (is_null($request->Term)) {
            $enrolment_forms = null;
            return view('preenrolment.index')->withEnrolment_forms($enrolment_forms)->withLanguages($languages)->withOrg($org)->withTerms($terms);
        }

        $enrolment_forms = new Preenrolment;
        // $currentQueries = \Request::query();
        $queries = [];

        $columns = [
            'L', 'DEPT', 'Term',
        ];

        
        foreach ($columns as $column) {
            if (\Request::has($column)) {
                $enrolment_forms = $enrolment_forms->where($column, \Request::input($column) );
                $queries[$column] = \Request::input($column);
            }

        } 

            if (\Request::has('sort')) {
                $enrolment_forms = $enrolment_forms->orderBy('created_at', \Request::input('sort') );
                $queries['sort'] = \Request::input('sort');
            }

        // $allQueries = array_merge($queries, $currentQueries);
        $enrolment_forms = $enrolment_forms->paginate(10)->appends($queries);
        return view('preenrolment.index')->withEnrolment_forms($enrolment_forms)->withLanguages($languages)->withOrg($org)->withTerms($terms);
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
}
