<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Preenrolment;
use App\Term;
use App\User;
use App\Torgan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailtoStudent;
use App\Mail\MailtoApproverHR;
use App\Mail\MailtoStudentHR;

class ApprovalController extends Controller
{
    public function __construct()
    {
        $this->middleware('prevent-back-history');
    }

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
        //check if decision has already been made
        $existing_appr_value = $input_staff->approval; 
        if (isset($existing_appr_value)) {
            
            return redirect()->route('eform');
        } 
        
        return view('form.approval')->withInput_course($input_course)->withInput_staff($input_staff)->withNext_term_code($next_term_code);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $staff $tecode
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
                'decision' => 'required|boolean|not_equal_to_existing',
                'INDEXID' => 'required',
                'Te_Code' => 'required',
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

        // execute Mail class before redirect
        $formfirst = Preenrolment::orderBy('Term', 'desc')
                                ->where('INDEXID', $staff)
                                ->where('Term', $next_term_code)
                                ->where('Te_Code', $tecode)
                                ->first();    
        // query student email from users model via index nmber in preenrolment model
        $staff_name = $formfirst->users->name;
        $staff_email = $formfirst->users->email;
        $staff_index = $formfirst->INDEXID;   
        $mgr_email = $formfirst->mgr_email;
        $now_date = Carbon::now()->toDateString();
        $terms = Term::orderBy('Term_Code', 'desc')
                ->whereDate('Term_End', '>=', $now_date)
                ->get()->min();
        $next_term_code = Term::orderBy('Term_Code', 'desc')->where('Term_Code', '=', $terms->Term_Next)->get()->min('Term_Code');
        $course = Preenrolment::orderBy('Term', 'desc')->orderBy('id', 'desc')
                                ->where('INDEXID', $staff_index)
                                ->value('Te_Code');
        //query from Preenrolment table the needed information data to include in email
        $input_course = $formfirst;
        //check the organization of the student to know which email process is followed by the system
        $org = $formfirst->DEPT; 

        if ($org !== 'UNOG' && $decision !== '0') {

            Mail::to($staff_email)
                    ->cc($mgr_email)
                    ->send(new MailtoStudent($input_course, $staff_name));

            //if not UNOG, email to HR Learning Partner of $other_org
            $other_org = Torgan::where('Org name', $org)->select('Org name', 'Org Full Name', 'Org Contact')->get();
            $org_email = 'allyson.frias@un.org';//should be $other_org->email
            Mail::to($org_email)
                    ->send(new MailtoApproverHR($forms, $input_course, $staff_name, $mgr_email));
            
            return redirect()->route('eform');            
        } else {

            Mail::to($staff_email)
                    ->cc($mgr_email)
                    ->send(new MailtoStudent($input_course, $staff_name));
            
            return redirect()->route('eform');
        }

    }

    /**
     * Show the pre-enrolment forms for approving the forms submitted by staff member 
     *
     */
    public function getForm2hr($staff, $tecode)
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
        //check if decision has already been made
        $existing_appr_value = $input_staff->approval_hr; 
        if (isset($existing_appr_value)) {
            
            return redirect()->route('eform2');
        } 
        
        return view('form.approvalhr')->withInput_course($input_course)->withInput_staff($input_staff)->withNext_term_code($next_term_code);
    }

    public function updateForm2hr(Request $request, $staff, $tecode)
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
                    
        $decision = $request->input('decisionhr'); 
        // Validate data
            $this->validate($request, array(
                'decisionhr' => 'required|boolean|not_equal_to_existing',
                'INDEXID' => 'required',
                'Te_Code' => 'required',
            )); 

        // Save the data to db
        $enrol_form = [];
        for ($i = 0; $i < count($forms); $i++) {
            $enrol_form = $forms[$i]->id;
            $course = Preenrolment::find($enrol_form);
            $course->approval_hr = $decision;
            $course->save();
        }
    
        // Set flash data with message
        $request->session()->flash('success', 'CLM Learning Partner Decision has been saved! Decision value is: '.$decision);

        // execute Mail class before redirect
        $formfirst = Preenrolment::orderBy('Term', 'desc')
                                ->where('INDEXID', $staff)
                                ->where('Term', $next_term_code)
                                ->where('Te_Code', $tecode)
                                ->first();    
        // query student email from users model via index nmber in preenrolment model
        $staff_name = $formfirst->users->name;
        $staff_email = $formfirst->users->email;
        $staff_index = $formfirst->INDEXID;   
        $mgr_email = $formfirst->mgr_email;
        $now_date = Carbon::now()->toDateString();
        $terms = Term::orderBy('Term_Code', 'desc')
                ->whereDate('Term_End', '>=', $now_date)
                ->get()->min();
        $next_term_code = Term::orderBy('Term_Code', 'desc')->where('Term_Code', '=', $terms->Term_Next)->get()->min('Term_Code');
        $course = Preenrolment::orderBy('Term', 'desc')->orderBy('id', 'desc')
                                ->where('INDEXID', $staff_index)
                                ->value('Te_Code');
        //query from Preenrolment table the needed information data to include in email
        $input_course = $formfirst;

        Mail::to($staff_email)
                ->cc($mgr_email)
                ->send(new MailtoStudentHR($input_course, $staff_name));
        
        return redirect()->route('eform2');
        
    }

}
