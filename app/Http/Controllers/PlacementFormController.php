<?php

namespace App\Http\Controllers;

use App\Classroom;
use App\Course;
use App\Day;
use App\FocalPoints;
use App\Language;
use App\Mail\MailPlacementTesttoApprover;
use App\Mail\MailPlacementTesttoApproverHR;
use App\Mail\MailaboutPlacementCancel;
use App\Mail\MailtoApprover;
use App\Mail\SendMailableReminderPlacement;
use App\Mail\SendReminderEmailPlacementHR;
use App\PlacementForm;
use App\PlacementSchedule;
use App\Preenrolment;
use App\Repo;
use App\Room;
use App\SDDEXTR;
use App\Schedule;
use App\Teachers;
use App\Term;
use App\Time;
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
use Spatie\Permission\Models\Role;

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
        //     if ($now_date->toDateString() == Carbon::parse($enrolment_term_object->Approval_Date_Limit)->toDateString()) {
        //         $recipient = $valueMgrEmails->mgr_email;

        //         $staff = User::where('indexno', $valueMgrEmails->INDEXID)->first();
        //         $input_course = PlacementForm::orderBy('id', 'desc')->where('Term', $enrolment_term)->where('INDEXID', $valueMgrEmails->INDEXID)->where('L', $valueMgrEmails->L)->first();

        //         Mail::to($recipient)->send(new SendMailableReminderPlacement($input_course, $staff));
        //     }
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
                        Mail::to($fp_email_arr)->send(new SendReminderEmailPlacementHR($formItems, $input_course, $staff_name, $mgr_email,$term_en, $term_fr,$term_season_en, $term_season_fr,$term_year));
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
                    Mail::to($fp_email_arr)->send(new SendReminderEmailPlacementHR($formItems, $input_course, $staff_name, $mgr_email,$term_en, $term_fr,$term_season_en, $term_season_fr,$term_year));
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
        // $mgr_email = $request->input('mgr_email');
        // $mgr_fname = $request->input('mgr_fname');
        // $mgr_lname = $request->input('mgr_lname');
        $uniquecode = $request->input('CodeIndexID');
        $org = $request->input('org');
        $agreementBtn = $request->input('agreementBtn');
        // $contractDate = $request->input('contractDate');

        $this->validate($request, array(
            // 'mgr_email' => 'required|email',
            'placementLang' => 'required|integer',
            'approval' => 'required',
            'agreementBtn' => 'required|',
            'dayInput' => 'required|',
            'timeInput' => 'required|',
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
        $placementForm->eform_submit_count = $eform_submit_count;
        // $placementForm->mgr_email = $mgr_email;
        // $placementForm->mgr_fname = $mgr_fname;
        // $placementForm->mgr_lname = $mgr_lname;   
        $placementForm->approval = $request->approval;   
        $placementForm->placement_schedule_id = $request->placementLang;
        $placementForm->std_comments = $request->std_comment;
        $placementForm->agreementBtn = $request->agreementBtn;
        // $placementForm->contractDate = $request->contractDate;
        $placementForm->save();

        if (in_array($placementForm->DEPT, ['UNOG', 'JIU','DDA','OIOS','DPKO'])) {
            $placementForm->update([
                'overall_approval' => 1,
            ]);
        }  
        // execute mail class to send email to HR focal point if needed
        $staff = $index_id;
        $next_term_code = $term_id;
        $lang = $language_id;
        $formcount = $eform_submit_count;

        $this->sendPlacementApprovalEmailToHR($staff, $next_term_code, $lang, $formcount);

            // $staff = Auth::user();
            // $current_user = Auth::user()->indexno;
            // $input_course = PlacementForm::orderBy('id', 'desc')->where('Term', $term_id)->where('INDEXID', $current_user)->where('L', $language_id)->first();

            // Mail::to($mgr_email)->send(new MailPlacementTesttoApprover($input_course, $staff));
        
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

    public function sendPlacementApprovalEmailToHR($staff, $next_term_code, $lang, $formcount)
    {
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

        // query student email from users model via index number in placement form model
        $staff_name = $formfirst->users->name;
        $staff_email = $formfirst->users->email;
        $staff_index = $formfirst->INDEXID;   
        $mgr_email = $formfirst->mgr_email;
        
        // get term values
        $term = $next_term_code;
        // get term values and convert to strings
        $term_en = Term::where('Term_Code', $term)->first()->Term_Name;
        $term_fr = Term::where('Term_Code', $term)->first()->Term_Name_Fr;
        
        $term_season_en = Term::where('Term_Code', $term)->first()->Comments;
        $term_season_fr = Term::where('Term_Code', $term)->first()->Comments_fr;

        $term_date_time = Term::where('Term_Code', $term)->first()->Term_Begin;
        $term_year = new Carbon($term_date_time);
        $term_year = $term_year->year;

        // query from placement form table the needed information data to include in email
        $input_course = $formfirst; 

        // check the organization of the student to know which email process is followed by the system
        $org = $formfirst->DEPT; 

        $torgan = Torgan::where('Org name', $org)->first();
        $learning_partner = $torgan->has_learning_partner;

        if ($learning_partner == '1') {
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
                    ->send(new MailPlacementTesttoApproverHR($formItems, $input_course, $staff_name, $mgr_email,$term_en, $term_fr,$term_season_en, $term_season_fr,$term_year));
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

        if (!Session::has('Term') ) {
            $placement_forms = null;
            return view('placement_forms.index')->withPlacement_forms($placement_forms)->withLanguages($languages)->withOrg($org)->withTerms($terms);
        }
            $placement_forms = new PlacementForm;
            // $currentQueries = \Request::query();
            $queries = [];

            $columns = [
                'L', 'DEPT', 'is_self_pay_form', 'overall_approval', 
            ];

            foreach ($columns as $column) {
                if (\Request::has($column)) {
                    $placement_forms = $placement_forms->where($column, \Request::input($column) );
                    
                    $queries[$column] = \Request::input($column);
                }
                
            } 

                if (Session::has('Term')) {
                    $placement_forms = $placement_forms->where('Term', Session::get('Term') );
                    $queries['Term'] = Session::get('Term');
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

                if (\Request::exists('approval_hr')) {
                    if (is_null(\Request::input('approval_hr'))) {
                        $placement_forms = $placement_forms->whereNotIn('DEPT', ['UNOG', 'JIU','DDA','OIOS','DPKO'])->whereNull('is_self_pay_form')->whereNull('approval_hr');
                        $queries['approval_hr'] = '';
                    }
                }

            // $allQueries = array_merge($queries, $currentQueries);
            $placement_forms = $placement_forms->withTrashed()->paginate(20)->appends($queries);
            return view('placement_forms.index')->withPlacement_forms($placement_forms)->withLanguages($languages)->withOrg($org)->withTerms($terms);
    }

    /**
     * View of simple list table of placement forms with overall_approval = 1 
     */
    public function getApprovedPlacementFormsView(Request $request)
    {   
        if (!Session::has('Term') ) {
            $request->session()->flash('error', 'Term is not set.');
            return view('admin_dashboard');
        }

            $placement_forms = new PlacementForm;
            // $currentQueries = \Request::query();
            $queries = [];

            $columns = [
                'L', 'DEPT', 'is_self_pay_form',
            ];

            foreach ($columns as $column) {
                if (\Request::has($column)) {
                    $placement_forms = $placement_forms->where($column, \Request::input($column) );
                    
                    $queries[$column] = \Request::input($column);
                }
                
            } 


                if (Session::has('Term')) {
                    $placement_forms = $placement_forms->where('Term', Session::get('Term') );
                    $queries['Term'] = Session::get('Term');
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
            $placement_forms = $placement_forms->where('overall_approval', 1)->get();
            return view('placement_forms.approvedPlacementForms', compact('placement_forms'));
    }

    /**
     * View for Placement Test forms which have not been assigned to a course
     * @param  Request $request 
     * @return html           View with filter
     */
    public function getFilteredPlacementForms(Request $request)
    {      
        $languages = DB::table('languages')->pluck("name","code")->all();
        $org = Torgan::orderBy('Org Name', 'asc')->get(['Org Name','Org Full Name']);

            $placement_forms = new PlacementForm;
            $queries = [];

            $columns = [
                'L', 'DEPT', 'is_self_pay_form', 'overall_approval', 
            ];

            foreach ($columns as $column) {
                if (\Request::has($column)) {
                    $placement_forms = $placement_forms->where($column, \Request::input($column) );
                    $queries[$column] = \Request::input($column);
                }  
            } 

                if (Session::has('Term')) {
                        $placement_forms = $placement_forms->where('Term', Session::get('Term') );
                        $queries['Term'] = Session::get('Term');
                }

                if (\Request::has('not_assigned')) {
                    $placement_forms = $placement_forms->whereNull('assigned_to_course');
                    $queries['not_assigned'] = \Request::input('not_assigned');
                }

                if (\Request::has('search')) {
                        $name = \Request::input('search');
                        $placement_forms = $placement_forms->with('users')
                            ->whereHas('users', function($q) use ( $name) {
                                return $q->where('name', 'LIKE', '%' . $name . '%')->orWhere('email', 'LIKE', '%' . $name . '%');
                            });
                        $queries['search'] = \Request::input('search');
                }

            $count = new PlacementForm;
            $columns2 = [
                'L', 'DEPT', 'is_self_pay_form', 'overall_approval',
            ];
            foreach ($columns2 as $column2) {
                if (\Request::has($column2)) {
                    $count = $count->where($column2, \Request::input($column2) );
                }  
            } 
                if (Session::has('Term')) {
                        $count = $count->where('Term', Session::get('Term') );
                }
                if (\Request::has('search')) {
                        $name2 = \Request::input('search');
                        $count = $count->with('users')
                            ->whereHas('users', function($q2) use ( $name2) {
                                return $q2->where('name', 'LIKE', '%' . $name2 . '%')->orWhere('email', 'LIKE', '%' . $name2 . '%');
                            });
                }
            $count = $count->where('overall_approval', 1)->whereNull('assigned_to_course')->get()->count();

            // $placement_forms = $placement_forms->whereNull('assigned_to_course')->paginate(20)->appends($queries);
            $placement_forms = $placement_forms->where('overall_approval', 1)->paginate(20)->appends($queries);
            return view('placement_forms.filteredPlacementForms')->withPlacement_forms($placement_forms)->withCount($count)->withLanguages($languages)->withOrg($org);            
    }

    /**
     * Page/View to convoke students to take a placement examination
     * Not used at the moment
     */
    public function edit($id)
    {
        $placement_form = PlacementForm::find($id);
        $waitlists = PlacementForm::with('waitlist')->where('INDEXID',$placement_form->INDEXID)->get();
        $times = Time::all();
        return view('placement_forms.edit',compact('placement_form','waitlists', 'times'));
    }

    /**
     * Page/View to assign course to a placement examination
     */
    public function editAssignCourse($id)
    {
        $languages = DB::table('languages')->pluck("name","code")->all();
        $placement_form = PlacementForm::find($id);
        
        $prev_termCode = Term::where('Term_Code', $placement_form->Term)->first()->Term_Prev;
        $waitlists = Repo::where('INDEXID',$placement_form->INDEXID)
            ->where('Term',$prev_termCode)
            ->whereHas('classrooms', function ($query) {
                    $query->whereNull('Tch_ID')
                            ->orWhere('Tch_ID', '=', 'TBD')
                            ;
                    })
            ->get();

        $historical_data = Repo::orderBy('Term', 'desc')->where('INDEXID', $placement_form->INDEXID)->get();
        
        // dd($placement_form, $placement_student_index);
        return view('placement_forms.editAssignCourse',compact('languages','placement_form','waitlists','historical_data'));
    }

    /**
     * Update resource with convoked field = '1'. This method is currently not used
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
                            'Term' => 'required|',
                            'INDEXID' => 'required|',
                            'L' => 'required|',
                            'decision' => 'required|',
                            'submit-approval' => 'required|',
                        ]); 

        $placement_form = PlacementForm::find($id);
        
        if($placement_form->L != 'F'){

            $this->validate($request,[
                                'placement_time' => 'required',
                            ]);     
        }

        if (isset($placement_form->convoked)) {
                // $this->assignCourseToPlacement($request, $id);
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
            $placement_form->convoked = $request->decision; // 'convoked' field = 1
            if( $request->decision == 1){
                $placement_form->placement_time = $request->placement_time;
                $placement_form->save();
                // send email convocation to student
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
        
        // $request->session()->flash('success', 'Placement form record has been updated.'); 
        return redirect()->back();
    }

    /**
     * Update resource with assigned course with convoked input field 
     */
    public function assignCourseToPlacement(Request $request, $id)
    {
        $code_index_id = $request->course_id.'-'.$request->schedule_id.'-'.$request->Term.'-'.$request->INDEXID;
        $request->request->add(['CodeIndexID' => $code_index_id]);

        $this->validate($request, array(
            'CodeIndexID' => 'unique:tblLTP_Placement_Forms,CodeIndexID|',
            'Term' => 'required|',
            'INDEXID' => 'required|',
            'L' => 'required|',
            'convoked' => 'required|',
            'submit-approval' => 'required|',
            'course_id' => 'required|',
            'schedule_id' => 'required|',
        )); 

        $placement_form = PlacementForm::find($id);
        $placement_form->convoked = $request->convoked;
        $placement_form->Result = $request->Result;
        $placement_form->assigned_to_course = 1;

        if (!is_null($request->admin_plform_comment)) {
            $placement_form->admin_plform_comment = $request->admin_plform_comment;
        }
            
        $placement_form->updated_by_admin = 1;
        $placement_form->modified_by = Auth::user()->id;
        $placement_form->schedule_id = $request->schedule_id;
        $placement_form->Te_Code = $request->course_id;
        $placement_form->L = $request->L;
        $placement_form->Code = $request->course_id.'-'.$request->schedule_id.'-'.$request->Term;
        $placement_form->CodeIndexID = $request->course_id.'-'.$request->schedule_id.'-'.$request->Term.'-'.$request->INDEXID;
        $placement_form->save();
    
        // $request->session()->flash('success', 'Placement form record has been updated. You can now close this window/tab.'); 
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $staff, $lang, $term, $eform)
    {
        $admin_id = Auth::user()->id;

        //query submitted forms based from tblLTP_Enrolment table
        $forms = PlacementForm::orderBy('Term', 'desc')
                ->where('L', $lang)
                ->where('INDEXID', '=', $staff)
                ->where('Term', $term )
                ->where('eform_submit_count', $eform )
                ->get();
        $display_language = PlacementForm::orderBy('Term', 'desc')
                ->where('L', $lang)
                ->where('INDEXID', '=', $staff)
                ->where('Term', $term )
                ->where('eform_submit_count', $eform )
                ->first();
        
        //get email address of the Manager
        $mgr_email = $forms->pluck('mgr_email')->first();

        // get is_self_pay_form value
        $is_self_pay_form = $forms->pluck('is_self_pay_form')->first();

        //if self-paying placement form
        if ($is_self_pay_form == 1){
            $enrol_form = [];
            for ($i = 0; $i < count($forms); $i++) {
                $enrol_form = $forms[$i]->id;
                $delform = PlacementForm::find($enrol_form);
                $delform->admin_plform_cancel_comment = $request->admin_plform_cancel_comment;
                // $delform->cancelled_by_student = 1;
                $delform->cancelled_by_admin = $admin_id;
                $delform->save();
                $delform->delete();
            }
            session()->flash('cancel_success', 'Placement Test Request for '.$display_language->languages->name. ' has been cancelled.');
            return redirect()->back();
        }

        $staff_member_name = $forms->first()->users->name;
        //email notification to Manager    
            //     Mail::to($mgr_email)->send(new MailaboutPlacementCancel($forms, $display_language, $staff_member_name));
        
        // get term values and convert to strings
        $term_en = Term::where('Term_Code', $term)->first()->Term_Name;
        $term_fr = Term::where('Term_Code', $term)->first()->Term_Name_Fr;
        
        $term_season_en = Term::where('Term_Code', $term)->first()->Comments;
        $term_season_fr = Term::where('Term_Code', $term)->first()->Comments_fr;

        $term_date_time = Term::where('Term_Code', $term)->first()->Term_Begin;
        $term_year = new Carbon($term_date_time);
        $term_year = $term_year->year;

        //email notification to CLM Partner
        $org = $display_language->DEPT;

        $torgan = Torgan::where('Org name', $org)->first();
        $learning_partner = $torgan->has_learning_partner;

        // if there is a learning partner, email them as well
        if ($learning_partner == '1'){
            
            // email to HR Learning Partner of $other_org
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
                    ->send(new MailaboutPlacementCancel($forms, $display_language, $staff_member_name, $term_season_en, $term_year));

        }

        $enrol_form = [];
        for ($i = 0; $i < count($forms); $i++) {
            $enrol_form = $forms[$i]->id;
            $delform = PlacementForm::find($enrol_form);
            $delform->admin_plform_cancel_comment = $request->admin_plform_cancel_comment;
            // $delform->cancelled_by_student = 1;
            $delform->cancelled_by_admin = $admin_id;
            $delform->save();
            $delform->delete();
        }

        session()->flash('cancel_success', 'Placement Form Request for '.$display_language->languages->name. ' has been cancelled. If necessary, an email has been sent to the HR/Staff Development Office of the student.');
        return redirect()->back();
    }

    public function ajaxPlacementComments(Request $request)
    {
        if($request->ajax()){
            $student_enrolments = PlacementForm::withTrashed()
            ->where('INDEXID', $request->indexno)
            ->where('Term', $request->term)
            ->where('L', $request->L)
            ->where('eform_submit_count', $request->eform_submit_count)
            ->get();

            $std_comment = $student_enrolments->first()->std_comments;
            $course_preference_comment = $student_enrolments->first()->course_preference_comment;
            $timeInput = $student_enrolments->first()->timeInput;
            $dayInput = $student_enrolments->first()->dayInput;

            $data = [$std_comment, $course_preference_comment, $timeInput, $dayInput];
            return response()->json($data);
        }
    }

    /**
     * Main one-page view for managing placement exams
     * @return \Illuminate\Http\Response 
     */
    public function manageExamView(Request $request)
    {
        $user = Auth::user();
        if (!Session::has('Term') ) {
            if ($user->hasAnyRole(['Teacher FP', 'Teacher'])) {
                $request->session()->flash('error', 'Term is not set.');
                return redirect(route('teacher-dashboard'));
            } elseif ($user->hasRole('Admin')) {
                $request->session()->flash('error', 'Term is not set.');
                return redirect(route('admin_dashboard'));
            } else {
                $request->session()->flash('error', 'Unauthorized action.');
                return view('home');
            }
        }

        $languages = DB::table('languages')->pluck("name","code")->all();
        return view('placement_forms.manage_exam_view', compact('languages'));
    }

    public function manageExamTable(Request $request)
    {
        if ($request->ajax()) {
            
            if (!Session::has('Term') ) {
                $request->session()->flash('error', 'Term is not set.');
                return view('home');
            }

                $placement_forms = new PlacementForm;

                $columns = [
                    'L',
                ];

                foreach ($columns as $column) {
                    if (\Request::has($column)) {
                        $placement_forms = $placement_forms->where($column, \Request::input($column) );
                        
                        $queries[$column] = \Request::input($column);
                    }
                    
                } 


                    if (Session::has('Term')) {
                        $placement_forms = $placement_forms->where('Term', Session::get('Term') );
                        $queries['Term'] = Session::get('Term');
                    }


            $placement_forms = $placement_forms->where('overall_approval', 1)->get();
            $term = Term::where('Term_Code', Session::get('Term'))->first();
            $rooms = Room::pluck("Rl_Room","Rl_Room")->all();
            $teachers = Teachers::where('In_Out', '1')->get();

            $data = view('placement_forms.manage_exam_table', compact('placement_forms', 'term', 'rooms', 'teachers'))->render();
            return response()->json(['options'=>$data]);
        }
    }
}