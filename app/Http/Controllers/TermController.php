<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Term;
use App\Season;

class TermController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $terms = Term::orderBy('Term_Code', 'desc')->paginate(10);
        return view('terms.index')->withTerms($terms);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $terms = Term::orderBy('Term_Code', 'desc')->paginate(10);
        $seasons = Season::pluck('ESEASON', 'ESEASON');
        return view('terms.create')->withTerms($terms)->withSeasons($seasons);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate the data 
        // validate date to be greater that now()
        $this->validate($request, array(
                // 'name' => 'required|max:255',
            ));
        // manipulate before storing
        $termBeginStr = date('d F', strtotime($request->termBeginDate));
        $termEndStr = date('d F Y', strtotime($request->termEndDate));
        $termNameStr = $termBeginStr.' - '.$termEndStr;

        // store in database
        $term = new Term;
        $term->Term_Code = $request->termCode;
        $term->Term_Name = $termNameStr;
        $term->Term_Begin = $request->termBeginDate;
        $term->Term_End = $request->termEndDate;
        $term->Enrol_Date_Begin = $request->enrolBeginInput;
        $term->Enrol_Date_End = $request->enrolEndInput;
        $term->Cancel_Date_Limit = $request->cancelDateInput;
        $term->Activ = 0;

        $term->save();

        $request->session()->flash('success', 'Entry has been saved!'); //laravel 5.4 version

        return redirect()->route('terms.index');
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
