<?php

namespace App\Http\Controllers;

use App\Classroom;
use App\Course;
use App\CourseSchedule;
use App\FocalPoints;
use App\Jobs\SendEmailJob;
use App\Language;
use App\Mail\MailtoApprover;
use App\Mail\SendAuthMail;
use App\Mail\SendMailable;
use App\Mail\SendMailableReminderPlacement;
use App\Mail\SendReminderEmailHR;
use App\Mail\SendReminderEmailPlacementHR;
use App\PlacementForm;
use App\Preenrolment;
use App\Repo;
use App\SDDEXTR;
use App\Schedule;
use App\Term;
use App\Torgan;
use App\User;
use App\Waitlist;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Session;

class WaitlistController extends Controller
{
    public function testMethod()
    {
        $codeSortByCountIndexID = Preenrolment::select('Code', 'Term', DB::raw('count(*) as CountIndexID'))->where('Te_Code', 'F1R1')->where('INDEXID', 'L21264')->groupBy('Code', 'Term')->orderBy(\DB::raw('count(INDEXID)'), 'ASC')->get();
        
        dd($codeSortByCountIndexID);
        // $current_term = \App\Helpers\GlobalFunction::instance()->currentTermObject();
        // $student_last_record = Repo::orderBy('Term', 'desc')->where('Term', $current_term->Term_Code)
        //         ->where('INDEXID', '17942')->first();

        // $select_courses = CourseSchedule::where('L', 'F')
        //     ->where('Te_Term', '191')
        //     ->orderBy('id', 'asc')
        //     ->with('course')
        //     // ->whereHas('course', function($q) {
        //     //                 return $q->where('id', '<', 11);
        //     //             })
        //     ->get();
        //     // ->pluck("course.Description","Te_Code_New");

        // dd($select_courses, $student_last_record->Result, $student_last_record->Te_Code_old, $current_term);
    }
    public function sddextr()
    {
        $sddextr = SDDEXTR::where('INDEXNO', '17942')->first();
        return $sddextr->users->name;
/*
        // method to re-send emails to manager for un-approved forms
        $arrRecipient = [];
        $enrolments_no_mgr_approval = Preenrolment::where('Term', '191')->whereNull('is_self_pay_form')->whereNull('approval')->select('INDEXID', 'Te_Code', 'form_counter', 'mgr_email','created_at')->groupBy('INDEXID', 'Te_Code', 'form_counter', 'mgr_email', 'created_at')->get();
        
        foreach ($enrolments_no_mgr_approval as  $valueMgrEmails) 
        {                 
            $arrRecipient[] = $valueMgrEmails->mgr_email; 
            $recipient = $valueMgrEmails->mgr_email;

            $staff = User::where('indexno', $valueMgrEmails->INDEXID)->first();
            $input_course = Preenrolment::orderBy('Term', 'desc')->orderBy('id', 'desc')->where('INDEXID', $valueMgrEmails->INDEXID)->where('Term', '191')->first();
            $input_schedules = Preenrolment::orderBy('Term', 'desc')
                                ->where('INDEXID', $valueMgrEmails->INDEXID)
                                ->where('Term', '191')
                                ->where('Te_Code', $valueMgrEmails->Te_Code)
                                ->where('form_counter', $valueMgrEmails->form_counter)
                                ->get();
            // Mail::to('allyson.frias@un.org')->send(new SendMailable($input_course, $input_schedules, $staff));
            
            echo 'email sent to: '.$recipient;
            echo '<br>';
            echo $input_course->courses->Description;
            echo '<br>';
            // echo $input_schedules;
            // echo '<br>';
            echo $staff->name;
            echo '<br>';
            echo '<br>';
        } // end of foreach loop
        dd($enrolments_no_mgr_approval);
*/
    }
    public function queryTerm()
    {
        $now_date = Carbon::now();
        $now_year = Carbon::now()->year; 
        $enrolment_term = Term::whereYear('Enrol_Date_Begin', $now_year)
                        ->orderBy('Term_Code', 'desc')
                        ->where('Enrol_Date_Begin', '<=', $now_date)
                        ->where('Approval_Date_Limit_HR', '>=', $now_date)
                        ->get()->min();
        dd($enrolment_term);
    }
    public function sendAuthEmailIndividual()
    {
        $sddextr_email_address = 'm_hallali@yahoo.com';
        // send credential email to user using email from sddextr 
        Mail::to($sddextr_email_address)->send(new SendAuthMail($sddextr_email_address));

        dd($sddextr_email_address);
    }

    public function testQuery()
    {
        // method to re-send emails to manager for un-approved forms
        $arrRecipient = [];
        $enrolments_no_mgr_approval = Preenrolment::where('INDEXID', '')->where('Term', '191')->whereNull('is_self_pay_form')->whereNull('approval')->select('INDEXID', 'Te_Code', 'form_counter', 'mgr_email','created_at')->groupBy('INDEXID', 'Te_Code', 'form_counter', 'mgr_email', 'created_at')->get();
        
        // if ($enrolments_no_mgr_approval->isEmpty()) {
        //     Log::info("No email addresses to pick up. No Emails sent.");
        //     echo $enrolment_term;
        //     echo  $enrolments_no_mgr_approval;
        //     // return exit();
        // }
        foreach ($enrolments_no_mgr_approval as  $valueMgrEmails) 
        { 
            // if submission date < (Enrol_Date_End minus x days) then send reminder emails after x days of submission
            // if ($valueMgrEmails->created_at < Carbon::parse($enrolment_term_object->Enrol_Date_End)->subDays($remind_mgr_param)) {
                // if ($now_date >= Carbon::parse($valueMgrEmails->created_at)->addDays($remind_mgr_param)) {
                
                    $arrRecipient[] = $valueMgrEmails->mgr_email; 
                    $recipient = $valueMgrEmails->mgr_email;

                    $staff = User::where('indexno', $valueMgrEmails->INDEXID)->first();
                    $input_course = Preenrolment::orderBy('Term', 'desc')->orderBy('id', 'desc')->where('INDEXID', $valueMgrEmails->INDEXID)->where('Term', '191')->first();
                    $input_schedules = Preenrolment::orderBy('Term', 'desc')
                                        ->where('INDEXID', $valueMgrEmails->INDEXID)
                                        ->where('Term', '191')
                                        ->where('Te_Code', $valueMgrEmails->Te_Code)
                                        ->where('form_counter', $valueMgrEmails->form_counter)
                                        ->get();
                    Mail::to($recipient)->send(new SendMailable($input_course, $input_schedules, $staff));
                    
                    echo 'email sent to: '.$recipient;
                    echo '<br>';
                    echo '<br>';
                // }
            // }
        } // end of foreach loop
        dd($enrolments_no_mgr_approval);
    }
    // {
        // $arrDept = [];
        // $arrHrEmails = [];
        // $arr=[];
        // $enrolments_no_hr_approval = PlacementForm::where('Term', '191')->whereNull('is_self_pay_form')->whereNull('approval_hr')->where('approval', '1')->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])->get();

        // foreach ($enrolments_no_hr_approval as $valueDept) {
        //     // if ($valueDept->UpdatedOn < Carbon::parse($enrolment_term_object->Enrol_Date_End)->subDays($remind_hr_param)) {
        //         // if ($now_date >= Carbon::parse($valueDept->UpdatedOn)->addDays($remind_hr_param)) {
        //             $arrDept[] = $valueDept->DEPT;
        //             $torgan = Torgan::where('Org name', $valueDept->DEPT)->first();
        //             $learning_partner = $torgan->has_learning_partner;

        //             if ($learning_partner == '1') {
        //                 $query_hr_email = FocalPoints::where('org_id', $torgan->OrgCode)->get(['email']); 
        //                 $fp_email = $query_hr_email->map(function ($val, $key) {
        //                     return $val->email;
        //                 });
        //                 $fp_email_arr = $fp_email->toArray();
        //                 $arrHrEmails[] = $fp_email_arr;

        //                 $formItems = PlacementForm::orderBy('Term', 'desc')
        //                                 ->where('INDEXID', $valueDept->INDEXID)
        //                                 ->where('Term', '191')
        //                                 ->where('L', $valueDept->L)
        //                                 ->where('eform_submit_count', $valueDept->eform_submit_count)
        //                                 ->get();
        //                 $formfirst = PlacementForm::orderBy('Term', 'desc')
        //                                 ->where('INDEXID', $valueDept->INDEXID)
        //                                 ->where('Term', '191')
        //                                 ->where('L', $valueDept->L)
        //                                 ->where('eform_submit_count', $valueDept->eform_submit_count)
        //                                 ->first();   
        //                 // $staff_name = $formfirst->users->name;
        //                 $staff_name = $formfirst->users->name;
        //                 $arr[] = $staff_name;
        //                 $mgr_email = $formfirst->mgr_email;    
        //                 $input_course = $formfirst; 
        //                 // Mail::to($fp_email_arr);
        //                 Mail::to($fp_email_arr)->send(new SendReminderEmailPlacementHR($formItems, $input_course, $staff_name, $mgr_email));
        //             }
        //         // }
        //     // }          
        // } // end of foreach loop
        // dd($enrolments_no_hr_approval);
    // }
    // {
        
    //     $arrDept = [];
    //     $arrHrEmails = [];
    //     $enrolments_no_hr_approval = Preenrolment::where('Term', '191')->whereNull('is_self_pay_form')->whereNull('approval_hr')->where('approval', '1')->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])->select('INDEXID', 'Te_Code', 'form_counter', 'mgr_email', 'DEPT', 'UpdatedOn')->groupBy('INDEXID', 'Te_Code', 'form_counter', 'mgr_email', 'DEPT','UpdatedOn')->get();

    //     foreach ($enrolments_no_hr_approval as $valueDept) {
    //         // if ($valueDept->UpdatedOn < Carbon::parse($enrolment_term_object->Enrol_Date_End)->subDays($remind_hr_param)) {
    //             // if ($now_date >= Carbon::parse($valueDept->UpdatedOn)->addDays($remind_hr_param)) {
                    
    //                 $arrDept[] = $valueDept->DEPT;
    //                 $torgan = Torgan::where('Org name', $valueDept->DEPT)->first();
    //                 $learning_partner = $torgan->has_learning_partner;

    //                 if ($learning_partner == '1') {
    //                     $query_hr_email = FocalPoints::where('org_id', $torgan->OrgCode)->get(['email']); 
    //                     $fp_email = $query_hr_email->map(function ($val, $key) {
    //                         return $val->email;
    //                     });
    //                     $fp_email_arr = $fp_email->toArray();
    //                     $arrHrEmails[] = $fp_email_arr;

    //                     $formItems = Preenrolment::orderBy('Term', 'desc')
    //                                     ->where('INDEXID', $valueDept->INDEXID)
    //                                     ->where('Term', '191')
    //                                     ->where('Te_Code', $valueDept->Te_Code)
    //                                     ->where('form_counter', $valueDept->form_counter)
    //                                     ->get();
    //                     $formfirst = Preenrolment::orderBy('Term', 'desc')
    //                                     ->where('INDEXID', $valueDept->INDEXID)
    //                                     ->where('Term', '191')
    //                                     ->where('Te_Code', $valueDept->Te_Code)
    //                                     ->where('form_counter', $valueDept->form_counter)
    //                                     ->first();   
    //                     $staff_name = $formfirst->users->name;
    //                     $mgr_email = $formfirst->mgr_email;    
    //                     $input_course = $formfirst; 
    //                     // Mail::to($fp_email_arr);
    //                     Mail::to($fp_email_arr)->send(new SendReminderEmailHR($formItems, $input_course, $staff_name, $mgr_email));
    //                 }
    //             // }
    //         // }
    //     }
    //     dd($enrolments_no_hr_approval);
    // }
    // {
    //     $never_logged = User::where('must_change_password', 1)->get();
    //     $input = ([ 
    //         'password' => Hash::make('Welcome2CLM'),
    //     ]);
    //     foreach ($never_logged as $user) {
    //         $user->fill($input)->save();
    //     }

    //     dd($never_logged);
    // }
    // {
    //     $enrolments_no_mgr_approval = PlacementForm::where('Term', '188')->whereNull('is_self_pay_form')->whereNull('approval')->select('INDEXID', 'L', 'eform_submit_count', 'mgr_email','created_at')->groupBy('INDEXID', 'L', 'eform_submit_count', 'mgr_email','created_at')->get()->take(1);
    //     foreach ($enrolments_no_mgr_approval as  $valueMgrEmails) 
    //     {           
    //             $arrRecipient[] = $valueMgrEmails->mgr_email; 
    //             $recipient = $valueMgrEmails->mgr_email;

    //             $staff = User::where('indexno', $valueMgrEmails->INDEXID)->first();
    //             $input_course = PlacementForm::orderBy('id', 'desc')->where('Term', '188')->where('INDEXID', $valueMgrEmails->INDEXID)->where('L', $valueMgrEmails->L)->first();

    //             Mail::to('allyson.frias@un.org')->send(new SendMailableReminderPlacement($input_course, $staff));
    //             echo $recipient;
    //             echo '<br>';
    //             echo '<br>';   
    //     }
    //     $arrDept = [];
    //     $arrHrEmails = [];
    //     $enrolments_no_hr_approval = PlacementForm::where('Term', '188')->whereNull('is_self_pay_form')->whereNull('approval_hr')->where('approval', '1')->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])->get()->take(1);

    //     foreach ($enrolments_no_hr_approval as $valueDept) {
                
    //             $arrDept[] = $valueDept->DEPT;
    //             $torgan = Torgan::where('Org name', $valueDept->DEPT)->first();
    //             $learning_partner = $torgan->has_learning_partner;

    //             if ($learning_partner == '1') {
    //                 $query_hr_email = FocalPoints::where('org_id', $torgan->OrgCode)->get(['email']); 
    //                 $fp_email = $query_hr_email->map(function ($val, $key) {
    //                     return $val->email;
    //                 });
    //                 $fp_email_arr = $fp_email->toArray();
    //                 $arrHrEmails[] = $fp_email_arr;

    //                 $formItems = PlacementForm::orderBy('Term', 'desc')
    //                                 ->where('INDEXID', $valueDept->INDEXID)
    //                                 ->where('Term', '188')
    //                                 ->where('L', $valueDept->L)
    //                                 ->where('eform_submit_count', $valueDept->eform_submit_count)
    //                                 ->get();
    //                 $formfirst = PlacementForm::orderBy('Term', 'desc')
    //                                 ->where('INDEXID', $valueDept->INDEXID)
    //                                 ->where('Term', '188')
    //                                 ->where('L', $valueDept->L)
    //                                 ->where('eform_submit_count', $valueDept->eform_submit_count)
    //                                 ->first();   
    //                 // $staff_name = $formfirst->users->name;
    //                 $staff_name = $formfirst->users->name;
    //                 $arr[] = $staff_name;
    //                 $mgr_email = $formfirst->mgr_email;    
    //                 $input_course = $formfirst; 
    //                 // Mail::to($fp_email_arr);
    //                 Mail::to('allyson.frias@un.org')->send(new SendReminderEmailPlacementHR($formItems, $input_course, $staff_name, $mgr_email));
    //             }
    //     }
    //     // DB::table('jobs')->truncate();
    //     // Log::info("Start sending email");
    //     // for ($i=0; $i < 2; $i++)  {
    //     //     $emailJob = (new SendEmailJob())->delay(Carbon::now()->addSeconds(10));
    //     //     dispatch($emailJob);
    //     // }
    //     //     echo 'email sent<br>';
    //     // Log::info("Finished sending email");

    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    	$students = Waitlist::all();
        return view('waitlist.index')->withStudents($students);
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
