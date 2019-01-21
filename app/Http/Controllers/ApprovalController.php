<?php

namespace App\Http\Controllers;

use App\FocalPoints;
use App\Mail\MailPlacementApprovaltoStudent;
use App\Mail\MailPlacementHRApprovaltoStudent;
use App\Mail\MailPlacementTesttoApproverHR;
use App\Mail\MailtoApproverHR;
use App\Mail\MailtoStudent;
use App\Mail\MailtoStudentHR;
use App\PlacementForm;
use App\Preenrolment;
use App\Term;
use App\Torgan;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

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

    public function getPlacementFormData($staff, $lang, $id, $form, $term)
    {
        //get variables from URL to decrypt and pass to controller logic 
        $staff = Crypt::decrypt($staff);
        $lang = Crypt::decrypt($lang);
        $id = Crypt::decrypt($id);
        $form_counter = Crypt::decrypt($form);
        $term = Crypt::decrypt($term);

        $next_term_code = $term; 
        $next_term_name = Term::where('Term_Code', $next_term_code)->first()->Term_Name;

        //query from PlacementForm table the needed information data to include in the control logic and then pass to approval page
        $input_course = PlacementForm::orderBy('Term', 'desc')
                                ->where('INDEXID', $staff)
                                ->where('Term', $next_term_code)
                                ->where('L', $lang)
                                ->where('eform_submit_count', $form_counter)
                                ->get();
        $input_staff = PlacementForm::withTrashed()->orderBy('Term', 'desc')->orderBy('id', 'desc')
                                ->where('INDEXID', $staff)
                                ->where('Term', $next_term_code)
                                ->where('L', $lang)
                                ->where('id', $id)
                                ->where('eform_submit_count', $form_counter)
                                ->first();

        // check if decision has already been made or self-paid form
        $existing_appr_value = $input_staff->approval;
        $is_self_pay = $input_staff->is_self_pay_form;
        $is_deleted = $input_staff->deleted_at;
        if (isset($existing_appr_value) || isset($is_self_pay) || isset($is_deleted)) {
            return redirect()->route('eform');
        } 
        
        return view('form.placementApprovalPage')->withInput_course($input_course)->withInput_staff($input_staff)->withNext_term_code($next_term_code)->withNext_term_name($next_term_name);
    }

    public function updatePlacementFormData(Request $request, $staff, $lang, $formcount, $term)
    {   
        $next_term_code = $term;
        $forms = PlacementForm::orderBy('id', 'desc')
                                ->where('INDEXID', $staff)
                                ->where('Term', $next_term_code)
                                ->where('L', $lang)
                                ->where('eform_submit_count', $formcount)
                                ->first();

        $mgr_comment =  $request->input('mgr_comment');
        $decision = $request->input('decision-'.$forms->L);
        // Validate decision input 
                $this->validate($request, array(
                    'decision-'.$forms->L => 'required|',
                )); 

        // Save the data to db 
        $updateData = PlacementForm::find($forms->id);
        $updateData->approval =  $decision; 
        $updateData->mgr_comments = $mgr_comment;
        $updateData->save();

        // query from the table with the saved data and then
        // execute Mail class before redirect
        $formfirst = PlacementForm::orderBy('Term', 'desc')
                                ->where('INDEXID', $staff)
                                ->where('Term', $next_term_code)
                                ->where('L', $lang)
                                ->where('eform_submit_count', $formcount)
                                ->first();

        $formItems = PlacementForm::orderBy('Term', 'desc')
                                ->where('INDEXID', $staff)
                                ->where('Term', $next_term_code)
                                ->where('L', $lang)
                                ->where('eform_submit_count', $formcount)
                                ->get();

        // query student email from users model via index nmber in preenrolment model
        $staff_name = $formfirst->users->name;
        $staff_email = $formfirst->users->email;
        $staff_index = $formfirst->INDEXID;   
        $mgr_email = $formfirst->mgr_email;
        
        // query from Preenrolment table the needed information data to include in email
        $input_course = $formfirst; 

        // check the organization of the student to know which email process is followed by the system
        $org = $formfirst->DEPT; 

        $torgan = Torgan::where('Org name', $org)->first();
        $learning_partner = $torgan->has_learning_partner;
        
        // Add more organizations in the IF statement below
        if ($learning_partner == '1' && $decision != '0') {
            // mail to staff members which have a CLM learning partner
            Mail::to($staff_email)
                    ->cc($mgr_email)
                    ->send(new MailPlacementApprovaltoStudent($formItems, $input_course, $staff_name, $mgr_comment, $request));

            //if not UNOG, email to HR Learning Partner of $other_org
            $other_org = Torgan::where('Org name', $org)->first();
            $org_query = FocalPoints::where('org_id', $other_org->OrgCode)->get(['email']); 

            //use map function to iterate through the collection and store value of email to var $org_email
            //subjects each value to a callback function
            $org_email = $org_query->map(function ($val, $key) {
                return $val->email;
            });
            //make collection to array
            $org_email_arr = $org_email->toArray(); 
            //send email to array of email addresses $org_email_arr
            Mail::to($org_email_arr)
                    ->send(new MailPlacementTesttoApproverHR($formItems, $input_course, $staff_name, $mgr_email));
           
            // return redirect()->route('eform');            
        } else {
            // mail to UNOG staff members or staff which do not have CLM learning partner
            Mail::to($staff_email)
                    ->cc($mgr_email)
                    ->send(new MailPlacementApprovaltoStudent($formItems, $input_course, $staff_name, $mgr_comment, $request));
        }
        
        if($decision == '1'){
            $decision_text = 'Yes, you have approved the request.';
            } else {
            $decision_text = 'No, you did not approve the request.';

                $enrol_form_d = $forms->id;
                $course = PlacementForm::find($enrol_form_d);
                $course->delete();
                
            }
        // Set flash data with message
        $request->session()->flash('success', 'Manager Decision has been saved! Decision is: '.$decision_text);

        return redirect()->route('eform');
    }

    public function getPlacementFormData2hr($staff, $lang, $id, $form, $term)
    {   
        //get variables from URL to decrypt and pass to controller logic 
        $staff = Crypt::decrypt($staff);
        $lang = Crypt::decrypt($lang);
        $id = Crypt::decrypt($id);
        $form_counter = Crypt::decrypt($form);
        $term = Crypt::decrypt($term);

        $next_term_code = $term; 
        $next_term_name = Term::where('Term_Code', $next_term_code)->first()->Term_Name;
        //query from PlacementForm table the needed information data to include in the control logic and then pass to approval page
        $input_course = PlacementForm::orderBy('Term', 'desc')
                                ->where('INDEXID', $staff)
                                ->where('Term', $next_term_code)
                                ->where('L', $lang)
                                ->where('eform_submit_count', $form_counter)
                                ->get();
        $input_staff = PlacementForm::withTrashed()->orderBy('Term', 'desc')->orderBy('id', 'desc')
                                ->where('INDEXID', $staff)
                                ->where('Term', $next_term_code)
                                ->where('eform_submit_count', $form_counter)
                                ->where('id', $id)
                                ->where('L', $lang)
                                ->first();
        //check if decision has already been made
        $existing_appr_value = $input_staff->approval_hr;
        $is_self_pay = $input_staff->is_self_pay_form;
        $is_deleted = $input_staff->deleted_at;
        if (isset($existing_appr_value) || isset($is_self_pay) || isset($is_deleted)) {
            return redirect()->route('eform2');
        } 
        
        return view('form.placementApprovalHRPage')->withInput_course($input_course)->withInput_staff($input_staff)->withNext_term_code($next_term_code)->withNext_term_name($next_term_name);
    }

    public function updatePlacementFormData2hr(Request $request, $staff, $lang, $formcount, $term)
    {
        $next_term_code = $term;
        $forms = PlacementForm::orderBy('Term', 'desc')
                                ->where('INDEXID', $staff)
                                ->where('Term', $next_term_code)
                                ->where('L', $lang)
                                ->where('eform_submit_count', $formcount)
                                ->first();
                
        $hr_comment =  $request->input('hr_comment');
        $decision = $request->input('decisionhr'); 
        // Validate data
            $this->validate($request, array(
                'decisionhr' => 'required|boolean|',
                'INDEXID' => 'required',
            )); 

        // Save the data to db
            $enrol_form = $forms->id;
            $course = PlacementForm::find($enrol_form);
            $course->approval_hr = $decision;
            $course->hr_comments = $hr_comment;
            $course->save();

        // execute Mail class before redirect
        $formfirst = PlacementForm::orderBy('Term', 'desc')
                                ->where('INDEXID', $staff)
                                ->where('Term', $next_term_code)
                                ->where('L', $lang)
                                ->where('eform_submit_count', $formcount)
                                ->first();    

        $formItems = PlacementForm::orderBy('Term', 'desc')
                                ->where('INDEXID', $staff)
                                ->where('Term', $next_term_code)
                                ->where('L', $lang)
                                ->where('eform_submit_count', $formcount)
                                ->get();

        // query student email from users model via index nmber in preenrolment model
        $staff_name = $formfirst->users->name;
        $staff_email = $formfirst->users->email;
        $staff_index = $formfirst->INDEXID;   
        $mgr_email = $formfirst->mgr_email;
        
        //query from PlacementForm table the needed information data to include in email
        $input_course = $formfirst;

        // query org and clm hr partner emails addresses 
        $org = $formfirst->DEPT; 
        $other_org = Torgan::where('Org name', $org)->first();
        $org_query = FocalPoints::where('org_id', $other_org->OrgCode)->get(['email']); 

        $org_email = $org_query->map(function ($val, $key) {
            return $val->email;
        });

        $org_email_arr = $org_email->toArray(); 

        Mail::to($staff_email)
                ->cc($mgr_email)
                ->bcc($org_email_arr)
                ->send(new MailPlacementHRApprovaltoStudent($formItems, $input_course, $staff_name, $request));
        
        if($decision == 1){
            $decision_text = 'Yes, you approved the request.';
        } else {
            $decision_text = 'No, you did not approved the request.';

                $enrol_form_d = $forms->id;
                $course = PlacementForm::find($enrol_form_d);
                $course->delete();
        }
    
        // Set flash data with message
        $request->session()->flash('success', 'CLM Learning Partner Decision has been saved! Decision is: '.$decision_text);

        return redirect()->route('eform2');
    }

    /**
     * Show the pre-enrolment forms for approving the forms submitted by staff member 
     *
     */
    public function getForm($staff, $tecode, $id, $form, $term)
    {
        //get variables from URL to decrypt and pass to controller logic 
    	$staff = Crypt::decrypt($staff);
        $tecode = Crypt::decrypt($tecode);
        $id = Crypt::decrypt($id);
        $form_counter = Crypt::decrypt($form);
        $term = Crypt::decrypt($term);

        $next_term_code = $term; 
        $next_term_name = Term::where('Term_Code', $next_term_code)->first()->Term_Name;

        //query from Preenrolment table the needed information data to include in the control logic and then pass to approval page
        $input_course = Preenrolment::orderBy('Term', 'desc')
                                ->where('INDEXID', $staff)
                                ->where('Term', $term)
                                ->where('Te_Code', $tecode)
                                ->where('form_counter', $form_counter)
                                ->get();
        $input_staff = Preenrolment::withTrashed()->orderBy('Term', 'desc')->orderBy('id', 'desc')
                                ->where('INDEXID', $staff)
                                ->where('Term', $term)
                                ->where('Te_Code', $tecode)
                                ->where('id', $id)
                                ->where('form_counter', $form_counter)
                                ->first();

        // check if decision has already been made or self-paid form
        $existing_appr_value = $input_staff->approval;
        $is_self_pay = $input_staff->is_self_pay_form;
        $is_deleted = $input_staff->deleted_at;
        if (isset($existing_appr_value) || isset($is_self_pay) || isset($is_deleted)) {
            return redirect()->route('eform');
        } 
        
        return view('form.approval')->withInput_course($input_course)->withInput_staff($input_staff)->withNext_term_code($next_term_code)->withNext_term_name($next_term_name);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $staff $tecode
     * @return \Illuminate\Http\Response
     */
    public function updateForm(Request $request, $staff, $tecode, $formcount, $term)
    {
        $next_term_code = $term;
        $forms = Preenrolment::orderBy('Term', 'desc')
                                ->where('INDEXID', $staff)
                                ->where('Term', $next_term_code)
                                ->where('Te_Code', $tecode)
                                ->where('form_counter', $formcount)
                                ->get();
        
        $mgr_comment =  $request->input('mgr_comment');

        // Validate decision input array
        for ($i = 0; $i < count($forms); $i++) {
                $dataDecision =  []; 
                $dataDecision = $request->input('decision-'.$forms[$i]->CodeIndexID);
                
                $this->validate($request, array(
                    'decision-'.$forms[$i]->CodeIndexID => 'required|boolean|',
                )); 
        }

        // Validate other data
            $this->validate($request, array(
                // 'decision' => 'required|boolean|not_equal_to_existing',
                'INDEXID' => 'required',
                'Te_Code' => 'required',
            )); 

        // Save the data to db 
        $enrol_form = [];
        for ($i = 0; $i < count($forms); $i++) {
            $enrol_form = $forms[$i]->CodeIndexID;
            $course = Preenrolment::where('CodeIndexID', $enrol_form)->first();
                $dataDecision =  []; 
                $dataDecision = $request->input('decision-'.$forms[$i]->CodeIndexID);
            $course->approval = $dataDecision;
            $course->mgr_comments = $mgr_comment;
            $course->save();
        }

        // query from the table with the saved data and then
        // execute Mail class before redirect
        $formfirst = Preenrolment::orderBy('Term', 'desc')
                                ->where('INDEXID', $staff)
                                ->where('Term', $next_term_code)
                                ->where('Te_Code', $tecode)
                                ->where('form_counter', $formcount)
                                ->first();

        $formItems = Preenrolment::orderBy('Term', 'desc')
                                ->where('INDEXID', $staff)
                                ->where('Term', $next_term_code)
                                ->where('Te_Code', $tecode)
                                ->where('form_counter', $formcount)
                                ->get();

        // query student email from users model via index nmber in preenrolment model
        $staff_name = $formfirst->users->name;
        $staff_email = $formfirst->users->email;
        $staff_index = $formfirst->INDEXID;   
        $mgr_email = $formfirst->mgr_email;
        
        // query from Preenrolment table the needed information data to include in email
        $input_course = $formfirst; 

        // check the organization of the student to know which email process is followed by the system
        $org = $formfirst->DEPT; 

        $torgan = Torgan::where('Org name', $org)->first();
        $learning_partner = $torgan->has_learning_partner;

        // store decision values in array and then compare the values to define $decision variable
        $getDecision = [];
        $decision = null;
        foreach ($formItems as $value) {
            $getDecision[] = $value->approval;
            var_dump($getDecision);
        }

        if ($getDecision[0] == 1 && !isset($getDecision[1])) {
            $decision = 1; 
        } elseif (isset($getDecision[1]) && ($getDecision[0] == 1 || $getDecision[1] == 1 )) {
            $decision = 1;
        } elseif ($getDecision[0] == 0 && !isset($getDecision[1])) {
            $decision = 0;
        } else {
            $decision = 0;
        }

        // Add more organizations in the IF statement below
        // if ($org != 'UNOG' && $decision != '0') {
        if ($learning_partner == '1' && $decision != '0') {
            // mail to staff members which have a CLM learning partner
            Mail::to($staff_email)
                    ->cc($mgr_email)
                    ->send(new MailtoStudent($formItems, $input_course, $staff_name, $mgr_comment, $request));

            //if not UNOG, email to HR Learning Partner of $other_org
            $other_org = Torgan::where('Org name', $org)->first();
            $org_query = FocalPoints::where('org_id', $other_org->OrgCode)->get(['email']); 

            //use map function to iterate through the collection and store value of email to var $org_email
            //subjects each value to a callback function
            $org_email = $org_query->map(function ($val, $key) {
                return $val->email;
            });
            //make collection to array
            $org_email_arr = $org_email->toArray(); 
            //send email to array of email addresses $org_email_arr
            Mail::to($org_email_arr)
                    ->send(new MailtoApproverHR($formItems, $input_course, $staff_name, $mgr_email));
           
            // return redirect()->route('eform');            
        } else {
            // mail to UNOG staff members or staff which do not have CLM learning partner
            Mail::to($staff_email)
                    ->cc($mgr_email)
                    ->send(new MailtoStudent($formItems, $input_course, $staff_name, $mgr_comment, $request));
        }
        
        if($decision == '1'){
            $decision_text = 'Yes, you have approved at least one of the chosen schedules.';
            } else {
            $decision_text = 'No, you did not approve any of the chosen schedules.';

            $enrol_form_d = [];
            for ($i = 0; $i < count($forms); $i++) {
                $enrol_form_d = $forms[$i]->id;
                $course = Preenrolment::find($enrol_form_d);
                $course->delete();
                }
            }
        // Set flash data with message
        $request->session()->flash('success', 'Manager Decision has been saved! Decision is: '.$decision_text);

        return redirect()->route('eform');

    }

    /**
     * Show the pre-enrolment forms for approving the forms submitted by staff member 
     *
     */
    public function getForm2hr($staff, $tecode, $id, $form, $term)
    {
        //get variables from URL to decrypt and pass to controller logic 
        $staff = Crypt::decrypt($staff);
        $tecode = Crypt::decrypt($tecode);
        $id = Crypt::decrypt($id);
        $form_counter = Crypt::decrypt($form);
        $term = Crypt::decrypt($term);

        $next_term_code = $term;
        $next_term_name = Term::where('Term_Code', $next_term_code)->first()->Term_Name;
        //query from Preenrolment table the needed information data to include in the control logic and then pass to approval page
        $input_course = Preenrolment::orderBy('Term', 'desc')
                                ->where('INDEXID', $staff)
                                ->where('Term', $next_term_code)
                                ->where('Te_Code', $tecode)
                                ->where('form_counter', $form_counter)
                                ->get();
        $input_staff = Preenrolment::withTrashed()->orderBy('Term', 'desc')->orderBy('id', 'desc')
                                ->where('INDEXID', $staff)
                                ->where('Term', $next_term_code)
                                ->where('form_counter', $form_counter)
                                ->where('id', $id)
                                ->where('Te_Code', $tecode)
                                ->first();
        //check if decision has already been made
        $existing_appr_value = $input_staff->approval_hr;
        $is_self_pay = $input_staff->is_self_pay_form;
        $is_deleted = $input_staff->deleted_at;
        if (isset($existing_appr_value) || isset($is_self_pay) || isset($is_deleted)) {
            return redirect()->route('eform2');
        } 
        
        return view('form.approvalhr')->withInput_course($input_course)->withInput_staff($input_staff)->withNext_term_code($next_term_code)->withNext_term_name($next_term_name);
    }

    public function updateForm2hr(Request $request, $staff, $tecode, $formcount, $term)
    {
        $next_term_code = $term;
        $forms = Preenrolment::orderBy('Term', 'desc')
                                ->where('INDEXID', $staff)
                                ->where('Term', $next_term_code)
                                ->where('Te_Code', $tecode)
                                ->where('form_counter', $formcount)
                                ->get();
                    
        $hr_comment =  $request->input('hr_comment');
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
            $course->hr_comments = $hr_comment;
            $course->save();
        }

        // execute Mail class before redirect
        $formfirst = Preenrolment::orderBy('Term', 'desc')
                                ->where('INDEXID', $staff)
                                ->where('Term', $next_term_code)
                                ->where('Te_Code', $tecode)
                                ->where('form_counter', $formcount)
                                ->first();    

        $formItems = Preenrolment::orderBy('Term', 'desc')
                                ->where('INDEXID', $staff)
                                ->where('Term', $next_term_code)
                                ->where('Te_Code', $tecode)
                                ->where('form_counter', $formcount)
                                ->get();

        // query student email from users model via index nmber in preenrolment model
        $staff_name = $formfirst->users->name;
        $staff_email = $formfirst->users->email;
        $staff_index = $formfirst->INDEXID;   
        $mgr_email = $formfirst->mgr_email;
        
        // query from Preenrolment table the needed information data to include in email
        $input_course = $formfirst;

        // query org and clm hr partner emails addresses 
        $org = $formfirst->DEPT; 
        $other_org = Torgan::where('Org name', $org)->first();
        $org_query = FocalPoints::where('org_id', $other_org->OrgCode)->get(['email']); 

        $org_email = $org_query->map(function ($val, $key) {
            return $val->email;
        });

        $org_email_arr = $org_email->toArray(); 

        Mail::to($staff_email)
                // ->cc($mgr_email)
                ->bcc($org_email_arr)
                ->send(new MailtoStudentHR($formItems, $input_course, $staff_name, $request));
        
        if($decision == 1){
            $decision_text = 'Yes, you approved the enrolment.';
        } else {
            $decision_text = 'No, you did not approved the enrolment.';

            $enrol_form_d = [];
            for ($i = 0; $i < count($forms); $i++) {
                $enrol_form_d = $forms[$i]->id;
                $course = Preenrolment::find($enrol_form_d);
                $course->delete();
            }
        }
    
        // Set flash data with message
        $request->session()->flash('success', 'CLM Learning Partner Decision has been saved! Decision is: '.$decision_text);

        return redirect()->route('eform2');
        
    }

}
