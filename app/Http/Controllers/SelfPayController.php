<?php

namespace App\Http\Controllers;

use App\Classroom;
use App\Course;
use App\Day;
use App\File;
use App\Http\Controllers\PlacementFormController;
use App\Language;
use App\Mail\MailtoApprover;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Session;

class SelfPayController extends Controller
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
        $this->middleware('opencloseenrolment')->except(['index','update']);
        // $this->middleware('checksubmissionselfpay')->except(['index','update']);
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



        if (is_null($request->Term)) {
            $selfpayforms = null;
            return view('selfpayforms.index')->withSelfpayforms($selfpayforms)->withLanguages($languages)->withOrg($org)->withTerms($terms);
        }

        $selfpayforms = Preenrolment::select( 'INDEXID','Term','L','Te_Code','attachment_id', 'attachment_pay', 'created_at')->where('is_self_pay_form', '1')->groupBy('INDEXID','Term','L','Te_Code', 'attachment_id', 'attachment_pay', 'created_at');
        // ->orderBy('created_at', 'asc')->get();
        // $selfpayforms = new Preenrolment;
        // $currentQueries = \Request::query();
        $queries = [];

        $columns = [
            'L', 'DEPT', 'Term',
        ];

        
        foreach ($columns as $column) {
            if (\Request::has($column)) {
                $selfpayforms = $selfpayforms->where($column, \Request::input($column) );
                $queries[$column] = \Request::input($column);
            }

        } 

            if (\Request::has('sort')) {
                $selfpayforms = $selfpayforms->orderBy('created_at', \Request::input('sort') );
                $queries['sort'] = \Request::input('sort');
            }

        $selfpayforms = $selfpayforms->paginate(10)->appends($queries);
        return view('selfpayforms.index')->withSelfpayforms($selfpayforms)->withLanguages($languages)->withOrg($org)->withTerms($terms);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {  
        $sess = $request->session()->get('_previous');
        $result = array();
            foreach($sess as $val)
            {
              $result = $val;
            }
        // 'success' flash Session attribute comes from whatform() method @Homecontroller  
        // check if user did not directly access link   
        if ($request->session()->has('success') || $result == route('selfpayform.create')) {

        //make collection values available
        $courses = Course::all();
        //get values directly from 'languages' table
        $languages = DB::table('languages')->pluck("name","code")->all();
        $days = Day::pluck("Week_Day_Name","Week_Day_Name")->except('Sunday', 'Saturday')->all();
        //get current year and date
        $now_date = Carbon::now()->toDateString();
        $now_year = Carbon::now()->year;

        //query the current term based on year and Term_End column is greater than today's date
        //whereYear('Term_End', $now_year)  
        $terms = \App\Helpers\GlobalFunction::instance()->currentEnrolTermObject();
        // $terms = Term::orderBy('Term_Code', 'desc')
        //                 ->whereDate('Term_End', '>=', $now_date)
        //                 //->first();
        //                 ->get()->min();
        if (is_null($terms)) {
                $request->session()->flash('enrolment_closed', 'Enrolment Form error: Current Enrolment Model does not exist in the table. Please contact and report to the Language Secretariat.');
                return redirect()->route('whatorg');
        }
        //query the next term based Term_Begin column is greater than today's date and then get min
        $next_term = Term::orderBy('Term_Code', 'desc')
                        ->where('Term_Code', '=', $terms->Term_Next)->get();
                        // ->min();

        $prev_term = Term::orderBy('Term_Code', 'desc')
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
                $org = Torgan::orderBy('Org Name', 'asc')->get()->pluck('Org name','Org name');
                return view('form.myform3')->withCourses($courses)->withLanguages($languages)->withTerms($terms)->withNext_term($next_term)->withPrev_term($prev_term)->withRepos($repos)->withRepos_lang($repos_lang)->withUser($user)->withOrg($org)->withDays($days);
            }         

        $repos_lang = Repo::orderBy('Term', 'desc')->where('Term', $student_last_term->Term)
            ->where('INDEXID', $current_user)->get();
        $org = Torgan::orderBy('Org Name', 'asc')->get()->pluck('Org name','Org name');

        return view('form.myform3')->withCourses($courses)->withLanguages($languages)->withTerms($terms)->withNext_term($next_term)->withPrev_term($prev_term)->withRepos($repos)->withRepos_lang($repos_lang)->withUser($user)->withOrg($org)->withDays($days);
        } else {
        return redirect('home')->with('interdire-msg', 'You cannot go directly to that link. Click on "Register/Enrol Here" < '. route('whatorg') .' > from the Menu below and answer the mandatory question.');
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
        $uniquecode = $request->input('CodeIndexID');
        $decision = $request->input('decision');
        $org = $request->input('org');
        $agreementBtn = $request->input('agreementBtn');
        $consentBtn = $request->input('consentBtn');
        $flexibleBtn = $request->input('flexibleBtn');
        $codex = [];     
        //concatenate (implode) Code input before validation   
        //check if $code has no input
        if ( empty( $uniquecode ) ) {
            //loop based on $room_id count and store in $codex array
            for ($i=0; $i < count($schedule_id); $i++) { 
                $codex[] = array( $course_id,$schedule_id[$i],$term_id,$index_id );
                //implode array elements and pass imploded string value to $codex array as element
                $codex[$i] = implode('-', $codex[$i]);
                //for each $codex array element stored, loop array merge method
                //and output each array element to a string via $request->Code

                foreach ($codex as $value) {
                    $request->merge( [ 'CodeIndexID' => $value ] );
                }
                        //var_dump($request->CodeIndexID);
                        // the validation below fails when CodeIndexID is already taken AND 
                        // deleted_at column is NULL which means it has not been cancelled AND
                        // there is an existing self-pay form
                        $this->validate($request, array(
                            'CodeIndexID' => Rule::unique('tblLTP_Enrolment')->where(function ($query) use($request) {
                                    $uniqueCodex = $request->CodeIndexID;
                                    $query->where('CodeIndexID', $uniqueCodex)
                                        ->where('deleted_at', NULL)
                                        ->where('is_self_pay_form', 1);
                                })
                        ));
            }                              
        }
                    // 1st part of validate other input fields 
                        $this->validate($request, array(
                            'identityfile' => 'required|mimes:pdf,doc,docx|max:8000',
                            'payfile' => 'required|mimes:pdf,doc,docx|max:8000',
                        )); 
        // control the number of submitted enrolment forms
        $qryEformCount = Preenrolment::withTrashed()
            ->where('INDEXID', $index_id)
            ->where('Term', $term_id)
            ->orderBy('eform_submit_count', 'desc')->first();
           
        $eform_submit_count = 1;
        if(isset($qryEformCount->eform_submit_count)){
            $eform_submit_count = $qryEformCount->eform_submit_count + 1;    
        }

        // set default value of $form_counter to 1 and then add succeeding
        $lastValueCollection = Preenrolment::withTrashed()
            ->where('Te_Code', $course_id)
            ->where('INDEXID', $index_id)
            ->where('Term', $term_id)
            ->orderBy('form_counter', 'desc')->first();
            
        $form_counter = 1;
        if(isset($lastValueCollection->form_counter)){
            $form_counter = $lastValueCollection->form_counter + 1;    
        }

        //Store the attachments to storage path and save in db table
        if ($request->hasFile('identityfile')){
            $request->file('identityfile');
            $filename = $index_id.'_'.$term_id.'_'.$course_id.'.'.$request->identityfile->extension();
            //Store attachment
            $filestore = Storage::putFileAs('public/pdf/'.$index_id, $request->file('identityfile'), 'id_'.$index_id.'_'.$term_id.'_'.$course_id.'.'.$request->identityfile->extension());
            //Create new record in db table
            $attachment_identity_file = new File([
                    'filename' => $filename,
                    'size' => $request->identityfile->getClientSize(),
                    'path' => $filestore,
                            ]); 
            $attachment_identity_file->save();
        }
        if ($request->hasFile('payfile')){
            $request->file('payfile');
            $filename = $index_id.'_'.$term_id.'_'.$course_id.'.'.$request->payfile->extension();
            //Store attachment
            $filestore = Storage::putFileAs('public/pdf/'.$index_id, $request->file('payfile'), 'payment_'.$index_id.'_'.$term_id.'_'.$course_id.'.'.$request->payfile->extension());
            //Create new record in db table
            $attachment_pay_file = new File([
                    'filename' => $filename,
                    'size' => $request->payfile->getClientSize(),
                    'path' => $filestore,
                            ]); 
            $attachment_pay_file->save();
        }  

        // check if placement test form
        // if so, call method from PlacementFormController
        if ($request->placementDecisionB === '0') {
            app('App\Http\Controllers\PlacementFormController')->postSelfPayPlacementInfo($request, $attachment_pay_file, $attachment_identity_file);
            // $request->session()->flash('success', 'Your Placement Test request has been submitted.'); //laravel 5.4 version
            // return redirect()->route('placementinfo');
            if ($request->is_self_pay_form == 1) {
            $request->session()->flash('success', 'Your Placement Test request has been submitted.'); //laravel 5.4 version
            return redirect()->route('thankyouSelfPay');
            } 
            $request->session()->flash('success', 'Your Placement Test request has been submitted.'); //laravel 5.4 version
            return redirect()->route('thankyou');
        }

                    // 2nd part of validate other input fields 
                        $this->validate($request, array(
                            'term_id' => 'required|',
                            'schedule_id' => 'required|',
                            'course_id' => 'required|',
                            'L' => 'required|',
                        )); 

        //loop for storing Code value to database
        $ingredients = [];        
        for ($i = 0; $i < count($schedule_id); $i++) {
            $ingredients[] = new  Preenrolment([
                'CodeIndexID' => $course_id.'-'.$schedule_id[$i].'-'.$term_id.'-'.$index_id,
                'Code' => $course_id.'-'.$schedule_id[$i].'-'.$term_id,
                'schedule_id' => $schedule_id[$i],
                'L' => $language_id,
                'profile' => $request->profile,
                'Te_Code' => $course_id,
                'Term' => $term_id,
                'INDEXID' => $index_id,
                "created_at" =>  \Carbon\Carbon::now(),
                "updated_at" =>  \Carbon\Carbon::now(),
                'continue_bool' => $decision,
                'attachment_id' => $attachment_identity_file->id,
                'attachment_pay' => $attachment_pay_file->id,
                'is_self_pay_form' => 1,
                'eform_submit_count' => $eform_submit_count,
                'form_counter' => $form_counter, 
                'DEPT' => $org,
                'agreementBtn' => $agreementBtn,
                'consentBtn' => $consentBtn,
                'flexibleBtn' => $flexibleBtn,  
                ]); 

                    foreach ($ingredients as $data) {
                        $data->save();                    
                    }
        }
        
        //execute Mail class before redirect         
        $mgr_email = $request->mgr_email;
        $staff = Auth::user();
        $current_user = Auth::user()->indexno;
        $now_date = Carbon::now()->toDateString();
        $terms = Term::orderBy('Term_Code', 'desc')
                ->whereDate('Term_End', '>=', $now_date)
                ->get()->min();
        $next_term_code = Term::orderBy('Term_Code', 'desc')->where('Term_Code', '=', $terms->Term_Next)->get()->min('Term_Code');
        $course = Preenrolment::orderBy('Term', 'desc')->orderBy('id', 'desc')
                                ->where('INDEXID', $current_user)
                                ->value('Te_Code');
        //query from Preenrolment table the needed information data to include in email
        $input_course = Preenrolment::orderBy('Term', 'desc')->orderBy('id', 'desc')
                                ->where('INDEXID', $current_user)
                                ->first();
        $input_schedules = Preenrolment::orderBy('Term', 'desc')
                                ->where('INDEXID', $current_user)
                                ->where('Term', $next_term_code)
                                ->where('Te_Code', $course)
                                ->where('form_counter', $form_counter)
                                ->get();

        // email confirmation message to student enrolment form has been received 
        // Mail::to($mgr_email)->send(new MailtoApprover($input_course, $input_schedules, $staff));

        $request->session()->flash('success', 'Thank you. The enrolment form has been submitted to the Language Secretariat for processing.'); 

        return redirect()->route('thankyouSelfPay');   
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
