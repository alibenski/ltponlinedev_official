<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use DB;
use Carbon\Carbon;
use App\Course;
use App\Day;
use App\File;
use App\Mail\EmailLateEnrol;
use App\PlacementForm;
use App\Preenrolment;
use App\Repo;
use App\SDDEXTR;
use App\Term;
use App\Torgan;
use App\User;
use App\Services\User\MsuUpdateField;
use App\Services\User\NgoUpdateField;

class LateEnrolmentController extends Controller
{
    protected function generateRandomURL(Request $request)
    {
        if ($request->ajax()) {
            $recordId = DB::table('url_generator')->insertGetId(
                ['user_id' => Auth::id(), 'email' => $request->email, 'description' => 'late registration link']
            );

            $url = URL::temporarySignedRoute('late-what-org', now()->addDays(1), ['transaction' => $recordId]);

            Mail::to($request->email)->send(new EmailLateEnrol($url));

            return response()->json($url);
        }
    }

    public function lateWhatOrg(Request $request)
    {
        $qryEmail = DB::table('url_generator')->where('id', $request->transaction)->first()->email;
        if (!$request->hasValidSignature() || Auth::user()->email != $qryEmail) {
            abort(401);
        }

        $url = $request->fullUrl();
        $now_date = Carbon::now()->toDateString();
        $now_year = Carbon::now()->year;
        $next_term = Term::orderBy('Term_Code', 'desc')->whereDate('Term_Begin', '>=', $now_date)->get()->min();
        $org = Torgan::orderBy('Org name', 'asc')->get(['Org name', 'Org Full Name']);

        return view('form.late.late-what-org', compact('url', 'next_term', 'org'));
    }

    public function lateWhatForm(Request $request, MsuUpdateField $msuUpdateField, NgoUpdateField $ngoUpdateField)
    {
        if ($request->organization === 'MSU') {
            $this->validate($request, array(
                'countryMission' => 'required',
            ));
        }

        if ($request->organization === 'NGO') {
            $this->validate($request, array(
                'ngoName' => 'required',
            ));
        }
        // if part of new organization, then save the new organization to sddextr     
        // save CAT to Auth User table   
        $id = Auth::id();
        $student = User::findOrFail($id);
        $student->profile = $request->profile;
        $student->save();
        // save organization to sddextr table
        $student->sddextr->CAT = $request->profile;
        $student->sddextr->DEPT = $request->input('organization');

        $msuUpdateField->checkMsuValue($student, $request);
        $ngoUpdateField->checkNgoValue($student, $request);

        $student->sddextr->save();

        // query Torgan table if $request->organization is selfpaying or not
        $org_status = Torgan::where('Org name', '=', $request->organization)
            ->value('is_self_paying'); // change to appropriate field name 'is_self_pay' or 'is_billed'

        if ($request->decision == 1) {
            session()->flash('checkSelfPay', 1);
            session()->put('url', $request->url);
            return redirect(route('late-selfpay-form'));
        } elseif ($request->decision == 0 && $org_status == 1) {
            session()->flash('checkSelfPay', 1);
            session()->put('url', $request->url);
            return redirect(route('late-selfpay-form'));
        } elseif ($request->decision == 0 && $org_status == 0) {
            session()->flash('check', 1);
            session()->put('url', $request->url);
            return redirect()->route('late-registration');
        } else
            return redirect()->back();
    }

    public function lateRegistration(Request $request)
    {
        $url = session()->get('url');
        $check = $request->session()->get('check');
        if ($check == 1) {
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
            if ($url) {
                $errors = $request->session()->get('errors');
                if ($errors) {
                    return redirect()->to($url)->withErrors($errors->all());
                }
                return redirect()->to($url);
            }

            return redirect('home')->with('interdire-msg', 'Access denied. Please refer to the link that the CLM Secretariat sent to your email.');
        }
    }

    public function storeLateRegistration(Request $request)
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
            $this->postPlacementInfo($request);

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
            'regular_enrol_comment' => 'required',
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
                'approval' => $request->approval,
                'continue_bool' => 1,
                'DEPT' => $org,
                'country_mission' => $request->input('countryMission'),
                'ngo_name' => $request->input('ngoName'),
                'eform_submit_count' => $eform_submit_count,
                'form_counter' => $form_counter,
                'agreementBtn' => $agreementBtn,
                'flexibleBtn' => $flexibleBtn,
                'flexibleFormat' => $request->flexibleFormat,
                'std_comments' => $std_comments,
                'overall_approval' => 1, // late enrolments are assumed pre-approved by HR Focal Points
                'admin_eform_comment' => 'late registration form [auto-generated]',
            ]);
            foreach ($ingredients as $data) {
                $data->save();
                // if (in_array($data->DEPT, ['UNOG', 'JIU', 'DDA', 'OIOS', 'DPKO'])) {
                //     $data->update([
                //         'overall_approval' => 1,
                //     ]);
                // }
            }
        }

        //execute Mail class before redirect

        $current_user = Auth::user()->indexno;
        $staff = $index_id;
        $next_term_code = $term_id;
        $tecode = $course_id;
        $formcount = $form_counter;

        // $this->sendApprovalEmailToHR($staff, $tecode, $formcount, $next_term_code);

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

    public function postPlacementInfo(Request $request)
    {
        $index_id = $request->input('index_id');
        $language_id = $request->input('L');
        $course_id = $request->input('course_id');
        $term_id = $request->input('term_id');
        //$schedule_id is an array 
        $schedule_id = $request->input('schedule_id');
        $uniquecode = $request->input('CodeIndexID');
        $org = $request->input('org');
        $agreementBtn = $request->input('agreementBtn');

        $this->validate($request, array(
            'placementLang' => 'required|integer',
            'approval' => 'required',
            'agreementBtn' => 'required|',
            'dayInput' => 'required|',
            'timeInput' => 'required|',
            'course_preference_comment' => 'required|',
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

        $qryEformCount = PlacementForm::withTrashed()
            ->where('INDEXID', $index_id)
            ->where('Term', $term_id)
            ->orderBy('eform_submit_count', 'desc')->first();

        $eform_submit_count = 1;
        if (isset($qryEformCount->eform_submit_count)) {
            $eform_submit_count = $qryEformCount->eform_submit_count + 1;
        }

        $placementForm = new PlacementForm;
        $placementForm->L = $language_id;
        $placementForm->profile = $request->profile;
        $placementForm->Term = $term_id;
        $placementForm->INDEXID = $index_id;
        $placementForm->DEPT = $org;
        $placementForm->country_mission = $request->input('countryMission');
        $placementForm->ngo_name = $request->input('ngoName');
        $placementForm->eform_submit_count = $eform_submit_count;
        $placementForm->approval = $request->approval;
        $placementForm->placement_schedule_id = $request->placementLang;
        $placementForm->std_comments = $request->std_comment;
        $placementForm->agreementBtn = $request->agreementBtn;
        $placementForm->overall_approval = 1;
        $placementForm->admin_plform_comment = 'late placement registration form [auto-generated]';
        $placementForm->save();

        // get newly created placement form record
        $latest_placement_form = placementForm::orderBy('id', 'desc')->where('INDEXID', Auth::user()->indexno)->where('Term', $term_id)->where('L', $language_id)->first();
        $placement_form_id = $latest_placement_form->id;
        $this->postPlacementInfoAdditional($request, $placement_form_id);
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

    public function lateSelfpayForm(Request $request)
    {
        $url = session()->get('url');
        $checkSelfPay = $request->session()->get('checkSelfPay');
        if ($checkSelfPay == 1) {

            $courses = Course::all();
            $languages = DB::table('languages')->pluck("name", "code")->all();
            $days = Day::pluck("Week_Day_Name", "Week_Day_Name")->except('Sunday', 'Saturday')->all();
            $now_date = Carbon::now()->toDateString();
            $now_year = Carbon::now()->year;

            // query the current term based on year and Term_End column is greater than today's date
            // whereYear('Term_End', $now_year)  
            // $terms = \App\Helpers\GlobalFunction::instance()->currentEnrolTermObject();
            $terms = Term::orderBy('Term_Code', 'desc')
                ->whereDate('Term_Begin', '>=', $now_date)
                ->get()->min();

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
                $org = Torgan::orderBy('Org name', 'asc')->get()->pluck('Org name', 'Org name');
                return view('form.late.late-selfpay-form', compact('courses', 'languages', 'terms', 'next_term', 'prev_term', 'repos', 'repos_lang', 'user', 'org', 'days'));
            }

            $repos_lang = Repo::orderBy('Term', 'desc')->where('Term', $student_last_term->Term)
                ->where('INDEXID', $current_user)->get();
            $org = Torgan::orderBy('Org name', 'asc')->get()->pluck('Org name', 'Org name');

            return view('form.late.late-selfpay-form', compact('courses', 'languages', 'terms', 'next_term', 'prev_term', 'repos', 'repos_lang', 'user', 'org', 'days'));
        } else {
            if ($url) {
                $errors = $request->session()->get('errors');
                if ($errors) {
                    return redirect()->to($url)->withErrors($errors->all());
                }
                return redirect()->to($url);
            }
            return redirect('home')->with('interdire-msg', 'Access denied. Please refer to the link that the CLM Secretariat sent to your email.');
        }
    }

    public function storeLateSelfpayForm(Request $request)
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
                    //var_dump($request->CodeIndexID);
                    // the validation below fails when CodeIndexID is already taken AND 
                    // deleted_at column is NULL which means it has not been cancelled AND
                    // there is an existing self-pay form
                    $this->validate($request, array(
                        'CodeIndexID' => Rule::unique('tblLTP_Enrolment')->where(function ($query) use ($request) {
                            $uniqueCodex = $request->CodeIndexID;
                            $query->where('CodeIndexID', $uniqueCodex)
                                ->where('deleted_at', NULL)
                                ->where('is_self_pay_form', 1);
                        })
                    ));
                }
            }
        }
        // 1st part of validate other input fields 
        $this->validate($request, array(
            'identityfile' => 'required|mimes:pdf,doc,docx|max:8000',
            'payfile' => 'required|mimes:pdf,doc,docx|max:8000',
        ));

        // validate fields for placement form
        if ($request->placementDecisionB === '0') {
            $this->validate($request, array(
                'placementLang' => 'required|integer',
                'agreementBtn' => 'required|',
                'dayInput' => 'required|',
                'timeInput' => 'required|',
                'course_preference_comment' => 'required|',
            ));
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

        //Store the attachments to storage path and save in db table
        if ($request->hasFile('identityfile')) {
            $request->file('identityfile');
            $filename = $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->identityfile->extension();
            //Store attachment
            $filestore = Storage::putFileAs('public/pdf/' . $index_id, $request->file('identityfile'), 'id_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->identityfile->extension());
            //Create new record in db table
            $attachment_identity_file = new File([
                'filename' => $filename,
                'size' => $request->identityfile->getClientSize(),
                'path' => $filestore,
            ]);
            $attachment_identity_file->save();
        }
        if ($request->hasFile('payfile')) {
            $request->file('payfile');
            $filename = $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->payfile->extension();
            //Store attachment
            $filestore = Storage::putFileAs('public/pdf/' . $index_id, $request->file('payfile'), 'payment_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->payfile->extension());
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
            $this->postSelfPayPlacementInfo($request, $attachment_pay_file, $attachment_identity_file);

            if ($request->is_self_pay_form == 1) {
                $request->session()->flash('success', 'Your Placement Test request has been submitted.');
                return redirect()->route('thankyouSelfPay');
            }

            $request->session()->flash('success', 'Your Placement Test request has been submitted.');
            return redirect()->route('thankyouPlacement');
        }

        // 2nd part of validate other input fields 
        $this->validate($request, array(
            'term_id' => 'required|',
            'schedule_id' => 'required|',
            'course_id' => 'required|',
            'L' => 'required|',
            'org' => 'required',
            'regular_enrol_comment' => 'required',
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
                'continue_bool' => $decision,
                'attachment_id' => $attachment_identity_file->id,
                'attachment_pay' => $attachment_pay_file->id,
                'is_self_pay_form' => 1,
                'eform_submit_count' => $eform_submit_count,
                'form_counter' => $form_counter,
                'DEPT' => $org,
                'country_mission' => $request->input('countryMission'),
                "ngo_name" => $request->input('ngoName'),
                'agreementBtn' => $agreementBtn,
                'consentBtn' => $consentBtn,
                'flexibleBtn' => $flexibleBtn,
                'flexibleFormat' => $request->flexibleFormat,
                'admin_eform_comment' => 'selfpay late registration form [auto-generated]',
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

        $qryEformCount = PlacementForm::withTrashed()
            ->where('INDEXID', $index_id)
            ->where('Term', $term_id)
            ->orderBy('eform_submit_count', 'desc')->first();

        $eform_submit_count = 1;
        if (isset($qryEformCount->eform_submit_count)) {
            $eform_submit_count = $qryEformCount->eform_submit_count + 1;
        }

        $placementForm = new PlacementForm;
        $placementForm->L = $language_id;
        $placementForm->profile = $request->profile;
        $placementForm->Term = $term_id;
        $placementForm->INDEXID = $index_id;
        $placementForm->DEPT = $org;
        $placementForm->country_mission = $request->input('countryMission');
        $placementForm->ngo_name = $request->input('ngoName');
        $placementForm->attachment_id = $attachment_identity_file->id;
        $placementForm->attachment_pay = $attachment_pay_file->id;
        $placementForm->is_self_pay_form = 1;
        $placementForm->eform_submit_count = $eform_submit_count;
        $placementForm->placement_schedule_id = $request->placementLang;
        $placementForm->std_comments = $request->std_comment;
        $placementForm->consentBtn = $request->consentBtn;
        $placementForm->agreementBtn = $request->agreementBtn;
        $placementForm->admin_plform_comment = 'late placement selfpay form [auto-generated]';
        $placementForm->save();
        // get newly created placement form record
        $latest_placement_form = placementForm::orderBy('id', 'desc')->where('INDEXID', Auth::user()->indexno)->where('Term', $term_id)->where('L', $language_id)->first();
        $placement_form_id = $latest_placement_form->id;
        $this->postPlacementInfoAdditional($request, $placement_form_id);
    }

    public function lateCheckPlacementFormAjax(Request $request)
    {
        if (Auth::check()) {
            $current_user = Auth::user()->indexno;
            $termCode = \App\Helpers\GlobalFunction::instance()->lateEnrolTermObject()->Term_Code;

            $placementData = PlacementForm::orderBy('Term', 'desc')
                ->where('INDEXID', $current_user)
                ->where('Term', $termCode)
                ->get();
            if (isset($placementData)) {
                $data = true;
            } else {
                $data = false;
            }
            $data = $placementData;
            return response()->json($data);
        }
    }

    public function lateCheckPlacementCourseAjax(Request $request)
    {
        if ($request->ajax()) {

            // first validation
            // get the last enrolment from PASHQ table including cancelled ones
            $repos_lang = Repo::withTrashed()->orderBy('Term', 'desc')->where('L', $request->L)->where('INDEXID', $request->index)->first();

            if (is_null($repos_lang)) {
                $repos_value = 0;
            } else {
                // get the Term value of the last enrolment from PASHQ table 
                $repos_value = $repos_lang->Term;
            }
            // get the previous term code of the previous term of the current enrolment term
            $current_enrol_term = \App\Helpers\GlobalFunction::instance()->lateEnrolTermObject();
            $prev_termCode = $current_enrol_term->Term_Prev;
            $prev_prev_TermCode = Term::orderBy('Term_Code', 'desc')->where('Term_Code', $prev_termCode)->value('Term_Prev');

            // query placement table if student placement enrolment data exists or not for the previous term
            $selectedTerm = $current_enrol_term->Term_Code;
            $lastDigit = substr($selectedTerm, -1);

            if ($lastDigit == 9) {
                // if autumn term, set summer term code as previous term 
                $prev_term = $selectedTerm - 1;
                // query placement table with summer term code
                $placementData = PlacementForm::withTrashed()->where('Term', $prev_term)->where('L', $request->L)->where('INDEXID', $request->index)->whereNotNull('CodeIndexID')
                    ->orWhere(function ($query) use ($prev_term, $request) {
                        $query->where('Term', $prev_term)->where('L', $request->L)->where('INDEXID', $request->index)->where('Result', '!=', null);
                    })->first();
            } else {
                // $placementData = null;
                $placementData = PlacementForm::withTrashed()->where('Term', $prev_termCode)->where('L', $request->L)->where('INDEXID', $request->index)->whereNotNull('CodeIndexID')
                    ->orWhere(function ($q) use ($prev_termCode, $request) {
                        $q->where('Term', $prev_termCode)->where('L', $request->L)->where('INDEXID', $request->index)->where('Result', '!=', null);
                    })->first();
            }

            // Questions: 
            // what is the threshold of a placement exam result? how long is it valid?
            // is it correct to assume that once a course has been assigned to a placement form,
            // that is the level that suits the student?
            // what about students that are not assigned a course because it is not offered in the 
            // next term?

            // if latest term for selected language is less than the 2 terms then true, take placement
            if (($repos_value < $prev_prev_TermCode) && empty($placementData)) {
                $data = true;
            } else {
                $data = false;
            }

            return response()->json($data);
        }
    }

    public function lateCheckEnrolmentEntriesAjax(Request $request)
    {
        if (Auth::check()) {
            $current_user = Auth::user()->indexno;
            $eformGrouped = Preenrolment::distinct('Te_Code')->where('INDEXID', '=', $current_user)
                ->where(function ($q) {
                    $latest_term = \App\Helpers\GlobalFunction::instance()->lateEnrolTermObject()->Term_Code;
                    // do NOT count number of submitted forms disapproved by manager or HR learning partner  
                    $q->where('Term', $latest_term)->where('deleted_at', NULL)
                        ->where('is_self_pay_form', NULL);
                })->count('eform_submit_count');

            $data = $eformGrouped;
            return response()->json($data);
        }
    }

    public function lateCheckPlacementEntriesAjax(Request $request)
    {
        if (Auth::check()) {
            $current_user = Auth::user()->indexno;
            $termCode = \App\Helpers\GlobalFunction::instance()->lateEnrolTermObject()->Term_Code;

            $placementFromCount = PlacementForm::orderBy('Term', 'desc')
                ->where('INDEXID', $current_user)
                ->where('Term', $termCode)
                ->get();

            $data = $placementFromCount;
            return response()->json($data);
        }
    }

    public function lateCheckSelfpayEntriesAjax()
    {
        if (Auth::check()) {
            $current_user = Auth::user()->indexno;
            $eformGrouped = Preenrolment::distinct('Te_Code')->where('INDEXID', '=', $current_user)
                ->where(function ($q) {
                    $latest_term = \App\Helpers\GlobalFunction::instance()->lateEnrolTermObject()->Term_Code;
                    // do NOT count number of submitted forms disapproved by manager or HR learning partner  
                    $q->where('Term', $latest_term)->where('deleted_at', NULL)
                        ->where('is_self_pay_form', 1);
                })->count('eform_submit_count');

            $data = $eformGrouped;
            return response()->json($data);
        }
    }

    public function lateCheckSelfpayPlacementEntriesAjax()
    {
        if (Auth::check()) {
            $current_user = Auth::user()->indexno;
            $termCode = \App\Helpers\GlobalFunction::instance()->lateEnrolTermObject()->Term_Code;
            $placementFromCount = PlacementForm::orderBy('Term', 'desc')
                ->where('INDEXID', $current_user)
                ->where('Term', $termCode)
                ->where('is_self_pay_form', 1)
                ->get();

            $data = $placementFromCount;
            return response()->json($data);
        }
    }
}
