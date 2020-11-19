<?php

namespace App\Http\Controllers;

use App\Course;
use App\FocalPoints;
use App\Language;
use App\Mail\MailaboutCancel;
use App\Mail\MailaboutPlacementCancel;
use App\Mail\cancelConvocation;
use App\PlacementForm;
use App\Preenrolment;
use App\Preview;
use App\Repo;
use App\SDDEXTR;
use App\Term;
use App\Torgan;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Session;

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
        $currentEnrolmentTerm = \App\Helpers\GlobalFunction::instance()->currentEnrolTermObject();
        $cancel_date_limit_string = '';
        $cancel_date_limit_string_fr = '';

        if (!is_null($currentEnrolmentTerm)) {
            $currentEnrolmentTermCode = $currentEnrolmentTerm->Term_Code;

            // get cancel date limit
            $queryCancelDateLimit = Term::where('Term_Code', $currentEnrolmentTermCode)->first()->Cancel_Date_Limit;
            $cancel_date_limit = new Carbon($queryCancelDateLimit);
            $cancel_date_limit->subDay();

            $termCancelMonth = date('F', strtotime($cancel_date_limit));
            $termCancelDate = date('d', strtotime($cancel_date_limit));
            $termCancelYear = date('Y', strtotime($cancel_date_limit));

            // cancel limit date convert to string
            $cancel_date_limit_string = date('d F Y', strtotime($cancel_date_limit));

            // translate 
            $termCancelMonthFr = __('months.' . $termCancelMonth, [], 'fr');
            $cancel_date_limit_string_fr = $termCancelDate . ' ' . $termCancelMonthFr . ' ' . $termCancelYear;
        }

        return view('home', compact('cancel_date_limit_string', 'cancel_date_limit_string_fr'));
    }

    public function homeHowToCheckStatus()
    {
        return view('home_how_to_check_status');
    }

    /*
    *    Shows submitted forms @ route{{"/submitted"}} 
    *    and route{{"/previous-submitted"}} 
    */
    public function previousSubmitted(Request $request)
    {
        $current_user = Auth::user()->indexno;
        $term_select = Term::orderBy('Term_Code', 'desc')->get();

        // query the current term based on year and Term_End column is greater than today's date
        // whereYear('Term_End', $now_year)->first();
        // $now_date = Carbon::now()->toDateString();
        // $terms = Term::orderBy('Term_Code', 'desc')
        //         ->whereDate('Term_End', '>=', $now_date)
        //         ->get()->min();
        // $next_term_code = Term::orderBy('Term_Code', 'desc')->where('Term_Code', '=', $terms->Term_Next)->get()->min('Term_Code');

        // get the term from the select dropdown 
        $termValue = $request->termValue;

        if (is_null($termValue)) {
            // return redirect('home');
            $termValue = '001';
        }
        //query submitted forms based from tblLTP_Enrolment table
        $forms_submitted = Preenrolment::withTrashed()
            ->distinct('Te_Code')
            ->where('INDEXID', '=', $current_user)
            ->where('Term', $termValue)
            ->get(['Te_Code', 'Term', 'INDEXID', 'DEPT', 'is_self_pay_form', 'continue_bool', 'form_counter', 'deleted_at', 'eform_submit_count', 'cancelled_by_student']);
        // ->get(['Te_Code', 'schedule_id' , 'INDEXID' ,'approval','approval_hr', 'DEPT', 'is_self_pay_form', 'continue_bool', 'form_counter','deleted_at', 'eform_submit_count']);
        $plforms_submitted = PlacementForm::withTrashed()
            ->where('INDEXID', '=', $current_user)
            ->where('Term', $termValue)
            ->get();

        //$str = $forms_submitted->pluck('Te_Code');
        //$str_codes = str_replace(['\/','"','[',"]","'" ], '', $str);
        //$array_codes = explode(',', $str_codes);
        //var_dump($str);
        //var_dump($str_codes);
        //svar_dump($array_codes); 
        $next_term = Term::orderBy('Term_Code', 'desc')->where('Term_Code', '=', $termValue)->get()->min();

        $student_convoked = Repo::withTrashed()->whereNotNull('CodeIndexIDClass')->where('INDEXID', $current_user)->where('Term', $termValue)->get();


        return view('form.submitted', compact('forms_submitted', 'plforms_submitted', 'next_term', 'term_select', 'student_convoked'));
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
            ->where('Term', $term_code)->get(['schedule_id', 'mgr_email', 'approval', 'approval_hr', 'is_self_pay_form', 'DEPT', 'selfpay_approval']);
        // ->pluck('schedule.name', 'approval');

        // render and return data values via AJAX
        $data = view('form.modalshowinfo', compact('schedules'))->render();
        return response()->json([$data]);
    }

    public function history()
    {
        $currentTerm = \App\Helpers\GlobalFunction::instance()->currentTermObject();
        $currentTermCode = $currentTerm->Term_Code;
        $current_user = Auth::user()->indexno;
        $historical_data = Repo::orderBy('Term', 'desc')->where('INDEXID', $current_user)->get();

        if ($historical_data->isEmpty()) {
            $historical_data = null;
            return view('form.history', compact('historical_data', 'currentTermCode'));
        }
        // dd($historical_data);
        return view('form.history', compact('historical_data', 'currentTermCode'));
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

        $org = Torgan::orderBy('Org name', 'asc')->get(['Org name', 'Org Full Name']);
        // ->pluck('Org name','Org name', 'Org Full Name');

        $late = 0;

        return view('form.whatorg', compact('terms', 'next_term', 'org', 'late'));
    }

    public function whatform(Request $request)
    {
        dd($request->all());
        // if part of new organization, then save the new organization to sddextr     
        // save CAT to Auth User table   
        $id = Auth::id();
        $student = User::findOrFail($id);
        $student->profile = $request->profile;
        $student->save();
        // save organization to sddextr table
        $student->sddextr->CAT = $request->profile;
        $student->sddextr->DEPT = $request->input('organization');
        $student->sddextr->save();

        // query Torgan table if $request->organization is selfpaying or not
        $org_status = Torgan::where('Org name', '=', $request->organization)
            ->value('is_self_paying'); // change to appropriate field name 'is_self_pay' or 'is_billed'

        if ($request->decision == 1) {
            session()->flash('success', 'Please fill in the payment-based enrolment form');
            return redirect(route('selfpayform.create'));
        } elseif ($request->decision == 0 && $org_status == 1) {
            session()->flash('success', 'Please fill in the payment-based enrolment form');
            return redirect(route('selfpayform.create'));
        } elseif ($request->decision == 0 && $org_status == 0) {
            session()->flash('success', 'Please fill in the enrolment form');
            return redirect(route('myform.create'));
        }
        // elseif ($request->decision == 0) {
        //     session()->flash('success','Please fill in the enrolment form');
        //     return redirect(route('myform.create'));
        // } 
        else
            return redirect(route('whatorg'));
    }

    public function destroy(Request $request, $staff, $tecode,  $term, $form)
    {
        $current_user = $staff;

        //query submitted forms based from tblLTP_Enrolment table
        $forms = Preenrolment::orderBy('Term', 'desc')
            ->where('Te_Code', $tecode)
            ->where('INDEXID', '=', $current_user)
            ->where('Term', $term)
            ->where('form_counter', $form)
            ->get();
        $display_language = Preenrolment::orderBy('Term', 'desc')
            ->where('Te_Code', $tecode)
            ->where('INDEXID', '=', $current_user)
            ->where('Term', $term)
            ->where('form_counter', $form)
            ->first();

        //get email address of the Manager
        $mgr_email = $forms->pluck('mgr_email')->first();

        // get is_self_pay_form value
        $is_self_pay_form = $forms->pluck('is_self_pay_form')->first();

        //if self-paying enrolment form do this
        if ($is_self_pay_form == 1) {
            $type = 0; // 0 = regular enrolment form
            $this->sendMailToStudent($display_language, $term, $type, $forms);

            $enrol_form = [];
            for ($i = 0; $i < count($forms); $i++) {
                $enrol_form = $forms[$i]->id;
                $delform = Preenrolment::find($enrol_form);
                $delform->cancelled_by_student = 1;
                $delform->save();
                $delform->delete();
            }
            session()->flash('cancel_success', 'Enrolment Form for ' . $display_language->courses->EDescription . ' has been cancelled.');
            return redirect()->back();
        }

        $staff_member_name = $forms->first()->users->name;
        //email notification to Manager    
        //     Mail::to($mgr_email)->send(new MailaboutCancel($forms, $display_language, $staff_member_name));

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
        // check if org has learning partner
        $check_learning_partner = Torgan::where('Org name', $org)->first();
        $learning_partner = $check_learning_partner->has_learning_partner;
        // Add more organizations in the IF statement below
        if ($org !== 'UNOG' && $learning_partner == '1') {

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
                ->send(new MailaboutCancel($forms, $display_language, $staff_member_name, $term_season_en, $term_year));
        }

        $enrol_form = [];
        for ($i = 0; $i < count($forms); $i++) {
            $enrol_form = $forms[$i]->id;
            $delform = Preenrolment::find($enrol_form);
            $delform->cancelled_by_student = 1;
            $delform->save();
            $delform->delete();
        }

        session()->flash('cancel_success', 'Enrolment Form for ' . $display_language->courses->EDescription . ' has been cancelled.');
        return redirect()->back();
    }

    public function sendMailToStudent($display_language, $term, $type, $forms)
    {
        if ($type === 1) {
            $display_language_en = $display_language->languages->name . ' Placement Test';
            $display_language_fr = 'Test de placement - ' . $display_language->languages->name_fr;
            $schedule = 'n/a';
        } else {
            $display_language_en = $display_language->courses->EDescription;
            $display_language_fr = $display_language->courses->FDescription;
            $arraySchedule = [];
            foreach ($forms as $valueForms) {
                $arraySchedule[] = $valueForms->schedule->name;
            }
            $schedule = implode(' / ', $arraySchedule);
        }

        $staff_name = $display_language->users->name;
        $std_email = $display_language->users->email;

        $term_season_en = Term::where('Term_Code', $term)->first()->Comments;
        $term_season_fr = Term::where('Term_Code', $term)->first()->Comments_fr;

        $term_date_time = Term::where('Term_Code', $term)->first()->Term_Begin;
        $term_year = new Carbon($term_date_time);
        $term_year = $term_year->year;
        $seasonYear = $term_season_en . ' ' . $term_year;

        $subject = 'Cancellation: ' . $staff_name . ' - ' . $display_language_en . ' (' . $seasonYear . ')';

        Mail::to($std_email)->send(new cancelConvocation($staff_name, $display_language_fr, $display_language_en, $schedule, $subject, $type));
    }

    public function destroyPlacement(Request $request, $staff, $lang, $term, $eform)
    {
        //query submitted forms based from tblLTP_Enrolment table
        $forms = PlacementForm::orderBy('Term', 'desc')
            ->where('L', $lang)
            ->where('INDEXID', '=', $staff)
            ->where('Term', $term)
            ->where('eform_submit_count', $eform)
            ->get();
        $display_language = PlacementForm::orderBy('Term', 'desc')
            ->where('L', $lang)
            ->where('INDEXID', '=', $staff)
            ->where('Term', $term)
            ->where('eform_submit_count', $eform)
            ->first();

        //get email address of the Manager
        $mgr_email = $forms->pluck('mgr_email')->first();

        // get is_self_pay_form value
        $is_self_pay_form = $forms->pluck('is_self_pay_form')->first();

        //if self-paying enrolment form
        if ($is_self_pay_form == 1) {
            $type = 1; // 1 = placement form
            $this->sendMailToStudent($display_language, $term, $type, $forms);

            $enrol_form = [];
            for ($i = 0; $i < count($forms); $i++) {
                $enrol_form = $forms[$i]->id;
                $delform = PlacementForm::find($enrol_form);
                $delform->cancelled_by_student = 1;
                $delform->save();
                $delform->delete();
            }

            session()->flash('cancel_success', 'Placement Test Request for ' . $display_language->languages->name . ' has been cancelled.');
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
        if ($learning_partner == '1') {

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
            $delform->cancelled_by_student = 1;
            $delform->save();
            $delform->delete();
        }

        session()->flash('cancel_success', 'Placement Form Request for ' . $display_language->languages->name . ' has been cancelled.');
        return redirect()->back();
    }
}
