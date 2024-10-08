<?php

namespace App\Http\Controllers;

use App\AdditionalFile;
use App\Classroom;
use App\ContractFile;
use App\Course;
use App\Day;
use App\File;
use App\FocalPoints;
use App\Identity2File;
use App\Language;
use App\Mail\MailtoApprover;
use App\Mail\MailtoApproverHR;
use App\Preenrolment;
use App\Repo;
use App\SDDEXTR;
use App\Schedule;
use App\Term;
use App\Torgan;
use App\Traits\ValidateAndStoreAttachments;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Session;

class RepoController extends Controller
{
    use ValidateAndStoreAttachments;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('prevent-back-history');
        $this->middleware('opencloseenrolment');
        $this->middleware('checksubmissioncount');
        $this->middleware('checkcontinue');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $repos = Repo::orderBy('INDEXID', 'asc')->paginate(15);

        $terms = Term::all(['Term_Code', 'Term_Name']);

        return view('form.index', compact('repos', 'terms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // check if session flash msg exists, else re-route 
        $sess = $request->session()->get('_previous');
        if (is_null($sess)) {
            return redirect('home')->with('interdire-msg', 'Whoops! Looks like something went wrong... Please report the problem to clm_language@un.org');
        }
        $result = array();
        foreach ($sess as $val) {
            $result = $val;
        }
        // check if user did not directly access link   
        if ($request->session()->has('success') || $result == route('myform.create')) {

            //make collection values available
            $courses = Course::all();
            //get values directly from 'languages' table
            $languages = DB::table('languages')->pluck("name", "code")->all();
            $days = Day::pluck("Week_Day_Name", "Week_Day_Name")->except('Sunday', 'Saturday')->all();
            //get current year and date
            $now_date = Carbon::now()->toDateString();
            $now_year = Carbon::now()->year;

            //query the current term based on year and Term_End column is greater than today's date
            //whereYear('Term_End', $now_year)  
            $terms = \App\Helpers\GlobalFunction::instance()->currentEnrolTermObject();
            // Term::orderBy('Term_Code', 'desc')
            //                 ->whereDate('Term_End', '>=', $now_date)
            //                 //->first();
            //                 ->get()->min();
            if (is_null($terms)) {
                $request->session()->flash('enrolment_closed', 'Enrolment Form error: Current Enrolment Model does not exist in the table. Please contact and report to the Language Secretariat.');
                return redirect()->route('whatorg');
            }
            //query the next term based Term_Begin column is greater than today's date and then get min
            $next_term = Term::orderBy('Term_Code', 'desc')
                ->where('Term_Code', $terms->Term_Next)->get();
            // ->min();

            $prev_term = Term::orderBy('Term_Code', 'desc')
                // ->where('Term_End', '<', $now_date)->get()->max();
                ->where('Term_Code', $terms->Term_Prev)->get();

            //define user variable as User collection
            $user = Auth::user();
            //define user index number for query 
            $current_user = Auth::user()->indexno;
            //using DB method to query latest CodeIndexID of current_user
            $repos = Repo::orderBy('Term', 'desc')
                ->where('INDEXID', $current_user)->value('CodeIndexID');
            //not using DB method to get latest language course of current_user
            $student_last_term = Repo::orderBy('Term', 'desc')
                ->where('INDEXID', $current_user)->first(['Term']);
            if ($student_last_term == null) {
                $repos_lang = null;
                $org = Torgan::orderBy('Org name', 'asc')->get()->pluck('Org name', 'Org name');
                return view('form.myform', compact('courses', 'languages', 'terms', 'next_term', 'prev_term', 'repos', 'repos_lang', 'user', 'org', 'days'));
            }

            $repos_lang = Repo::orderBy('Term', 'desc')->where('Term', $student_last_term->Term)
                ->where('INDEXID', $current_user)->get();
            $org = Torgan::orderBy('Org name', 'asc')->get()->pluck('Org name', 'Org name');

            return view('form.myform', compact('courses', 'languages', 'terms', 'next_term', 'prev_term', 'repos', 'repos_lang', 'user', 'org', 'days'));
        } else {
            return redirect('home')->with('interdire-msg', 'You cannot go directly to that link. Click on "Register/Enrol Here" < ' . route('whatorg') . ' > from the Menu below and answer the mandatory question.');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
        $decision = $request->input('decision');
        $org = $request->input('org');
        $agreementBtn = $request->input('agreementBtn');
        $flexibleBtn = $request->input('flexibleBtn');
        $flexibleDay = $request->input('flexibleDay');
        $flexibleTime = $request->input('flexibleTime');
        $flexibleFormat = $request->input('flexibleFormat');
        // $contractDate = $request->input('contractDate');
        $std_comments = $request->input('regular_enrol_comment');

        $codex = [];
        //concatenate (implode) Code input before validation   
        if (!empty($schedule_id)) {
            //check if $code has no input
            if (empty($uniquecode)) {
                //loop based on $room_id count and store in $codex array
                for ($i = 0; $i < count($schedule_id); $i++) {
                    $codex[] = array($course_id, $schedule_id[$i], $term_id, $index_id);
                    //implode array elements and pass imploded string value to $codex array as element
                    $codex[$i] = implode('-', $codex[$i]);
                    //for each $codex array element stored, loop array merge method
                    //and output each array element to a string via $request->Code

                    foreach ($codex as $value) {
                        $request->merge(['CodeIndexID' => $value]);
                    }
                    var_dump($request->CodeIndexID);
                    // validate using custom validator based on unique validation helper
                    // with where clauses to specify customized validation 
                    // the validation below fails when CodeIndexID is already taken AND 
                    // deleted_at column is NULL which means it has not been cancelled AND
                    // not disapproved by manager or HR learning partner
                    $this->validate($request, array(
                        'CodeIndexID' => Rule::unique('tblLTP_Enrolment')->where(function ($query) use ($request) {
                            $uniqueCodex = $request->CodeIndexID;
                            $query->where('CodeIndexID', $uniqueCodex)
                                ->where('deleted_at', NULL);
                        })
                        // 'CodeIndexID' => 'unique:tblLTP_Enrolment,CodeIndexID|',
                    ));
                }
            }
        }

        // control the number of submitted enrolment forms
        $qryEformCount = Preenrolment::withTrashed()
            ->where('INDEXID', $index_id)
            ->where('Term', $term_id)
            ->orderBy('eform_submit_count', 'desc')->first();

        $eform_submit_count = 1;
        if (isset($qryEformCount->eform_submit_count)) {
            $eform_submit_count = $qryEformCount->eform_submit_count + 1;
        }

        // control the number of submitted courses per enrolment form submission
        // set default value of $form_counter to 1 and then add succeeding
        $lastValueCollection = Preenrolment::withTrashed()
            ->where('Te_Code', $course_id)
            ->where('INDEXID', $index_id)
            ->where('Term', $term_id)
            ->orderBy('form_counter', 'desc')->first();

        $form_counter = 1;
        if (isset($lastValueCollection->form_counter)) {
            $form_counter = $lastValueCollection->form_counter + 1;
        }

        $this->validateAttachments($request);

        $this->storeFrontIDattachment($request);

        // check if placement test form
        // if so, call method from PlacementFormController
        if ($request->placementDecisionB === '0') {
            app('App\Http\Controllers\PlacementFormController')->postPlacementInfo($request);

            if ($request->is_self_pay_form == 1) {
                $request->session()->flash('success', 'Your Placement Test request has been submitted.');
                return redirect()->route('thankyouSelfPay');
            }

            $request->session()->flash('success', 'Your Placement Test request has been submitted.');
            return redirect()->route('thankyouPlacement');
        }

        //validate other input fields outside of above loop
        $this->validate($request, array(
            'term_id' => 'required|',
            'schedule_id' => 'required|',
            'course_id' => 'required|',
            'L' => 'required|',
            // 'mgr_email' => 'required|email',
            'approval' => 'required',
            'org' => 'required',
            'flexibleDay' => 'required',
            'flexibleTime' => 'required',
            'flexibleFormat' => 'required',
            // 'regular_enrol_comment' => 'required',
            'agreementBtn' => 'required|',
        ));

        if ($org === 'MSU') {
            $this->validate($request, array(
                'countryMission' => 'required|'
            ));
        }

        if ($org === 'NGO') {
            $this->validate($request, array(
                'ngoName' => 'required|'
            ));
        }

        //loop for storing Code value to database
        $ingredients = [];
        for ($i = 0; $i < count($schedule_id); $i++) {
            $ingredients[] = new  Preenrolment([
                'CodeIndexID' => $course_id . '-' . $schedule_id[$i] . '-' . $term_id . '-' . $index_id,
                'Code' => $course_id . '-' . $schedule_id[$i] . '-' . $term_id,
                'schedule_id' => $schedule_id[$i],
                'L' => $language_id,
                'profile' => $request->profile,
                'Te_Code' => $course_id,
                'Term' => $term_id,
                'INDEXID' => $index_id,
                "created_at" =>  \Carbon\Carbon::now(),
                "updated_at" =>  \Carbon\Carbon::now(),
                // 'mgr_email' =>  $mgr_email,
                // 'mgr_lname' => $mgr_lname,
                // 'mgr_fname' => $mgr_fname,
                'approval' => $request->approval,
                'continue_bool' => 1,
                'DEPT' => $org,
                'country_mission' => $request->input('countryMission'),
                'ngo_name' => $request->input('ngoName'),
                'eform_submit_count' => $eform_submit_count,
                'form_counter' => $form_counter,
                'agreementBtn' => $agreementBtn,
                'flexibleBtn' => $flexibleBtn,
                'flexibleDay' => $flexibleDay,
                'flexibleTime' => $flexibleTime,
                'flexibleFormat' => $flexibleFormat,
                // 'contractDate' => $contractDate,
                'std_comments' => $std_comments,
            ]);
            foreach ($ingredients as $data) {
                $data->save();
                if (in_array($data->DEPT, ['UNOG', 'JIU', 'DDA', 'OIOS', 'DPKO'])) {
                    $data->update([
                        'overall_approval' => 1,
                    ]);
                }
            }
        }

        $this->storeOtherAttachments($ingredients, $request);

        //execute Mail class before redirect

        // $mgr_email = $request->mgr_email;
        // $staff = Auth::user();
        $current_user = Auth::user()->indexno;

        // $now_date = Carbon::now()->toDateString();
        // $terms = Term::orderBy('Term_Code', 'desc')
        //         ->whereDate('Term_End', '>=', $now_date)
        //         ->get()->min();
        // $next_term_code = Term::orderBy('Term_Code', 'desc')->where('Term_Code', '=', $terms->Term_Next)->get()->min('Term_Code');

        // $course = Preenrolment::orderBy('Term', 'desc')->orderBy('id', 'desc')->where('INDEXID', $current_user)->where('Term', $term_id)->value('Te_Code');
        // //query from Preenrolment table the needed information data to include in email
        // $input_course = Preenrolment::orderBy('Term', 'desc')->orderBy('id', 'desc')->where('INDEXID', $current_user)->where('Term', $term_id)->first();
        // $input_schedules = Preenrolment::orderBy('Term', 'desc')
        //                         ->where('INDEXID', $current_user)
        //                         ->where('Term', $term_id)
        //                         ->where('Te_Code', $course)
        //                         ->where('form_counter', $form_counter)
        //                         ->get();

        // Mail::to($mgr_email)->send(new MailtoApprover($input_course, $input_schedules, $staff));

        $staff = $index_id;
        $next_term_code = $term_id;
        $tecode = $course_id;
        $formcount = $form_counter;

        $this->sendApprovalEmailToHR($staff, $tecode, $formcount, $next_term_code);

        $sddextr_query = SDDEXTR::where('INDEXNO', $current_user)->firstOrFail();
        $sddextr_org = $sddextr_query->DEPT;
        if ($org == $sddextr_org) {

            // flash session success or errorBags 
            $request->session()->flash('success', 'Enrolment Form has been submitted.'); //laravel 5.4 version

            return redirect()->route('thankyou');
        } else {

            $this->update($request, $org, $current_user);
            $request->session()->flash('success', 'Enrolment Form has been submitted.'); //laravel 5.4 version
            $request->session()->flash('org_change_success', 'Organization has been updated');
            return redirect()->route('home');
        }
    }

    public function sendApprovalEmailToHR($staff, $tecode, $formcount, $next_term_code)
    {
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

        // query from Preenrolment table the needed information data to include in email
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
                ->send(new MailtoApproverHR($formItems, $input_course, $staff_name, $mgr_email, $term_en, $term_fr, $term_season_en, $term_season_fr, $term_year));
        }
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
    public function update(Request $request, $org, $current_user)
    {
        //update SDDEXTR table with new organization of the user after submit
        $sddextr_org = SDDEXTR::where('INDEXNO', $current_user)->firstOrFail();

        $sddextr_org->DEPT = $org;
        $sddextr_org->save();
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
