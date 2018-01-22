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
use App\File;
use Session;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

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
        //$this->middleware('opencloseenrolment');
        $this->middleware('checksubmissioncount');
        //$this->middleware('checkcontinue');
        $this->middleware('check-prev-url', ['only' => ['create']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {  
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
        $repos_lang = Repo::orderBy('Term', 'desc')
            ->where('INDEXID', $current_user)->first();
        $org = Torgan::get()->pluck('Org name','Org name');

        return view('form.myform3')->withCourses($courses)->withLanguages($languages)->withTerms($terms)->withNext_term($next_term)->withPrev_term($prev_term)->withRepos($repos)->withRepos_lang($repos_lang)->withUser($user)->withOrg($org);
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
                        $this->validate($request, array(
                            'CodeIndexID' => 'unique:tblLTP_Enrolment,CodeIndexID|',
                        ));
            }                              
        }
                    //validate other input fields outside of above loop
                        $this->validate($request, array(
                            'term_id' => 'required|',
                            'schedule_id' => 'required|',
                            'course_id' => 'required|',
                            'L' => 'required|',
                            'identityfile' => 'required|mimes:pdf,doc,docx|max:20000',
                            'payfile' => 'required|mimes:pdf,doc,docx|max:20000',
                        )); 
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
                'continue_bool' => $decision,
                'attachment_id' => $attachment_identity_file->id,
                'attachment_pay' => $attachment_pay_file->id,
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
                                ->get();
        
        //Mail::to($mgr_email)->send(new MailtoApprover($input_course, $input_schedules, $staff));
        
        $sddextr_query = SDDEXTR::where('INDEXNO', $current_user)->firstOrFail();

        $request->session()->flash('success', 'Enrolment Form has been submitted for approval'); 

        return redirect()->route('home');   
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
    public function selectAjax5(Request $request)
    {
        if($request->ajax()){
            //$courses = DB::table('courses')->where('language_id',$request->language_id)->pluck("name","id")->all();
            $select_courses = DB::table('LTP_CR_LIST')
            ->where('L', $request->L)
            ->orderBy('id', 'asc')
            ->pluck("Description","Te_Code_New")
            ->all();
            
            $data = view('ajax-select5',compact('select_courses'))->render();
            return response()->json(['options'=>$data]);
        }
    }

    public function selectAjax6(Request $request)
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

            $data = view('ajax-select6',compact('select_schedules'))->render();
            return response()->json(['options'=>$data]);
        }
    }
}
