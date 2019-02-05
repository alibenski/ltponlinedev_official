<?php

namespace App\Http\Controllers;

use App\FocalPoints;
use App\Mail\MailaboutCancel;
use App\Mail\SendMailable;
use App\Mail\SendReminderEmailHR;
use App\ModifiedForms;
use App\Preenrolment;
use App\Repo;
use App\Term;
use App\Torgan;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Session;

class PreenrolmentController extends Controller
{
    public function ajaxStdComments(Request $request)
    {
        if($request->ajax()){
            $student_enrolments = Preenrolment::withTrashed()
            ->where('INDEXID', $request->indexno)
            ->where('Term', $request->term)
            ->where('Te_Code', $request->tecode)
            ->where('eform_submit_count', $request->eform_submit_count)
            ->groupBy(['Te_Code', 'Term', 'INDEXID' , 'DEPT', 'is_self_pay_form', 'continue_bool', 'form_counter','deleted_at', 'eform_submit_count', 'cancelled_by_student', 'created_at', 'L', 'attachment_id', 'attachment_pay', 'modified_by', 'updated_by_admin', 'std_comments' ])
            ->get(['Te_Code', 'Term', 'INDEXID' , 'DEPT', 'is_self_pay_form', 'continue_bool', 'form_counter','deleted_at', 'eform_submit_count', 'cancelled_by_student', 'created_at', 'L' , 'attachment_id', 'attachment_pay', 'modified_by', 'updated_by_admin', 'std_comments' ]);

            $data = $student_enrolments->first()->std_comments;
            return response()->json($data);
        }
        
    }

    /**
     * Send reminder emails to manager and HR focalpoints.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendReminderEmails()
    {
        //get current year and date
        $now_date = Carbon::now();
        $now_year = Carbon::now()->year; 
        // get the correct enrolment term code
        $enrolment_term = Term::whereYear('Enrol_Date_Begin', $now_year)
                        ->orderBy('Term_Code', 'desc')
                        ->where('Enrol_Date_Begin', '<=', $now_date)
                        ->where('Approval_Date_Limit_HR', '>=', $now_date)
                        ->min('Term_Code');
        if (empty($enrolment_term)) {
            Log::info("Auto-sending of reminder emails failed. Term is null. No Emails sent.");
            echo "Term is null. No Emails sent.";
            return exit();
        }

        $enrolment_term_object = Term::findOrFail($enrolment_term);

        $remind_mgr_param = Term::where('Term_Code', $enrolment_term)->value('Remind_Mgr_After'); // get int value after how many days reminder email should be sent

        $arrRecipient = [];
        $enrolments_no_mgr_approval = Preenrolment::where('Term', $enrolment_term)->whereNull('is_self_pay_form')->whereNull('approval')->select('INDEXID', 'Te_Code', 'form_counter', 'mgr_email','created_at')->groupBy('INDEXID', 'Te_Code', 'form_counter', 'mgr_email', 'created_at')->get();
        
        if ($enrolments_no_mgr_approval->isEmpty()) {
            Log::info("No email addresses to pick up. No Emails sent.");
            echo $enrolment_term;
            echo  $enrolments_no_mgr_approval;
            // return exit();
        }
        foreach ($enrolments_no_mgr_approval as  $valueMgrEmails) 
        { 
            // if submission date < (Enrol_Date_End minus x days) then send reminder emails after x days of submission
            if ($valueMgrEmails->created_at < Carbon::parse($enrolment_term_object->Enrol_Date_End)->subDays($remind_mgr_param)) {
                if ($now_date >= Carbon::parse($valueMgrEmails->created_at)->addDays($remind_mgr_param)) {
                
                    $arrRecipient[] = $valueMgrEmails->mgr_email; 
                    $recipient = $valueMgrEmails->mgr_email;

                    $staff = User::where('indexno', $valueMgrEmails->INDEXID)->first();
                    $input_course = Preenrolment::orderBy('Term', 'desc')->orderBy('id', 'desc')->where('INDEXID', $valueMgrEmails->INDEXID)->where('Term', $enrolment_term)->first();
                    $input_schedules = Preenrolment::orderBy('Term', 'desc')
                                        ->where('INDEXID', $valueMgrEmails->INDEXID)
                                        ->where('Term', $enrolment_term)
                                        ->where('Te_Code', $valueMgrEmails->Te_Code)
                                        ->where('form_counter', $valueMgrEmails->form_counter)
                                        ->get();
                    Mail::to($recipient)->send(new SendMailable($input_course, $input_schedules, $staff));
                    // Mail::raw("This is a test automated message", function($message) use ($recipient){
                    //     $message->from('clm_language@unog.ch', 'CLM Language');
                    //     $message->to('allyson.frias@un.org')->subject('MGR - This is a test automated message');
                    // });
                    echo 'email sent to: '.$recipient;
                    echo '<br>';
                    echo '<br>';
                }
            }

            // else if $now_date = Approval Date Limit then do send to all enrolment forms without manager approval    
        //     if ($now_date->toDateString() == Carbon::parse($enrolment_term_object->Approval_Date_Limit)->toDateString()) {
        //         echo "send to all";
        //         $recipient = $valueMgrEmails->mgr_email;

        //         $staff = User::where('indexno', $valueMgrEmails->INDEXID)->first();
        //         $input_course = Preenrolment::orderBy('Term', 'desc')->orderBy('id', 'desc')->where('INDEXID', $valueMgrEmails->INDEXID)->where('Term', $enrolment_term)->first();
        //         $input_schedules = Preenrolment::orderBy('Term', 'desc')
        //                             ->where('INDEXID', $valueMgrEmails->INDEXID)
        //                             ->where('Term', $enrolment_term)
        //                             ->where('Te_Code', $valueMgrEmails->Te_Code)
        //                             ->where('form_counter', $valueMgrEmails->form_counter)
        //                             ->get();
        //         Mail::to($recipient)->send(new SendMailable($input_course, $input_schedules, $staff));
        //     }
        } // end of foreach loop

        $remind_hr_param = Term::where('Term_Code', $enrolment_term)->value('Remind_HR_After');

        $arrDept = [];
        $arrHrEmails = [];
        $enrolments_no_hr_approval = Preenrolment::where('Term', $enrolment_term)->whereNull('is_self_pay_form')->whereNull('approval_hr')->where('approval', '1')->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])->select('INDEXID', 'Te_Code', 'form_counter', 'mgr_email', 'DEPT', 'UpdatedOn')->groupBy('INDEXID', 'Te_Code', 'form_counter', 'mgr_email', 'DEPT','UpdatedOn')->get();

        foreach ($enrolments_no_hr_approval as $valueDept) {
            if ($valueDept->UpdatedOn < Carbon::parse($enrolment_term_object->Enrol_Date_End)->subDays($remind_hr_param)) {
                if ($now_date >= Carbon::parse($valueDept->UpdatedOn)->addDays($remind_hr_param)) {
                    
                    $arrDept[] = $valueDept->DEPT;
                    $torgan = Torgan::where('Org name', $valueDept->DEPT)->first();
                    $learning_partner = $torgan->has_learning_partner;

                    if ($learning_partner == '1') {
                        $query_hr_email = FocalPoints::where('org_id', $torgan->OrgCode)->get(['email']); 
                        $fp_email = $query_hr_email->map(function ($val, $key) {
                            return $val->email;
                        });
                        $fp_email_arr = $fp_email->toArray();
                        $arrHrEmails[] = $fp_email_arr;

                        $formItems = Preenrolment::orderBy('Term', 'desc')
                                        ->where('INDEXID', $valueDept->INDEXID)
                                        ->where('Term', $enrolment_term)
                                        ->where('Te_Code', $valueDept->Te_Code)
                                        ->where('form_counter', $valueDept->form_counter)
                                        ->get();
                        $formfirst = Preenrolment::orderBy('Term', 'desc')
                                        ->where('INDEXID', $valueDept->INDEXID)
                                        ->where('Term', $enrolment_term)
                                        ->where('Te_Code', $valueDept->Te_Code)
                                        ->where('form_counter', $valueDept->form_counter)
                                        ->first();   
                        $staff_name = $formfirst->users->name;
                        $mgr_email = $formfirst->mgr_email;  

                        // get term values
                        $term = $enrolment_term;
                        // get term values and convert to strings
                        $term_en = Term::where('Term_Code', $term)->first()->Term_Name;
                        $term_fr = Term::where('Term_Code', $term)->first()->Term_Name_Fr;
                        
                        $term_season_en = Term::where('Term_Code', $term)->first()->Comments;
                        $term_season_fr = Term::where('Term_Code', $term)->first()->Comments_fr;

                        $term_date_time = Term::where('Term_Code', $term)->first()->Term_Begin;
                        $term_year = new Carbon($term_date_time);
                        $term_year = $term_year->year;

                        $input_course = $formfirst; 
                        // Mail::to($fp_email_arr);
                        Mail::to($fp_email_arr)->send(new SendReminderEmailHR($formItems, $input_course, $staff_name, $mgr_email, $term_en, $term_fr,$term_season_en, $term_season_fr,$term_year));
                    }
                }
            }

            if ($now_date->toDateString() == Carbon::parse($enrolment_term_object->Approval_Date_Limit_HR)->toDateString()) {
                echo "send to all HR Partners";
                $torgan = Torgan::where('Org name', $valueDept->DEPT)->first();
                $learning_partner = $torgan->has_learning_partner;

                if ($learning_partner == '1') {
                    $query_hr_email = FocalPoints::where('org_id', $torgan->OrgCode)->get(['email']); 
                    $fp_email = $query_hr_email->map(function ($val, $key) {
                        return $val->email;
                    });
                    $fp_email_arr = $fp_email->toArray();
                    $arrHrEmails[] = $fp_email_arr;

                    $formItems = Preenrolment::orderBy('Term', 'desc')
                                    ->where('INDEXID', $valueDept->INDEXID)
                                    ->where('Term', $enrolment_term)
                                    ->where('Te_Code', $valueDept->Te_Code)
                                    ->where('form_counter', $valueDept->form_counter)
                                    ->get();
                    $formfirst = Preenrolment::orderBy('Term', 'desc')
                                    ->where('INDEXID', $valueDept->INDEXID)
                                    ->where('Term', $enrolment_term)
                                    ->where('Te_Code', $valueDept->Te_Code)
                                    ->where('form_counter', $valueDept->form_counter)
                                    ->first();   
                    $staff_name = $formfirst->users->name;
                    $mgr_email = $formfirst->mgr_email;    

                    // get term values
                    $term = $enrolment_term;
                    // get term values and convert to strings
                    $term_en = Term::where('Term_Code', $term)->first()->Term_Name;
                    $term_fr = Term::where('Term_Code', $term)->first()->Term_Name_Fr;
                    
                    $term_season_en = Term::where('Term_Code', $term)->first()->Comments;
                    $term_season_fr = Term::where('Term_Code', $term)->first()->Comments_fr;

                    $term_date_time = Term::where('Term_Code', $term)->first()->Term_Begin;
                    $term_year = new Carbon($term_date_time);
                    $term_year = $term_year->year;

                    $input_course = $formfirst; 
                    // Mail::to($fp_email_arr);
                    Mail::to($fp_email_arr)->send(new SendReminderEmailHR($formItems, $input_course, $staff_name, $mgr_email, $term_en, $term_fr,$term_season_en, $term_season_fr,$term_year));
                }
            }
        } // end of foreach loop

        // dd($arrRecipient, $enrolments_no_mgr_approval, $arrHrEmails,$formfirst);
        return 'reminder enrolment emails sent';
    }

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



        if (!Session::has('Term')) {
            $enrolment_forms = null;
            return view('preenrolment.index')->withEnrolment_forms($enrolment_forms)->withLanguages($languages)->withOrg($org)->withTerms($terms);
        }

        $enrolment_forms = new Preenrolment;
        // $currentQueries = \Request::query();
        $queries = [];

        $columns = [
            'L', 'DEPT', 'Te_Code'
        ];

        
        foreach ($columns as $column) {
            if (\Request::has($column)) {
                $enrolment_forms = $enrolment_forms->where($column, \Request::input($column) );
                $queries[$column] = \Request::input($column);
            }

        } 
            if (Session::has('Term')) {
                    $enrolment_forms = $enrolment_forms->where('Term', Session::get('Term') );
                    $queries['Term'] = Session::get('Term');
            }

                if (\Request::has('search')) {
                    $name = \Request::input('search');
                    $enrolment_forms = $enrolment_forms->with('users')
                        ->whereHas('users', function($q) use ( $name) {
                            return $q->where('name', 'LIKE', '%' . $name . '%')->orWhere('email', 'LIKE', '%' . $name . '%');
                        });
                    $queries['search'] = \Request::input('search');
            } 

            if (\Request::has('sort')) {
                $enrolment_forms = $enrolment_forms->orderBy('created_at', \Request::input('sort') );
                $queries['sort'] = \Request::input('sort');
            }

        // $allQueries = array_merge($queries, $currentQueries);
        $enrolment_forms = $enrolment_forms->withTrashed()->paginate(10)->appends($queries);
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
    public function show($indexno, $term)
    {
        
        return view('preenrolment.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    public function editEnrolmentFields($indexno, $term, $tecode, $form_counter)
    {
        $enrolment_details = Preenrolment::where('INDEXID', $indexno)
            ->where('Term', $term)->where('Te_Code', $tecode)->where('form_counter', $form_counter)
            ->groupBy(['Te_Code', 'Term', 'INDEXID' , 'DEPT', 'is_self_pay_form', 'continue_bool', 'form_counter','deleted_at', 'eform_submit_count', 'cancelled_by_student', 'created_at', 'L', 'attachment_id', 'attachment_pay', 'mgr_email', 'mgr_fname', 'mgr_lname' ])
            ->first(['Te_Code', 'Term', 'INDEXID' , 'DEPT', 'is_self_pay_form', 'continue_bool', 'form_counter','deleted_at', 'eform_submit_count', 'cancelled_by_student', 'created_at', 'L' , 'attachment_id', 'attachment_pay', 'mgr_email', 'mgr_fname', 'mgr_lname' ]);
        
        $enrolment_schedules = Preenrolment::orderBy('id', 'asc')
            ->where('Te_Code', $tecode)
            ->where('INDEXID', $indexno)
            ->where('form_counter', $form_counter)
            ->where('Term', $term)->get(['schedule_id', 'mgr_email', 'approval', 'approval_hr', 'is_self_pay_form', 'DEPT', 'deleted_at', 'INDEXID', 'Term','Te_Code' ]);

        $languages = DB::table('languages')->pluck("name","code")->all();
        $org = Torgan::orderBy('Org Name', 'asc')->get(['Org Name','Org Full Name']);

        return view('preenrolment.edit', compact('enrolment_details', 'enrolment_schedules', 'languages', 'org'));
    }

    public function nothingToModify(Request $request, $indexno, $term, $tecode, $form_counter)
    {
        $enrolment_to_be_copied = Preenrolment::orderBy('id', 'asc')
            ->where('Te_Code', $tecode)
            ->where('INDEXID', $indexno)
            ->where('form_counter', $form_counter)
            ->where('Term', $term)
            ->get();

        $user_id = User::where('indexno', $indexno)->first(['id']);
        
        foreach ($enrolment_to_be_copied as $data) {
            $data->fill(['updated_by_admin' => 1,'modified_by' => Auth::user()->id ])->save();

            // $arr = $data->attributesToArray();
            // $clone_forms = ModifiedForms::create($arr);
        }
        $request->session()->flash('success', 'Admin confirmation successful!');
        // return redirect()->route('manage-user-enrolment-data', $user_id);
        return redirect()->route('users.index');
    }

    public function updateEnrolmentFields(Request $request, $indexno, $term, $tecode, $form_counter)
    {   
        if (is_null($request->L)) {
            $request->session()->flash('warning', 'Nothing to change, Nothing to update...');
            return back();
        }
        $enrolment_to_be_copied = Preenrolment::orderBy('id', 'asc')
            ->where('Te_Code', $tecode)
            ->where('INDEXID', $indexno)
            ->where('form_counter', $form_counter)
            ->where('Term', $term)
            ->get();

        $user_id = User::where('indexno', $indexno)->first(['id']);

        foreach ($enrolment_to_be_copied as $data) {
            $data->fill(['updated_by_admin' => 1,'modified_by' => Auth::user()->id ])->save();

            $arr = $data->attributesToArray();
            $clone_forms = ModifiedForms::create($arr);
        }


        $count_form = $enrolment_to_be_copied->count();
        if ($count_form > 1) {
            $delform = Preenrolment::orderBy('id', 'desc')
            ->where('Te_Code', $tecode)
            ->where('INDEXID', $indexno)
            ->where('form_counter', $form_counter)
            ->where('Term', $term)
            ->first();
            $delform->Code = null;
            $delform->CodeIndexID = null;
            $delform->Te_Code = null;
            $delform->INDEXID = null;
            $delform->Term = null;
            $delform->schedule_id = null;             
            $delform->save();
            $delform->delete();
        }

        $enrolment_to_be_modified = Preenrolment::orderBy('id', 'asc')
            ->where('Te_Code', $tecode)
            ->where('INDEXID', $indexno)
            ->where('form_counter', $form_counter)
            ->where('Term', $term)
            ->get();

        $input = $request->all();
        $input = array_filter($input, 'strlen');
        
        foreach ($enrolment_to_be_modified as $new_data) {
            $new_data->fill($input)->save();    

            $new_data->Code = $new_data->Te_Code.'-'.$new_data->schedule_id.'-'.$new_data->Term;
            $new_data->CodeIndexID = $new_data->Te_Code.'-'.$new_data->schedule_id.'-'.$new_data->Term.'-'.$new_data->INDEXID;
            $new_data->save();
        }
        $request->session()->flash('success', 'Update successful!');
        // return redirect()->route('manage-user-enrolment-data', $user_id);
        return redirect()->route('users.index');
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
    public function destroy(Request $request, $staff, $tecode,  $term, $form)
    {
        $current_user = $staff;
        $admin_id = Auth::user()->id;
        
        //query submitted forms based from tblLTP_Enrolment table
        $forms = Preenrolment::orderBy('Term', 'desc')
                ->where('Te_Code', $tecode)
                ->where('INDEXID', '=', $current_user)
                ->where('Term', $term )
                ->where('form_counter', $form )
                ->get();
        $display_language = Preenrolment::orderBy('Term', 'desc')
                ->where('Te_Code', $tecode)
                ->where('INDEXID', '=', $current_user)
                ->where('Term', $term )
                ->where('form_counter', $form )
                ->first();
        
        //get email address of the Manager
        $mgr_email = $forms->pluck('mgr_email')->first();

        //if self-paying enrolment form
        if (is_null($mgr_email)){
            $enrol_form = [];
            for ($i = 0; $i < count($forms); $i++) {
                $enrol_form = $forms[$i]->id;
                $delform = Preenrolment::find($enrol_form);
                $delform->cancelled_by_student = 1;
                $delform->cancelled_by_admin = $admin_id;
                $delform->save();
                $delform->delete();
            }
            session()->flash('cancel_success', 'Enrolment Form for '.$display_language->courses->EDescription. ' has been cancelled.');
            return redirect()->back();
        }

        //email notification to Manager    
        $staff_member_name = $forms->first()->users->name;
            Mail::to($mgr_email)->send(new MailaboutCancel($forms, $display_language, $staff_member_name));
        
        //email notification to CLM Partner
        $org = $display_language->DEPT;
        // Add more organizations in the IF statement below
        if ($org !== 'UNOG'){
            
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
                    ->send(new MailaboutCancel($forms, $display_language, $staff_member_name));

        }

        $enrol_form = [];
        for ($i = 0; $i < count($forms); $i++) {
            $enrol_form = $forms[$i]->id;
            $delform = Preenrolment::find($enrol_form);
            $delform->cancelled_by_student = 1;
            $delform->cancelled_by_admin = $admin_id;
            $delform->save();
            $delform->delete();
        }

        session()->flash('cancel_success', 'Enrolment Form for '.$display_language->courses->EDescription. ' has been cancelled. An email has been sent to your supervisor and if necessary, to your HR/Staff Development Office.');
        return redirect()->back();
    }
}
