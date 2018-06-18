<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Mail\MailaboutCancel;
use App\Mail\MailaboutPlacementCancel;
use App\Torgan;
use App\FocalPoints;
use App\Language;
use App\Course;
use App\User;
use App\Repo;
use App\Preenrolment;
use App\Term;
use Session;
use Carbon\Carbon;
use DB;
use App\SDDEXTR;
use App\PlacementForm;

class HomeController extends Controller
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
        // $this->middleware('check-placement-exam', ['except' => ['getPlacementInfo', 'postPlacementInfo']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $current_user = Auth::user()->indexno;
        //query last UN Language Course enrolled in the past based on PASHQ table
        $repos_lang = Repo::orderBy('Term', 'desc')->where('INDEXID', $current_user)->first();
        //query the current term based on year and Term_End column is greater than today's date
        //whereYear('Term_End', $now_year)->first();
        $now_date = Carbon::now()->toDateString();

        $terms = Term::orderBy('Term_Code', 'desc')
                ->whereDate('Term_End', '>=', $now_date)
                ->get()->min();

        $next_term_code = Term::orderBy('Term_Code', 'desc')->where('Term_Code', '=', $terms->Term_Next)->get()->min('Term_Code');

        //query submitted forms based from tblLTP_Enrolment table
        $forms_submitted = Preenrolment::distinct('Te_Code')
            ->where('INDEXID', '=', $current_user)
            ->where('Term', $next_term_code )->get();
        $next_term = Term::orderBy('Term_Code', 'desc')->where('Term_Code', '=', $terms->Term_Next)->get()->min();
        
        $current_enrol_term = \App\Helpers\GlobalFunction::instance()->currentEnrolTermObject();

        return view('home')->withRepos_lang($repos_lang)->withForms_submitted($forms_submitted)->withNext_term($next_term)->withCurrent_enrol_term($current_enrol_term)->withTerms($terms);
    }
    
    /*
    *    Shows submitted forms @ route{{"/submitted"}}
    */
    public function currentSubmitted()
    {
        $current_user = Auth::user()->indexno;

        $now_date = Carbon::now()->toDateString();
        // get the current enrolment term collection
        $current_enrol_term = \App\Helpers\GlobalFunction::instance()->currentEnrolTermObject();
        
        //query submitted forms on current enrolment term based from tblLTP_Enrolment table
        if (!is_null($current_enrol_term)) {
            $forms_submitted = Preenrolment::withTrashed()
                ->distinct('Te_Code')
                ->where('INDEXID', '=', $current_user)
                ->where('Term', $current_enrol_term->Term_Code )
                ->get(['Te_Code', 'Term', 'INDEXID' , 'DEPT', 'is_self_pay_form', 'continue_bool', 'form_counter','deleted_at', 'eform_submit_count', 'cancelled_by_student' ]);
            $plforms_submitted = PlacementForm::withTrashed()
                ->where('INDEXID', '=', $current_user)
                ->where('Term', $current_enrol_term->Term_Code )
                ->get();

            // preserve variable names
            $next_term = $current_enrol_term; 
     
            return view('form.submitted')->withForms_submitted($forms_submitted)->withPlforms_submitted($plforms_submitted)->withNext_term($next_term);
        } 
        session()->flash('interdire-msg','under construction');
        return redirect('home');
    }    
    /*
    *    Shows submitted forms @ route{{"/submitted"}}
    */
    public function previousSubmitted(Request $request)
    {
        $current_user = Auth::user()->indexno;
        $term_select = Term::orderBy('Term_Code', 'desc')->get();

        //query the current term based on year and Term_End column is greater than today's date
        //whereYear('Term_End', $now_year)->first();
        $now_date = Carbon::now()->toDateString();
        // $terms = Term::orderBy('Term_Code', 'desc')
        //         ->whereDate('Term_End', '>=', $now_date)
        //         ->get()->min();
        // $next_term_code = Term::orderBy('Term_Code', 'desc')->where('Term_Code', '=', $terms->Term_Next)->get()->min('Term_Code');
        
        $current_enrol_term = \App\Helpers\GlobalFunction::instance()->currentEnrolTermObject();
        // get the term prior to current enrolment term 
        $prev_enrol_term = $request->termValue;

        if (is_null($prev_enrol_term)) {
            // return redirect('home');
            $prev_enrol_term = '001';
        }
        //query submitted forms based from tblLTP_Enrolment table
        $forms_submitted = Preenrolment::withTrashed()
            ->distinct('Te_Code')
            ->where('INDEXID', '=', $current_user)
            ->where('Term', $prev_enrol_term )
            ->get(['Te_Code', 'Term', 'INDEXID' , 'DEPT', 'is_self_pay_form', 'continue_bool', 'form_counter','deleted_at', 'eform_submit_count', 'cancelled_by_student' ]);
            // ->get(['Te_Code', 'schedule_id' , 'INDEXID' ,'approval','approval_hr', 'DEPT', 'is_self_pay_form', 'continue_bool', 'form_counter','deleted_at', 'eform_submit_count']);
        $plforms_submitted = PlacementForm::withTrashed()
            ->where('INDEXID', '=', $current_user)
            ->where('Term', $prev_enrol_term )
            ->get();

        //$str = $forms_submitted->pluck('Te_Code');
        //$str_codes = str_replace(['\/','"','[',"]","'" ], '', $str);
        //$array_codes = explode(',', $str_codes);
        //var_dump($str);
        //var_dump($str_codes);
        //svar_dump($array_codes); 
        $next_term = Term::orderBy('Term_Code', 'desc')->where('Term_Code', '=', $prev_enrol_term)->get()->min();
        
        return view('form.submitted')->withForms_submitted($forms_submitted)->withPlforms_submitted($plforms_submitted)->withNext_term($next_term)->withTerm_select($term_select);
    }

    public function showMod(Request $request)
    {
        $current_user = Auth::user()->indexno;
        $term_code = $request->term;
        // query submitted forms based from tblLTP_Enrolment table
        $schedules = Preenrolment::withTrashed()
            ->where('Te_Code', $request->tecode)
            ->where('INDEXID', $current_user)
            // ->where('approval', '=', $request->approval)
            ->where('form_counter', $request->form_counter)
            ->where('Term', $term_code )->get(['schedule_id', 'approval', 'approval_hr', 'is_self_pay_form', 'DEPT']);
            // ->pluck('schedule.name', 'approval');

        // render and return data values via AJAX
            $data = view('form.modalshowinfo',compact('schedules'))->render();
        return response()->json([$data]);
    }

    public function history()
    {
        $current_user = Auth::user()->indexno;
        $historical_data = Repo::orderBy('Term', 'desc')->where('INDEXID', $current_user)->get();
        // dd(Auth::user()->name);
        return view('form.history')->withHistorical_data($historical_data);
    }

    public function whatorg()
    {
        //get current year and date
        $now_date = Carbon::now()->toDateString();
        $now_year = Carbon::now()->year;
        // actual current term
        $terms = Term::orderBy('Term_Code', 'desc')->whereDate('Term_End', '>=', $now_date)->get()->min();
        // actual enrolment term
        $next_term = \App\Helpers\GlobalFunction::instance()->currentEnrolTermObject();

        $org = Torgan::orderBy('Org Name', 'asc')->get(['Org Name','Org Full Name']);
        // ->pluck('Org name','Org name', 'Org Full Name');

        return view('form.whatorg')->withTerms($terms)->withNext_term($next_term)->withOrg($org);
    }
    
    public function whatform(Request $request)
    {
        // if part of new organization, then save the new organization to sddextr        
        // save organization and CAT to sddextr table
        $id = Auth::id();
        $student = User::findOrFail($id);
        $student->sddextr->DEPT = $request->input('organization');
        // $student->sddextr->CAT = $request->input('profile');
        $student->sddextr->save();

        // query Torgan table if $request->organization is selfpaying or not
        $org_status = Torgan::where('Org name', '=', $request->organization)
            ->value('is_self_paying'); // change to appropriate field name 'is_self_pay' or 'is_billed'

        if ($request->decision == 1) {
            session()->flash('success','Please fill up the payment-based enrolment form');
            return redirect(route('selfpayform.create'));
        } 
        // elseif ($request->decision == 0 && $org_status == 1) {
        //     session()->flash('success','Please fill up the payment-based enrolment form');
        //     return redirect(route('selfpayform.create'));
        // } 
        // elseif ($request->decision == 0 && $org_status == 0) {
        //     session()->flash('success','Please fill up the enrolment form');
        //     return redirect(route('myform.create'));
        // } 
        elseif ($request->decision == 0) {
            session()->flash('success','Please fill up the enrolment form');
            return redirect(route('myform.create'));
        } else 
        return redirect(route('whatorg'));

    }

    public function destroy(Request $request, $staff, $tecode,  $term, $form)
    {
        $current_user = $staff;

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
                $delform->save();
                $delform->delete();
            }
            session()->flash('cancel_success', 'Enrolment Form for '.$display_language->courses->EDescription. ' has been cancelled.');
            return redirect()->back();
        }

        //email notification to Manager    
        $staff_member_name = Auth::user()->name;
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
            $delform->save();
            $delform->delete();
        }

        session()->flash('cancel_success', 'Enrolment Form for '.$display_language->courses->EDescription. ' has been cancelled.');
        return redirect()->back();
    }

    public function destroyPlacement(Request $request, $staff, $lang, $term, $eform)
    {
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

        //if self-paying enrolment form
        if (is_null($mgr_email)){
            $enrol_form = [];
            for ($i = 0; $i < count($forms); $i++) {
                $enrol_form = $forms[$i]->id;
                $delform = PlacementForm::find($enrol_form);
                $delform->cancelled_by_student = 1;
                $delform->save();
                $delform->delete();
            }
            session()->flash('cancel_success', 'Placement Test Request for '.$display_language->languages->name. ' has been cancelled.');
            return redirect()->back();
        }

        //email notification to Manager    
        $staff_member_name = Auth::user()->name;
            Mail::to($mgr_email)->send(new MailaboutPlacementCancel($forms, $display_language, $staff_member_name));
        
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
                    ->send(new MailaboutPlacementCancel($forms, $display_language, $staff_member_name));

        }

        $enrol_form = [];
        for ($i = 0; $i < count($forms); $i++) {
            $enrol_form = $forms[$i]->id;
            $delform = PlacementForm::find($enrol_form);
            $delform->cancelled_by_student = 1;
            $delform->save();
            $delform->delete();
        }

        session()->flash('cancel_success', 'Pleacement Form Request for '.$display_language->languages->name. ' has been cancelled.');
        return redirect()->back();
    }
}
