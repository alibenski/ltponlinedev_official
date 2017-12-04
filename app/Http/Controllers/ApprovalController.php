<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Preenrolment;
use App\Term;
use Carbon\Carbon;


class ApprovalController extends Controller
{
    public function show($id)
    {
        //
    }

    /**
     * Show the pre-enrolment forms for approving the forms submitted by staff member 
     *
     */
    public function getForm($staff, $tecode)
    {
        //get variables from URL to decrypt and pass to controller logic 
    	$staff = Crypt::decrypt($staff);
        $tecode = Crypt::decrypt($tecode);

        $now_date = Carbon::now()->toDateString();
        $terms = Term::orderBy('Term_Code', 'desc')
                ->whereDate('Term_End', '>=', $now_date)
                ->get()->min();
        $next_term_code = Term::orderBy('Term_Code', 'desc')->where('Term_Code', '=', $terms->Term_Next)->get()->min('Term_Code');
        //query from Preenrolment table the needed information data to include in the control logic and then pass to approval page
        $input_course = Preenrolment::orderBy('Term', 'desc')
                                ->where('INDEXID', $staff)
                                ->where('Term', $next_term_code)
                                ->where('Te_Code', $tecode)
                                ->get();
        $input_staff = Preenrolment::orderBy('Term', 'desc')->orderBy('id', 'desc')
                                ->where('INDEXID', $staff)
                                ->where('Term', $next_term_code)
                                ->where('Te_Code', $tecode)
                                ->first();

        return view('form.approval')->withInput_course($input_course)->withInput_staff($input_staff)->withNext_term_code($next_term_code);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateForm(Request $request, $staff, $tecode)
    {
        $now_date = Carbon::now()->toDateString();
        $terms = Term::orderBy('Term_Code', 'desc')
                ->whereDate('Term_End', '>=', $now_date)
                ->get()->min();
        $next_term_code = Term::orderBy('Term_Code', 'desc')->where('Term_Code', '=', $terms->Term_Next)->get()->min('Term_Code');
        $forms = Preenrolment::orderBy('Term', 'desc')
                                ->where('INDEXID', $staff)
                                ->where('Term', $next_term_code)
                                ->where('Te_Code', $tecode)
                                ->get();


        $decision = $request->input('decision'); 
        // Validate data
            $this->validate($request, array(
                'decision' => 'required|boolean',
            )); 

        // Save the data to db
        $enrol_form = [];
        for ($i = 0; $i < count($forms); $i++) {
            $enrol_form = $forms[$i]->id;
            $course = Preenrolment::find($enrol_form);
            $course->approval = $decision;
            $course->save();
        }
    
        // Set flash data with message
        $request->session()->flash('success', 'Changes have been saved! Decision value is: '.$decision);
        // Redirect to flash data to posts.show
        return redirect()->route('eform');
    }
}
