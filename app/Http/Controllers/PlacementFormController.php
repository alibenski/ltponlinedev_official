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
        $enrolment_term = Term::whereYear('Term_End', $now_year)
                        ->orderBy('Term_Code', 'desc')
                        ->where('Approval_Date_Limit', '>=', $now_date)
                        ->value('Term_Code');
        if (empty($enrolment_term)) {
            Log::info("Term is null. No Emails sent.");
            echo "Term is null. No Emails sent.";
            return exit();
        }

        $arrRecipient = [];
        $enrolments_no_mgr_approval = PlacementForm::where('Term', $enrolment_term)->whereNull('is_self_pay_form')->whereNull('approval')->select('INDEXID', 'L', 'eform_submit_count', 'mgr_email')->groupBy('INDEXID', 'L', 'eform_submit_count', 'mgr_email')->get();

        if ($enrolments_no_mgr_approval->isEmpty()) {
            Log::info("No email addresses to pick up. No Emails sent.");
            echo $enrolment_term;
            echo  $enrolments_no_mgr_approval;
            return exit();
        }
        foreach ($enrolments_no_mgr_approval as  $valueMgrEmails) 
        {
            $arrRecipient[] = $valueMgrEmails->mgr_email; 
            $recipient = $valueMgrEmails->mgr_email;

            $staff = User::where('indexno', $valueMgrEmails->INDEXID)->first();
            $input_course = PlacementForm::orderBy('id', 'desc')->where('Term', $enrolment_term)->where('INDEXID', $valueMgrEmails->INDEXID)->where('L', $valueMgrEmails->L)->first();

            Mail::to($recipient)->send(new SendMailableReminderPlacement($input_course, $staff));
            echo $recipient;
            echo '<br>';
            echo '<br>';
        }

        $arrDept = [];
        $arrHrEmails = [];
        $arr=[];
        $enrolments_no_hr_approval = PlacementForm::where('Term', $enrolment_term)->whereNull('is_self_pay_form')->whereNull('approval_hr')->where('approval', '1')->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS'])->get();

        foreach ($enrolments_no_hr_approval as $valueDept) {
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
    }

    public function getPlacementInfo()
    {
        // place control to not access this route directly???
         
        $languages = DB::table('languages')->pluck("name","code")->all();
        $days = Day::pluck("Week_Day_Name","Week_Day_Name")->except('Sunday', 'Saturday')->all();
        $latest_placement_form = placementForm::orderBy('id', 'desc')->where('INDEXID', Auth::user()->indexno)->first();

        return view('form.myformplacement')->withLanguages($languages)->withDays($days)->withLatest_placement_form($latest_placement_form);
    }

    public function postPlacementInfoAdditional(Request $request)
    {  
        $this->validate($request, array(
            'dayInput' => 'required|',
            'timeInput' => 'required|',
        ));
        
        $dayInput = $request->dayInput;
        $timeInput = $request->timeInput;
        $implodeDay = implode('-', $dayInput);
        $implodeTime = implode('-', $timeInput);

        $data = PlacementForm::findorFail($request->id);
        $data->dayInput = $implodeDay;
        $data->timeInput = $implodeTime;
        $data->save();

        if ($data->is_self_pay_form) {
            $request->session()->flash('success', 'Your answers have been saved.'); //laravel 5.4 version
            return redirect()->route('thankyouSelfPay');
        } 
        $request->session()->flash('success', 'Your answers have been saved.'); //laravel 5.4 version
        return redirect()->route('thankyou');
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

            if (\Request::has('sort')) {
                $placement_forms = $placement_forms->orderBy('created_at', \Request::input('sort') );
                $queries['sort'] = \Request::input('sort');
            }

        // $allQueries = array_merge($queries, $currentQueries);
        $placement_forms = $placement_forms->paginate(10)->appends($queries);
        return view('placement_forms.index')->withPlacement_forms($placement_forms)->withLanguages($languages)->withOrg($org)->withTerms($terms);
    }
}