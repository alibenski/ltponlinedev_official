<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Language;
use App\Course;
use App\Country;
use App\User;
use App\Repo;
use App\Term;
use App\Classroom;
use App\CourseSchedule;
use App\Schedule;
use App\Preenrolment;
use App\SDDEXTR;
use App\Torgan;
use App\PlacementSchedule;
use App\PlacementForm;
use Session;
use Carbon\Carbon;
use DB;


class AjaxController extends Controller
{
    function ajaxSelectCountry(Request $request)
    {
        if ($request->ajax()) {
            $countries = Country::orderBy('ABBRV_NAME', 'asc')->whereNull('TERM_DATE')->get();

            $data = view('ajax-select-country', compact('countries'))->render();
            return response()->json(['options' => $data]);
        }
    }

    public function ajaxExcludeFromBilling(Request $request)
    {
        if ($request->ajax()) {
            $pashRecord = Repo::withTrashed()->find($request->id);
            $pashRecord->update([
                'exclude_from_billing' => 1,
                'excluded_by' => Auth::id(),
            ]);

            $data = $pashRecord;
            return response()->json(['options' => $data]);
        }
    }

    public function ajaxShowLanguageDropdown(Request $request)
    {
        if ($request->ajax()) {
            $languages = DB::table('languages')->pluck("name", "code")->all();
            $data = view('ajax-language-select-dropdown', compact('languages'))->render();
            return response()->json(['options' => $data]);
        }
    }

    public function ajaxShowFullSelectDropdown(Request $request)
    {
        if ($request->ajax()) {
            $languages = DB::table('languages')->pluck("name", "code")->all();
            $data = view('ajax-full-select-dropdown', compact('languages'))->render();
            return response()->json(['options' => $data]);
        }
    }

    public function ajaxChangeHRApproval(Request $request)
    {
        if ($request->ajax()) {
            $data = view('ajax-change-hr-approval')->render();
            return response()->json(['options' => $data]);
        }
    }

    public function ajaxChangeOrgInForm(Request $request)
    {
        if ($request->ajax()) {
            $org = Torgan::orderBy('Org name', 'asc')->get(['Org name', 'Org Full Name']);
            $data = view('ajax-change-org-in-form', compact('org'))->render();
            return response()->json(['options' => $data]);
        }
    }

    public function ajaxConvertToSelfpay(Request $request)
    {
        if ($request->ajax()) {
            $data = view('ajax-convert-to-selfpay')->render();
            return response()->json(['options' => $data]);
        }
    }

    public function ajaxConvertToRegular(Request $request)
    {
        if ($request->ajax()) {
            $data = view('ajax-convert-to-regular')->render();
            return response()->json(['options' => $data]);
        }
    }

    public function ajaxCheckBatchHasRan(Request $request)
    {
        if ($request->ajax()) {
            if ($request->Term == null) {
                $data = null;
                return response()->json($data);
            }
            $checker = Repo::where('Term', $request->Term)->first();

            $data = $checker;
            return response()->json($data);
        }
    }

    public function ajaxShowModal(Request $request)
    {
        $current_user = $request->indexno;
        $term_code = $request->term;
        // query submitted forms based from tblLTP_Enrolment table
        $schedules = Preenrolment::withTrashed()
            ->where('Te_Code', $request->tecode)
            ->where('INDEXID', $current_user)
            // ->where('approval', '=', $request->approval)
            ->where('form_counter', $request->form_counter)
            ->where('Term', $term_code)->get(['schedule_id', 'mgr_email', 'approval', 'approval_hr', 'is_self_pay_form', 'DEPT', 'deleted_at', 'INDEXID', 'Term', 'Te_Code', 'selfpay_approval']);
        // ->pluck('schedule.name', 'approval');

        $query = Preenrolment::withTrashed()->where('INDEXID', $current_user)
            ->where('Term', $term_code)
            ->where('Te_Code', $request->tecode)
            ->where('form_counter', $request->form_counter)
            ->groupBy(['Te_Code', 'Term', 'INDEXID', 'form_counter', 'deleted_at'])
            ->get(['Te_Code', 'Term', 'INDEXID', 'form_counter', 'deleted_at']);

        // render and return data values via AJAX
        $data = view('ajax-show-modal', compact('schedules', 'query'))->render();
        return response()->json([$data]);
    }

    public function ajaxShowModalPlacement(Request $request)
    {
        // query submitted placement forms 
        $placement_form = PlacementForm::withTrashed()
            ->where('id', $request->id)
            ->first();

        $prev_termCode = Term::where('Term_Code', $placement_form->Term)->first()->Term_Prev;

        // query if student is in waitlist table 
        // $waitlists = PlacementForm::with('waitlist')->where('INDEXID',$placement_form->INDEXID)->get();

        $waitlists = Repo::where('INDEXID', $placement_form->INDEXID)
            ->where('Term', $prev_termCode)
            ->whereHas('classrooms', function ($query) {
                $query->whereNull('Tch_ID')
                    ->orWhere('Tch_ID', '=', 'TBD');
            })
            ->get();

        // render and return data values via AJAX
        $data = view('ajax-show-modal-placement', compact('placement_form', 'waitlists'))->render();
        return response()->json([$data]);
    }

    /**
     * show schedules in modal
     */
    public function showScheduleSelfPay(Request $request)
    {
        if ($request->ajax()) {
            $selfpay_student = Preenrolment::select('INDEXID', 'Te_Code', 'profile', 'DEPT', 'flexibleBtn')->where('INDEXID', $request->index)->where('Te_Code', $request->tecode)->where('Term', $request->term)->first();
            $show_sched_selfpay = Preenrolment::where('INDEXID', $request->index)->where('Te_Code', $request->tecode)->where('Term', $request->term)->get();

            $data = view('selfpayforms.show', compact('selfpay_student', 'show_sched_selfpay'))->render();
            return response()->json(['options' => $data]);
        }
    }

    /**
     * show schedules in modal
     */
    public function postDecisionSelfPay()
    {
    }
    /**
     * delete day parameter when editing classrooms in classroom view
     */
    public function ajaxDeleteDayParam(Request $request)
    {
        $deleteDayParam = Classroom::where('id', $request->id)->first();
        $dayID = $request->dayID;
        if ($dayID == '2') {
            $deleteDayParam->Te_Mon = null;
            $deleteDayParam->Te_Mon_Room = null;
            $deleteDayParam->Te_Mon_BTime = null;
            $deleteDayParam->Te_Mon_Room = null;
            $deleteDayParam->save();
        }
        if ($dayID == '3') {
            $deleteDayParam->Te_Tue = null;
            $deleteDayParam->Te_Tue_Room = null;
            $deleteDayParam->Te_Tue_BTime = null;
            $deleteDayParam->Te_Tue_ETime = null;
            $deleteDayParam->save();
        }
        if ($dayID == '4') {
            $deleteDayParam->Te_Wed = null;
            $deleteDayParam->Te_Wed_Room = null;
            $deleteDayParam->Te_Wed_BTime = null;
            $deleteDayParam->Te_Wed_ETime = null;
            $deleteDayParam->save();
        }
        if ($dayID == '5') {
            $deleteDayParam->Te_Thu = null;
            $deleteDayParam->Te_Thu_Room = null;
            $deleteDayParam->Te_Thu_BTime = null;
            $deleteDayParam->Te_Thu_ETime = null;
            $deleteDayParam->save();
        }
        if ($dayID == '6') {
            $deleteDayParam->Te_Fri = null;
            $deleteDayParam->Te_Fri_Room = null;
            $deleteDayParam->Te_Fri_BTime = null;
            $deleteDayParam->Te_Fri_ETime = null;
            $deleteDayParam->save();
        }

        return response()->json($deleteDayParam);
    }

    /**
     * show sections in modal in classroom view
     */
    public function ajaxShowSection(Request $request)
    {
        if ($request->ajax()) {
            $show_classrooms = Classroom::where('cs_unique', $request->cs_unique)
                ->orderBy('sectionNo', 'asc')
                ->get();

            $data = view('classrooms.show', compact('show_classrooms'))->render();
            return response()->json(['options' => $data]);
        }
    }

    /**
     * get existing section no. in the classroom view
     */
    public function ajaxGetSectionNo(Request $request)
    {
        // get value of cs_unique and query in TEVENTcur if exists
        $cs_unique = $request->input('cs_unique');
        $cs_exist = Classroom::orderBy('Te_Term', 'desc')->orderBy('sectionNo', 'desc')->where('cs_unique', $cs_unique)->get();
        $status = isset($cs_exist[0]) ? $cs_exist[0] : false;
        // if null, then sectionValue = 1
        if (!$status) {
            $data = 1;
            // $data = var_dump($data);
        } else { // if exists, plus 1 to the value and return to DOM
            $data = $cs_exist[0]['sectionNo'] + 1;
        }

        return response()->json($data);
    }

    public function ajaxGetSectionParam(Request $request)
    {
        if ($request->ajax()) {
            $show_classrooms = Classroom::where('cs_unique', $request->cs_unique)
                ->first();

            $data = $show_classrooms;
            return response()->json(['options' => $data]);
        }
    }

    /**
     * check if enrolment form is cancelled in submitted forms view
     */
    public function ajaxIsCancelled(Request $request)
    {
        if (Auth::check()) {
            $current_user = Auth::user()->indexno;

            //query submitted forms based from tblLTP_Enrolment table
            $forms_submitted = Preenrolment::withTrashed()
                ->distinct('Te_Code')
                ->where('INDEXID', '=', $current_user)
                ->where('Term', $request->term)
                ->get(['Te_Code', 'INDEXID', 'approval', 'approval_hr', 'DEPT', 'is_self_pay_form', 'continue_bool', 'deleted_at', 'form_counter']);

            $data = $forms_submitted;

            return response()->json($data);
        }
    }

    public function ajaxOrgSelect()
    {
        $select_org = Torgan::orderBy('Org name', 'asc')->get(['Org name', 'Org Full Name']);
        $data = view('ajax-org-select', compact('select_org'))->render();
        return response()->json([$data]);
    }

    /**
     * compare if chosen organization in whatorg view is the same in User Model 
     */
    public function ajaxOrgCompare(Request $request)
    {
        $ajaxOrg = $request->organization;
        $id = Auth::user()->id;
        $studentOrg = User::findOrFail($id)->sddextr->DEPT;
        $torgan = Torgan::where('Org name', $ajaxOrg)->first();
        if ($ajaxOrg != $studentOrg) {
            $data = false;
        } else {
            $data = true;
        }
        return response()->json([$data, $torgan]);
    }

    /**
     * on doc ready, disable cancel button in submitted forms view based on cancel date in Terms table 
     */
    public function ajaxGetDate(Request $request)
    {
        //get current date
        $now_date = Carbon::now();
        //return string of Cancel_Date_Limit 
        $cancel_date_limit = Term::orderBy('Term_Code', 'desc')
            ->where('Term_Code', $request->term)
            ->value('Cancel_Date_Limit');
        if ($now_date > $cancel_date_limit) {
            $data = 'disabled';
        } else {
            $data = 'enabled';
        }
        return response()->json($data);
    }

    /**
     * Show the application selectAjax.
     *
     * @return \Illuminate\Http\response
     */
    public function selectAjax(Request $request)
    {
        if ($request->ajax()) {
            $select_courses = CourseSchedule::where('L', $request->L)
                ->where('Te_Term', $request->term_id)
                ->orderBy('Te_Price', 'asc')
                ->orderBy('Te_Code_New', 'asc')
                ->select('Te_Code_New','specialized', 'L', 'Te_Term', 'Te_Price')
                ->with('course')
                ->groupBy('Te_Code_New','specialized', 'L', 'Te_Term', 'Te_Price')
                ->get()
                ->groupBy('specialized');
                // ->pluck("course.Description", "Te_Code_New");
            $data = view('ajax-select', compact('select_courses'))->render();
            return response()->json(['options' => $data]);
        }
    }

    /**
     * ajax select on vsa-page-2 admin page
     */
    public function selectAjaxAdmin(Request $request)
    {
        if ($request->ajax()) {
            $select_courses = CourseSchedule::where('L', $request->L)
                ->where('Te_Term', $request->term_id)
                // ->whereNull('Code')
                // ->orderBy('id', 'asc')
                ->orderBy('Te_Code_New', 'asc')
                ->select('Te_Code_New','specialized', 'L', 'Te_Term')
                ->with('course')
                ->groupBy('Te_Code_New','specialized', 'L', 'Te_Term')
                ->get()
                ->groupBy('specialized');
                // ->pluck("course.Description", "Te_Code_New");

            $data = view('ajax-select', compact('select_courses'))->render();
            return response()->json(['options' => $data]);
        }
    }

    /**
     * ajax select if student level one of any language in enrolment form
     */
    public function selectAjaxLevelOne(Request $request)
    {
        if ($request->ajax()) {
            $select_courses = Course::where('L', $request->L)
                ->whereNotNull('Te_Code_New')
                ->orderBy('id', 'asc')
                ->pluck("Description", "Te_Code_New")
                ->all();

            $data = view('ajax-select3', compact('select_courses'))->render();
            return response()->json(['options' => $data]);
        }
    }

    /**
     * ajax select returns available schedules associated to the course selected in enrolment form
     */
    public function selectAjax2(Request $request)
    {
        if ($request->ajax()) {
            $select_schedules = CourseSchedule::where('Te_Code_New', $request->course_id)->where('Te_Term', $request->term_id)
                //Eager Load scheduler function and pluck using "dot" 
                ->with('scheduler')->get()->pluck('scheduler.name', 'schedule_id');

            $data = view('ajax-select2', compact('select_schedules'))->render();
            return response()->json(['options' => $data]);
        }
    }

    /**
     * ajax select returns available schedules associated to the course selected in student can edit form
     */
    public function selectAjaxStudentEdit(Request $request)
    {
        if ($request->ajax()) {
            $select_schedules = CourseSchedule::where('Te_Code_New', $request->course_id)->where('Te_Term', $request->term_id)
                //Eager Load scheduler function and pluck using "dot" 
                ->with('scheduler')->get()->pluck('scheduler.name', 'schedule_id');

            $data = view('ajax-select-student-edit', compact('select_schedules'))->render();
            return response()->json(['options' => $data]);
        }
    }

    public function ajaxCheckEnrolmentEntries()
    {
        if (Auth::check()) {
            $current_user = Auth::user()->indexno;
            $eformGrouped = Preenrolment::distinct('Te_Code')->where('INDEXID', '=', $current_user)
                ->where(function ($q) {
                    $latest_term = \App\Helpers\GlobalFunction::instance()->currentEnrolTermObject()->Term_Code;
                    // do NOT count number of submitted forms disapproved by manager or HR learning partner  
                    $q->where('Term', $latest_term)->where('deleted_at', NULL)
                        ->where('is_self_pay_form', NULL);
                })->count('eform_submit_count');

            $data = $eformGrouped;
            return response()->json($data);
        }
    }

    public function ajaxCheckPlacementForm()
    {
        if (Auth::check()) {
            $current_user = Auth::user()->indexno;
            $termCode = \App\Helpers\GlobalFunction::instance()->currentEnrolTermObject()->Term_Code;

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

    public function ajaxCheckPlacementEntries()
    {
        if (Auth::check()) {
            $current_user = Auth::user()->indexno;
            $termCode = \App\Helpers\GlobalFunction::instance()->currentEnrolTermObject()->Term_Code;

            $placementFromCount = PlacementForm::orderBy('Term', 'desc')
                ->where('INDEXID', $current_user)
                ->where('Term', $termCode)
                ->get();

            $data = $placementFromCount;
            return response()->json($data);
        }
    }

    public function ajaxCheckSelfpayPlacementEntries()
    {
        if (Auth::check()) {
            $current_user = Auth::user()->indexno;
            $termCode = \App\Helpers\GlobalFunction::instance()->currentEnrolTermObject()->Term_Code;
            $placementFromCount = PlacementForm::orderBy('Term', 'desc')
                ->where('INDEXID', $current_user)
                ->where('Term', $termCode)
                ->where('is_self_pay_form', 1)
                ->get();

            $data = $placementFromCount;
            return response()->json($data);
        }
    }

    public function ajaxCheckSelfpayEntries()
    {
        if (Auth::check()) {
            $current_user = Auth::user()->indexno;
            $eformGrouped = Preenrolment::distinct('Te_Code')->where('INDEXID', '=', $current_user)
                ->where(function ($q) {
                    $latest_term = \App\Helpers\GlobalFunction::instance()->currentEnrolTermObject()->Term_Code;
                    // do NOT count number of submitted forms disapproved by manager or HR learning partner  
                    $q->where('Term', $latest_term)->where('deleted_at', NULL)
                        ->where('is_self_pay_form', 1);
                })->count('eform_submit_count');

            $data = $eformGrouped;
            return response()->json($data);
        }
    }

    /*
        checks whether student is NEW or missed 2 semesters
    */
    public function ajaxCheckPlacementCourse(Request $request)
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
            $current_enrol_term = \App\Helpers\GlobalFunction::instance()->currentEnrolTermObject();
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
                ->orWhere(function($query) use($prev_term, $request){
                    $query->where('Term', $prev_term)->where('L', $request->L)->where('INDEXID', $request->index)->where('Result', '!=', null);
                })->first();
            } else {
                // $placementData = null;
                $placementData = PlacementForm::withTrashed()->where('Term', $prev_termCode)->where('L', $request->L)->where('INDEXID', $request->index)->whereNotNull('CodeIndexID')
                ->orWhere(function($q) use($prev_termCode, $request){
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

    public function ajaxCheckPlacementSched(Request $request)
    {
        if ($request->ajax()) {

            $placement_schedule = PlacementSchedule::where('language_id', $request->L)->where('term', $request->term_id)->get();
            $data = $placement_schedule;
            return response()->json($data);
        }
    }

    public function ajaxGetTermData(Request $request)
    {
        if ($request->ajax()) {

            $term_data = Term::where('Term_Code', $request->term)->get();

            $data = $term_data;
            return response()->json($data);
        }
    }
}
