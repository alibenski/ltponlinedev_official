<?php

namespace App\Http\Controllers;

use App\Classroom;
use App\Course;
use App\Day;
use App\FocalPoints;
use App\Language;
use App\Mail\MailPlacementTesttoApprover;
use App\Mail\MailtoApprover;
use App\Mail\SendMailableReminderPlacement;
use App\Mail\SendReminderEmailPlacementHR;
use App\PlacementForm;
use App\PlacementSchedule;
use App\Preenrolment;
use App\Repo;
use App\SDDEXTR;
use App\Schedule;
use App\Term;
use App\Torgan;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Session;

class PlacementFormController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('prevent-back-history');
        // $this->middleware('opencloseenrolment');
        // $this->middleware('checksubmissioncount');
        // $this->middleware('checkcontinue');
    }

    /**
     * Send reminder emails to manager and HR focalpoints.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendReminderEmailsPlacement()
    {
        $now_date = Carbon::now();
        $now_year = Carbon::now()->year; 
        $enrolment_term = Term::whereYear('Enrol_Date_Begin', $now_year)
                        ->orderBy('Term_Code', 'desc')
                        ->where('Approval_Date_Limit_HR', '>=', $now_date)
                        ->value('Term_Code');
        if (empty($enrolment_term)) {
            Log::info("Term is null. No Emails sent.");
            echo "Term is null. No Emails sent.";
            return exit();
        }

        $enrolment_term_object = Term::findOrFail($enrolment_term);

        $remind_mgr_param = Term::where('Term_Code', $enrolment_term)->value('Remind_Mgr_After'); // get int value after how many days reminder email should be sent

        $arrRecipient = [];
        $enrolments_no_mgr_approval = PlacementForm::where('Term', $enrolment_term)->whereNull('is_self_pay_form')->whereNull('approval')->select('INDEXID', 'L', 'eform_submit_count', 'mgr_email','created_at')->groupBy('INDEXID', 'L', 'eform_submit_count', 'mgr_email','created_at')->get();

        if ($enrolments_no_mgr_approval->isEmpty()) {
            Log::info("No email addresses to pick up. No Emails sent.");
            echo $enrolment_term;
            echo  $enrolments_no_mgr_approval;
            // return exit();
        }
        foreach ($enrolments_no_mgr_approval as  $valueMgrEmails) 
        {
            if ($valueMgrEmails->created_at < Carbon::parse($enrolment_term_object->Enrol_Date_End)->subDays($remind_mgr_param)) {
                if ($now_date >= Carbon::parse($valueMgrEmails->created_at)->addDays($remind_mgr_param)) {
                    $arrRecipient[] = $valueMgrEmails->mgr_email; 
                    $recipient = $valueMgrEmails->mgr_email;

                    $staff = User::where('indexno', $valueMgrEmails->INDEXID)->first();
                    $input_course = PlacementForm::orderBy('id', 'desc')->where('Term', $enrolment_term)->where('INDEXID', $valueMgrEmails->INDEXID)->where('L', $valueMgrEmails->L)->first();

                    Mail::to($recipient)->send(new SendMailableReminderPlacement($input_course, $staff));
                    echo $recipient;
                    echo '<br>';
                    echo '<br>';
                }
            }
            if ($now_date->toDateString() == Carbon::parse($enrolment_term_object->Approval_Date_Limit)->toDateString()) {
                $recipient = $valueMgrEmails->mgr_email;

                $staff = User::where('indexno', $valueMgrEmails->INDEXID)->first();
                $input_course = PlacementForm::orderBy('id', 'desc')->where('Term', $enrolment_term)->where('INDEXID', $valueMgrEmails->INDEXID)->where('L', $valueMgrEmails->L)->first();

                Mail::to($recipient)->send(new SendMailableReminderPlacement($input_course, $staff));
            }
        } // end of foreach loop

        $remind_hr_param = Term::where('Term_Code', $enrolment_term)->value('Remind_HR_After');

        $arrDept = [];
        $arrHrEmails = [];
        $arr=[];
        $enrolments_no_hr_approval = PlacementForm::where('Term', $enrolment_term)->whereNull('is_self_pay_form')->whereNull('approval_hr')->where('approval', '1')->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])->get();

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

                        $formItems = PlacementForm::orderBy('Term', 'desc')
                                        ->where('INDEXID', $valueDept->INDEXID)
                                        ->where('Term', $enrolment_term)
                                        ->where('L', $valueDept->L)
                                        ->where('eform_submit_count', $valueDept->eform_submit_count)
                                        ->get();
                        $formfirst = PlacementForm::orderBy('Term', 'desc')
                                        ->where('INDEXID', $valueDept->INDEXID)
                                        ->where('Term', $enrolment_term)
                                        ->where('L', $valueDept->L)
                                        ->where('eform_submit_count', $valueDept->eform_submit_count)
                                        ->first();   
                        // $staff_name = $formfirst->users->name;
                        $staff_name = $formfirst->users->name;
                        $arr[] = $staff_name;
                        $mgr_email = $formfirst->mgr_email;    
                        $input_course = $formfirst; 
                        // Mail::to($fp_email_arr);
                        Mail::to($fp_email_arr)->send(new SendReminderEmailPlacementHR($formItems, $input_course, $staff_name, $mgr_email));
                    }
                }
            }

            if ($now_date->toDateString() == Carbon::parse($enrolment_term_object->Approval_Date_Limit_HR)->toDateString()) {
                $torgan = Torgan::where('Org name', $valueDept->DEPT)->first();
                $learning_partner = $torgan->has_learning_partner;

                if ($learning_partner == '1') {
                    $query_hr_email = FocalPoints::where('org_id', $torgan->OrgCode)->get(['email']); 
                    $fp_email = $query_hr_email->map(function ($val, $key) {
                        return $val->email;
                    });
                    $fp_email_arr = $fp_email->toArray();
                    $arrHrEmails[] = $fp_email_arr;

                    $formItems = PlacementForm::orderBy('Term', 'desc')
                                    ->where('INDEXID', $valueDept->INDEXID)
                                    ->where('Term', $enrolment_term)
                                    ->where('L', $valueDept->L)
                                    ->where('eform_submit_count', $valueDept->eform_submit_count)
                                    ->get();
                    $formfirst = PlacementForm::orderBy('Term', 'desc')
                                    ->where('INDEXID', $valueDept->INDEXID)
                                    ->where('Term', $enrolment_term)
                                    ->where('L', $valueDept->L)
                                    ->where('eform_submit_count', $valueDept->eform_submit_count)
                                    ->first();   
                    // $staff_name = $formfirst->users->name;
                    $staff_name = $formfirst->users->name;
                    $arr[] = $staff_name;
                    $mgr_email = $formfirst->mgr_email;    
                    $input_course = $formfirst; 
                    // Mail::to($fp_email_arr);
                    Mail::to($fp_email_arr)->send(new SendReminderEmailPlacementHR($formItems, $input_course, $staff_name, $mgr_email));
                }
            }            
        } // end of foreach loop
        
        // dd($arrRecipient, $enrolments_no_mgr_approval, $arrHrEmails,$arr);
        return 'reminder placement emails sent';
    }

    public function postPlacementInfo(Request $request)
    {   
        $index_id = $request->input('index_id');
        $language_id = $request->input('L'); 
        $course_id = $request->input('course_id');
        $term_id = $request->input('term_id');
        //$schedule_id is an array 
        $schedule_id = $request->input('schedule_id');
        $mgr_email = $request->input('mgr_email');
        $mgr_fname = $request->input('mgr_fname');
        $mgr_lname = $request->input('mgr_lname');
        $uniquecode = $request->input('CodeIndexID');
        $org = $request->input('org');
        $agreementBtn = $request->input('agreementBtn');
        // $contractDate = $request->input('contractDate');

        $this->validate($request, array(
            'mgr_email' => 'required|email',
            'placementLang' => 'required|integer',
            'course_preference_comment' => 'required|',
            'agreementBtn' => 'required|',
        ));

        $qryEformCount = PlacementForm::withTrashed()
            ->where('INDEXID', $index_id)
            ->where('Term', $term_id)
            ->orderBy('eform_submit_count', 'desc')->first();
           
        $eform_submit_count = 1;
        if(isset($qryEformCount->eform_submit_count)){
            $eform_submit_count = $qryEformCount->eform_submit_count + 1;    
        }

        $placementForm = new PlacementForm;
        $placementForm->L = $language_id;
        $placementForm->profile = $request->profile;
        $placementForm->Term = $term_id;
        $placementForm->INDEXID = $index_id;
        $placementForm->DEPT = $org;
        $placementForm->eform_submit_count = $eform_submit_count;
        $placementForm->mgr_email = $mgr_email;
        $placementForm->mgr_fname = $mgr_fname;
        $placementForm->mgr_lname = $mgr_lname;        
        $placementForm->placement_schedule_id = $request->placementLang;
        $placementForm->std_comments = $request->std_comment;
        $placementForm->agreementBtn = $request->agreementBtn;
        // $placementForm->contractDate = $request->contractDate;
        $placementForm->save();
        
        // mail student regarding placement form information
        $staff = Auth::user();
        $current_user = Auth::user()->indexno;
        $input_course = PlacementForm::orderBy('id', 'desc')->where('Term', $term_id)->where('INDEXID', $current_user)->where('L', $language_id)->first();

        Mail::to($mgr_email)->send(new MailPlacementTesttoApprover($input_course, $staff));
        // get newly created placement form record
        $latest_placement_form = placementForm::orderBy('id', 'desc')->where('INDEXID', Auth::user()->indexno)->where('Term', $term_id)->where('L', $language_id)->first();
        $placement_form_id = $latest_placement_form->id;
        $this->postPlacementInfoAdditional($request, $placement_form_id);
    }

    public function postSelfPayPlacementInfo(Request $request, $attachment_pay_file, $attachment_identity_file)
    {   
        $index_id = $request->input('index_id');
        $language_id = $request->input('L'); 
        $course_id = $request->input('course_id');
        $term_id = $request->input('term_id');
        //$schedule_id is an array 
        $schedule_id = $request->input('schedule_id');
        $mgr_email = $request->input('mgr_email');
        $mgr_fname = $request->input('mgr_fname');
        $mgr_lname = $request->input('mgr_lname');
        $uniquecode = $request->input('CodeIndexID');
        $org = $request->input('org');
        $agreementBtn = $request->input('agreementBtn');

        $this->validate($request, array(
            'placementLang' => 'required|integer',
            'agreementBtn' => 'required|',
            'course_preference_comment' => 'required|',
        ));

        $qryEformCount = PlacementForm::withTrashed()
            ->where('INDEXID', $index_id)
            ->where('Term', $term_id)
            ->orderBy('eform_submit_count', 'desc')->first();
           
        $eform_submit_count = 1;
        if(isset($qryEformCount->eform_submit_count)){
            $eform_submit_count = $qryEformCount->eform_submit_count + 1;    
        }

        $placementForm = new PlacementForm;
        $placementForm->L = $language_id;
        $placementForm->profile = $request->profile;
        $placementForm->Term = $term_id;
        $placementForm->INDEXID = $index_id;
        $placementForm->DEPT = $org;
        $placementForm->attachment_id = $attachment_identity_file->id;
        $placementForm->attachment_pay = $attachment_pay_file->id;
        $placementForm->is_self_pay_form = 1;
        $placementForm->eform_submit_count = $eform_submit_count;      
        $placementForm->placement_schedule_id = $request->placementLang;
        $placementForm->std_comments = $request->std_comment;
        $placementForm->consentBtn = $request->consentBtn;
        $placementForm->agreementBtn = $request->agreementBtn;
        $placementForm->save();
        // get newly created placement form record
        $latest_placement_form = placementForm::orderBy('id', 'desc')->where('INDEXID', Auth::user()->indexno)->where('Term', $term_id)->where('L', $language_id)->first();
        $placement_form_id = $latest_placement_form->id;
        $this->postPlacementInfoAdditional($request, $placement_form_id);
    }

    public function getPlacementInfo()
    {
        // place control to not access this route directly???
         
        // $languages = DB::table('languages')->pluck("name","code")->all();
        // $days = Day::pluck("Week_Day_Name","Week_Day_Name")->except('Sunday', 'Saturday')->all();
        // $latest_placement_form = placementForm::orderBy('id', 'desc')->where('INDEXID', Auth::user()->indexno)->first();

        // return view('form.myformplacement')->withLanguages($languages)->withDays($days)->withLatest_placement_form($latest_placement_form);
    }

    public function postPlacementInfoAdditional($request, $placement_form_id)
    {  
        $this->validate($request, array(
            'dayInput' => 'required|',
            'timeInput' => 'required|',
            'course_preference_comment' => 'required|',
        ));
        
        $dayInput = $request->dayInput;
        $timeInput = $request->timeInput;
        $implodeDay = implode('-', $dayInput);
        $implodeTime = implode('-', $timeInput);

        $data = PlacementForm::findorFail($placement_form_id);
        $data->dayInput = $implodeDay;
        $data->timeInput = $implodeTime;
        $data->course_preference_comment = $request->course_preference_comment;
        $data->save();

        if ($data->is_self_pay_form) {
            $request->request->add(['is_self_pay_form' => 1]);
            return $request;
        }       
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

        // $request->session()->put('Term', \Request::input('Term') );
        // dd($request);
        // if (is_null($request->Term) && $request->session()->has('Term') ) {
        //     dd($request->session()->get('Term'));
        // }
        
        if (is_null($request->Term) ) {
            $placement_forms = null;
            return view('placement_forms.index')->withPlacement_forms($placement_forms)->withLanguages($languages)->withOrg($org)->withTerms($terms);
        }
            $placement_forms = new PlacementForm;
            // $currentQueries = \Request::query();
            $queries = [];

            $columns = [
                'L', 'DEPT', 'Term',
            ];

            foreach ($columns as $column) {
                if (\Request::has($column)) {
                    $placement_forms = $placement_forms->where($column, \Request::input($column) );
                    
                    $queries[$column] = \Request::input($column);
                }
                
            } 
                if (\Request::has('search')) {
                    $name = \Request::input('search');
                    $placement_forms = $placement_forms->with('users')
                        ->whereHas('users', function($q) use ( $name) {
                            return $q->where('name', 'LIKE', '%' . $name . '%')->orWhere('email', 'LIKE', '%' . $name . '%');
                        });
                    $queries['search'] = \Request::input('search');
                }  

                if (\Request::has('sort')) {
                    $placement_forms = $placement_forms->where('selfpay_approval', '1')->orWhere('selfpay_approval', null)->orderBy('created_at', \Request::input('sort') );
                    $queries['sort'] = \Request::input('sort');
                }

            // $allQueries = array_merge($queries, $currentQueries);
            $placement_forms = $placement_forms->paginate(10)->appends($queries);
            return view('placement_forms.index')->withPlacement_forms($placement_forms)->withLanguages($languages)->withOrg($org)->withTerms($terms);
    }

    public function getFilteredPlacementForms(Request $request)
    {
        $languages = DB::table('languages')->pluck("name","code")->all();
        $org = Torgan::orderBy('Org Name', 'asc')->get(['Org Name','Org Full Name']);
        $terms = Term::orderBy('Term_Code', 'desc')->get();
        
        if (is_null($request->Term) ) {
            $placement_forms = null;
            return view('placement_forms.index')->withPlacement_forms($placement_forms)->withLanguages($languages)->withOrg($org)->withTerms($terms);
        }
            $placement_forms = new PlacementForm;
            // $currentQueries = \Request::query();
            $queries = [];

            $columns = [
                'L', 'DEPT', 'Term',
            ];

            foreach ($columns as $column) {
                if (\Request::has($column)) {
                    $placement_forms = $placement_forms->where($column, \Request::input($column) );
                    
                    $queries[$column] = \Request::input($column);
                }
                
            } 
                if (\Request::has('search')) {
                    $name = \Request::input('search');
                    $placement_forms = $placement_forms->with('users')
                        ->whereHas('users', function($q) use ( $name) {
                            return $q->where('name', 'LIKE', '%' . $name . '%')->orWhere('email', 'LIKE', '%' . $name . '%');
                        });
                    $queries['search'] = \Request::input('search');
                }  

                if (\Request::has('sort')) {
                    $placement_forms = $placement_forms->orderBy('created_at', \Request::input('sort') );
                    $queries['sort'] = \Request::input('sort');
                }

            // $allQueries = array_merge($queries, $currentQueries);
            $placement_forms = $placement_forms->whereNull('assigned_to_course')->paginate(20)->appends($queries);
            return view('placement_forms.filteredPlacementForms')->withPlacement_forms($placement_forms)->withLanguages($languages)->withOrg($org)->withTerms($terms);
    }

    public function edit($id)
    {
        $placement_form = PlacementForm::find($id);
        $waitlists = PlacementForm::with('waitlist')->where('INDEXID',$placement_form->INDEXID)->get();
        // dd($placement_form, $placement_student_index);
        return view('placement_forms.edit',compact('placement_form','waitlists'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, array(
                            'Term' => 'required|',
                            'INDEXID' => 'required|',
                            'L' => 'required|',
                            'decision' => 'required|',
                            'submit-approval' => 'required|',
                        )); 

        $placement_form = PlacementForm::find($id);
        if (isset($placement_form->convoked)) {
                $this->validate($request, array(
                                'course_id' => 'required|',
                                'schedule_id' => 'required|',
                            ));
                $placement_form->assigned_to_course = 1;
                $placement_form->schedule_id = $request->schedule_id;
                $placement_form->Te_Code = $request->course_id;
                $placement_form->Code = $request->course_id.'-'.$request->schedule_id.'-'.$request->Term;
                $placement_form->CodeIndexID = $request->course_id.'-'.$request->schedule_id.'-'.$request->Term.'-'.$request->INDEXID;
                $placement_form->save();
        } else {
            $placement_form->convoked = $request->decision;
            if( $request->decision == 1){
                $placement_form->save();
                // $staff_email = User::where('indexno', $request->INDEXID)->first();
                // Mail::to($staff_email)
                //             ->send(new XXX($request));
            } else {
                $this->validate($request, array(
                                'course_id' => 'required|',
                                'schedule_id' => 'required|',
                            ));
                $placement_form->assigned_to_course = 1;
                $placement_form->schedule_id = $request->schedule_id;
                $placement_form->Te_Code = $request->course_id;
                $placement_form->Code = $request->course_id.'-'.$request->schedule_id.'-'.$request->Term;
                $placement_form->CodeIndexID = $request->course_id.'-'.$request->schedule_id.'-'.$request->Term.'-'.$request->INDEXID;
                $placement_form->save();
            }
        }

        

        // // save comments in the comments table and associate it to the enrolment form
        // foreach ($forms as $form) {
        //     $admin_comment = new AdminCommentPlacement;
        //     $admin_comment->comments = $request->admin_comment_show;
        //     $admin_comment->placement_id = $form->id;
        //     $admin_comment->user_id = Auth::user()->id;
        //     $admin_comment->save();
        // }
        
        $request->session()->flash('success', 'Placement form record has been updated.'); 
        return redirect()->back();
    }
}