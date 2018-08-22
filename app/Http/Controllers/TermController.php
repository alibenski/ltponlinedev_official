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

        // automated prev and next term code 

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
        $this->validate($request, array(
                'Term_Code' => 'required|unique:LTP_Terms',
                'Remind_Mgr_After' => 'required|integer',
                'Remind_HR_After' => 'required|integer',
            ));

        // Term_Begin should not be less than Approval_Date_Limit of previous term
        $selectedTerm = $request->Term_Code; // No need of type casting
        // echo substr($selectedTerm, 0, 1); // get first value
        // echo substr($selectedTerm, -1); // get last value
        $lastDigit = substr($selectedTerm, -1);

        if ($lastDigit == 9) {
            $prev_term = $selectedTerm - 5;
        }
        // if last digit is 1, check Term table for previous term value or subtract 2 from selectedTerm value
        if ($lastDigit == 1) {
            $prev_term = $selectedTerm - 2;
        }
        // if last digit is 4, check Term table for previous term value or subtract 3 from selectedTerm value
        if ($lastDigit == 4) {
            $prev_term = $selectedTerm - 3;
        }
        if ($lastDigit == 8) {
            $prev_term = $selectedTerm - 4;
        }
        $previous_term = Term::where('Term_Code', $prev_term)->first();

        if ($request->Enrol_Date_Begin < $previous_term->Approval_Date_Limit) {
            $request->session()->flash('interdire-msg', 'Enrolment Begin Date ('.$request->Enrol_Date_Begin.') cannot be less than the Approval Date Limit ('.$previous_term->Approval_Date_Limit.') of the previous term: '.$prev_term);
            return redirect()->back();
        }

        // manipulate strings before storing
        $termBeginStr = date('d F', strtotime($request->Term_Begin));
        $termEndStr = date('d F Y', strtotime($request->Term_End));
        $termNameStr = $termBeginStr.' - '.$termEndStr;

        // store in database
        $term = new Term;
        $term->Term_Code = $request->Term_Code;
        $term->Term_Name = $termNameStr;
        $term->Term_Begin = $request->Term_Begin;
        $term->Term_End = $request->Term_End;
        $term->Term_Prev = $request->Term_Prev;
        $term->Term_Next = $request->Term_Next;
        $term->Enrol_Date_Begin = $request->Enrol_Date_Begin;
        $term->Enrol_Date_End = $request->Enrol_Date_End;
        $term->Cancel_Date_Limit = $request->Cancel_Date_Limit;
        $term->Approval_Date_Limit = $request->Approval_Date_Limit;
        $term->Remind_Mgr_After = $request->Remind_Mgr_After;
        $term->Remind_HR_After = $request->Remind_HR_After;
        $term->Comments = $request->Comments;
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
        $term = Term::find($id);
        $seasons = Season::pluck('ESEASON', 'ESEASON');
        return view('terms.edit')->withTerm($term)->withSeasons($seasons);
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
        $this->validate($request, array(
                // validate if termCode is unique 
                'Term_Code' => 'unique:LTP_Terms,Term_Code|',
                'Term_Begin' => 'required_with:Term_End|',
                'Term_End' => 'required_with:Term_Begin|',
            ));

        $noTokenMethod = $request->except(['_token', '_method']);
        $fliteredInput = (array_filter($noTokenMethod));

        // update data in db
        $term = Term::findOrFail($id);

        // manipulate Term_Name before storing
        if (!is_null($request->Term_Begin)) {
            $termBeginStr = date('d F', strtotime($request->Term_Begin));
            $termEndStr = date('d F Y', strtotime($request->Term_End));
            $termNameStr = $termBeginStr.' - '.$termEndStr;
            $term->Term_Name = $termNameStr;
        }
        $term->save();
        $term->update($fliteredInput);

        $request->session()->flash('success', 'Changes have been saved!');
        return redirect()->route('terms.index');
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
