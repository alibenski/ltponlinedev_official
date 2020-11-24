<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use DB;
use Carbon\Carbon;
use App\Course;
use App\Day;
use App\Repo;
use App\Term;
use App\Torgan;
use App\User;

class LateEnrolmentController extends Controller
{
    protected function generateRandomURL(Request $request)
    {
        if ($request->ajax()) {

            $recordId = DB::table('url_generator')->insertGetId(
                ['user_id' => Auth::id(), 'email' => $request->email]
            );

            $url = URL::temporarySignedRoute('late-what-org', now()->addDays(1), ['transaction' => $recordId]);

            return response()->json($url);
        }
    }

    public function lateWhatOrg(Request $request)
    {
        // if (!$request->hasValidSignature()) {
        //     abort(401);
        // }

        $now_date = Carbon::now()->toDateString();
        $now_year = Carbon::now()->year;
        $terms = Term::orderBy('Term_Code', 'desc')->whereDate('Term_End', '>=', $now_date)->get()->min();
        $next_term = \App\Helpers\GlobalFunction::instance()->currentEnrolTermObject();
        $org = Torgan::orderBy('Org name', 'asc')->get(['Org name', 'Org Full Name']);

        return view('form.late.late-what-org', compact('terms', 'next_term', 'org'));
    }

    public function lateWhatForm(Request $request)
    {
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
            return redirect(route('late-registration'));
        }
        else
            return redirect()->back();
    }

    public function lateRegistration(Request $request)
    {
        $sess = $request->session()->get('_previous');
        $result = array();
        foreach ($sess as $val) {
            $result = $val;
        }

        if ($result == route('late-what-org') || $result == route('late-registration') ) {
            $courses = Course::all();
            $languages = DB::table('languages')->pluck("name", "code")->all();
            $days = Day::pluck("Week_Day_Name", "Week_Day_Name")->except('Sunday', 'Saturday')->all();
            $now_date = Carbon::now()->toDateString();
            $now_year = Carbon::now()->year;

            //query the current term based on year and Term_End column is greater than today's date
            //whereYear('Term_End', $now_year)  
            // $terms = \App\Helpers\GlobalFunction::instance()->currentEnrolTermObject();
            $terms = Term::orderBy('Term_Code', 'desc')
                            ->whereDate('Term_Begin', '>=', $now_date)
                            ->get()->min();

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
                return view('form.late.late-registration', compact('courses', 'languages', 'terms', 'next_term', 'prev_term', 'repos', 'repos_lang', 'user', 'org', 'days'));
            }

            $repos_lang = Repo::orderBy('Term', 'desc')->where('Term', $student_last_term->Term)
                ->where('INDEXID', $current_user)->get();
            $org = Torgan::orderBy('Org name', 'asc')->get()->pluck('Org name', 'Org name');

            return view('form.late.late-registration', compact('courses', 'languages', 'terms', 'next_term', 'prev_term', 'repos', 'repos_lang', 'user', 'org', 'days'));
        } else {
            abort(401);
        }
        
    }

    public function storeLateRegistration(Request $request)
    {
        dd($request->all());
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
            'agreementBtn' => 'required|',
        ));

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
                'eform_submit_count' => $eform_submit_count,
                'form_counter' => $form_counter,
                'agreementBtn' => $agreementBtn,
                'flexibleBtn' => $flexibleBtn,
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

}
