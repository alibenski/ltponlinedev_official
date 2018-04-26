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

use App\PlacementSchedule;
use App\PlacementForm;

class PlacementFormController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
        $this->middleware('prevent-back-history');
        // $this->middleware('opencloseenrolment');
        // $this->middleware('checksubmissioncount');
        // $this->middleware('checkcontinue');
    }

    public function getPlacementInfo()
    {
        $languages = DB::table('languages')->pluck("name","code")->all();
        $now_date = Carbon::now()->toDateString();
            $terms = Term::orderBy('Term_Code', 'desc')
                    ->whereDate('Term_End', '>=', $now_date)
                    ->get()->min();

        $next_term = Term::orderBy('Term_Code', 'desc')->where('Term_Code', '=', $terms->Term_Next)->get()->min();
        return view('form.myformplacement')->withLanguages($languages)->withNext_term($next_term);
    }

    public function postPlacementInfo(Request $request, $form_counter, $eform_submit_count)
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
            'CodeIndexID' => Rule::unique('tblLTP_Placement_Forms')->where(function ($query) use($request) {
                                    $uniqueCodex = $request->CodeIndexID;
                                    $query->where('CodeIndexID', $uniqueCodex)
                                        ->where('deleted_at', NULL);
                                }),
            'placementLang' => 'required|integer',
            'agreementBtn' => 'required|',
        ));
        
        //loop for storing Code value to database
        $ingredients = [];        
        for ($i = 0; $i < count($schedule_id); $i++) {
            $ingredients[] = new  PlacementForm([
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
                'continue_bool' => 0,
                'DEPT' => $org,    
                'eform_submit_count' => $eform_submit_count, 
                'form_counter' => $form_counter,  
                'agreementBtn' => $agreementBtn,
                'placement_schedule_id' => $request->placementLang,                
                ]); 
                    foreach ($ingredients as $data) {
                        $data->save();
                    }
        } 
        
        // mail student regarding placement form information

        $request->session()->flash('success', 'Your Placement Test request has been submitted.'); //laravel 5.4 version
        return redirect()->route('home');
    }
}