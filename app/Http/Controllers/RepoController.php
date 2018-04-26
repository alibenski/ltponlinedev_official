<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailtoApprover;
use App\Language;
use App\Course;
use App\User;
use App\Repo;
use App\Term;
use App\Classroom;
use App\Schedule;
use App\Preenrolment;
use App\SDDEXTR;
use App\Torgan;
use Session;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;

class RepoController extends Controller
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

        return view('form.index')->withRepos($repos)->withTerms($terms);
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
        $result = array();
            foreach($sess as $val)
            {
              $result = $val;
            }
        // check if user did not directly access link   
        if ($request->session()->has('success') || $result == route('myform.create')){

            //make collection values available
            $courses = Course::all();
            //get values directly from 'languages' table
            $languages = DB::table('languages')->pluck("name","code")->all();

            //get current year and date
            $now_date = Carbon::now()->toDateString();
            $now_year = Carbon::now()->year;

            //query the current term based on year and Term_End column is greater than today's date
            //whereYear('Term_End', $now_year)  
            $terms = Term::orderBy('Term_Code', 'desc')
                            ->whereDate('Term_End', '>=', $now_date)
                            //->first();
                            ->get()->min();

            //query the next term based Term_Begin column is greater than today's date and then get min
            $next_term = Term::orderBy('Term_Code', 'desc')
                            ->where('Term_Code', '=', $terms->Term_Next)->get()->min();

            $prev_term = Term::orderBy('Term_Code', 'desc')
                            ->where('Term_End', '<', $now_date)->get()->max();

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
                    return view('form.myform')->withCourses($courses)->withLanguages($languages)->withTerms($terms)->withNext_term($next_term)->withPrev_term($prev_term)->withRepos($repos)->withRepos_lang($repos_lang)->withUser($user)->withOrg($org);
                }    

            $repos_lang = Repo::orderBy('Term', 'desc')->where('Term', $student_last_term->Term)
                ->where('INDEXID', $current_user)->get();
            $org = Torgan::orderBy('Org Name', 'asc')->get()->pluck('Org name','Org name');

            return view('form.myform')->withCourses($courses)->withLanguages($languages)->withTerms($terms)->withNext_term($next_term)->withPrev_term($prev_term)->withRepos($repos)->withRepos_lang($repos_lang)->withUser($user)->withOrg($org);
        } else {
            return redirect('home')->with('interdire-msg', 'You cannot go directly to that link. First visit: < '. route('whatorg') .' > and answer the mandatory question.');
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
        $mgr_email = $request->input('mgr_email');
        $mgr_fname = $request->input('mgr_fname');
        $mgr_lname = $request->input('mgr_lname');
        $uniquecode = $request->input('CodeIndexID');
        $decision = $request->input('decision');
        $org = $request->input('org');
        $agreementBtn = $request->input('agreementBtn');
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
                        var_dump($request->CodeIndexID);
                        // validate using custom validator based on unique validation helper
                        // with where clauses to specify customized validation 
                        // the validation below fails when CodeIndexID is already taken AND 
                        // deleted_at column is NULL which means it has not been cancelled AND
                        // not disapproved by manager or HR learning partner
                        $this->validate($request, array(
                            'CodeIndexID' => Rule::unique('tblLTP_Enrolment')->where(function ($query) use($request) {
                                    $uniqueCodex = $request->CodeIndexID;
                                    $query->where('CodeIndexID', $uniqueCodex)
                                        ->where('deleted_at', NULL);
                                })
                            // 'CodeIndexID' => 'unique:tblLTP_Enrolment,CodeIndexID|',
                        ));
            }                              
        }
                    //validate other input fields outside of above loop
                        $this->validate($request, array(
                            'term_id' => 'required|',
                            'schedule_id' => 'required|',
                            'course_id' => 'required|',
                            'L' => 'required|',
                            'mgr_email' => 'required|email',
                            'org' => 'required',
                            'agreementBtn' => 'required|',
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

        // control the number of submitted courses per enrolment form submission
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

        // check if placement test form
        // if so, call method from PlacementFormController
        if ($request->placementDecisionB === '0') {
            app('App\Http\Controllers\PlacementFormController')->postPlacementInfo($request, $form_counter, $eform_submit_count);
            $request->session()->flash('success', 'Your Placement Test request has been submitted.'); //laravel 5.4 version
            return redirect()->route('home');
        }
        
        //loop for storing Code value to database
        $ingredients = [];        
        for ($i = 0; $i < count($schedule_id); $i++) {
            $ingredients[] = new  Preenrolment([
                'CodeIndexID' => $course_id.'-'.$schedule_id[$i].'-'.$term_id.'-'.$index_id,
                'Code' => $course_id.'-'.$schedule_id[$i].'-'.$term_id,
                'schedule_id' => $schedule_id[$i],
                'L' => $language_id,
                'Te_Code' => $course_id,
                'Term' => $term_id,
                'INDEXID' => $index_id,
                "created_at" =>  \Carbon\Carbon::now(),
                "updated_at" =>  \Carbon\Carbon::now(),
                'mgr_email' =>  $mgr_email,
                'mgr_lname' => $mgr_lname,
                'mgr_fname' => $mgr_fname,
                'continue_bool' => 1,
                'DEPT' => $org, 
                'eform_submit_count' => $eform_submit_count,              
                'form_counter' => $form_counter,  
                'agreementBtn' => $agreementBtn,
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

        Mail::to($mgr_email)->send(new MailtoApprover($input_course, $input_schedules, $staff));
        
        $sddextr_query = SDDEXTR::where('INDEXNO', $current_user)->firstOrFail();
        $sddextr_org = $sddextr_query->DEPT;
        if ($org == $sddextr_org){
            // flash session success or errorBags 
            $request->session()->flash('success', 'Enrolment Form has been submitted for approval'); //laravel 5.4 version

            return redirect()->route('home');
        }else{
            
            return $this->update($request, $org, $current_user);
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

        $request->session()->flash('success', 'Enrolment Form has been submitted for approval'); //laravel 5.4 version
        $request->session()->flash('org_change_success', 'Organization has been updated'); 
        return redirect()->route('home');
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

    /**
     * Show the application selectAjax.
     *
     * @return \Illuminate\Http\response
     */
    public function selectAjax(Request $request)
    {
        if($request->ajax()){
            //$courses = DB::table('courses')->where('language_id',$request->language_id)->pluck("name","id")->all();
            $select_courses = Course::where('L', $request->L)
            ->whereNotNull('Te_Code_New')
            ->orderBy('id', 'asc')
            ->pluck("Description","Te_Code_New")
            ->all();
            
            $data = view('ajax-select',compact('select_courses'))->render();
            return response()->json(['options'=>$data]);
        }
    }

    public function selectAjax2(Request $request)
    {
        if($request->ajax()){

            //$select_schedules = DB::table('LTP_TEVENTCur')
            $select_schedules = Classroom::where('Te_Code_New', $request->course_id)
            ->where(function($q){
                //get current year and date
                $now_date = Carbon::now()->toDateString();
                $now_year = Carbon::now()->year;

                //query the current term based on Term_End column is greater than today's date  
                $latest_term = Term::orderBy('Term_Code', 'desc')
                                ->whereDate('Term_End', '>=', $now_date)
                                ->get()->min();            
                //$latest_term = DB::table('LTP_Terms')->orderBy('Term_Code', 'DESC')->value('Term_Code');
                $q->where('Te_Term', $latest_term->Term_Code );
            })

            //Eager Load scheduler function and pluck using "dot" 
            ->with('scheduler')->get()->pluck('scheduler.name', 'schedule_id');

            $data = view('ajax-select2',compact('select_schedules'))->render();
            return response()->json(['options'=>$data]);
        }
    }
}
