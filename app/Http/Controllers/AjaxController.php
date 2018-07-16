<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Language;
use App\Course;
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

    public function ajaxIsCancelled(Request $request)
    {
        $current_user = Auth::user()->indexno;
        
        //query submitted forms based from tblLTP_Enrolment table
        $forms_submitted = Preenrolment::withTrashed()
            ->distinct('Te_Code')
            ->where('INDEXID', '=', $current_user)
            ->where('Term', $request->term )
            ->get(['Te_Code', 'INDEXID' ,'approval','approval_hr', 'DEPT', 'is_self_pay_form', 'continue_bool', 'deleted_at', 'form_counter']);

        $data = $forms_submitted;

        return response()->json($data); 
    }
    public function ajaxOrgSelect()
    {
        $select_org = Torgan::orderBy('Org Name', 'asc')->get(['Org Name','Org Full Name']);
        $data = view('ajax-org-select',compact('select_org'))->render();
        return response()->json([$data]);  
    }

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
        if($request->ajax()){            
            $select_courses = CourseSchedule::where('L', $request->L)
            ->where('Te_Term', $request->term_id)
            ->orderBy('id', 'asc')
            ->with('course')
            ->get()
            ->pluck("course.Description","Te_Code_New");

            $data = view('ajax-select',compact('select_courses'))->render();
            return response()->json(['options'=>$data]);
        }
    }

    public function selectAjaxLevelOne(Request $request)
    {
        if($request->ajax()){
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
            $select_schedules = CourseSchedule::where('Te_Code_New', $request->course_id)->where('Te_Term', $request->term_id )
            //Eager Load scheduler function and pluck using "dot" 
            ->with('scheduler')->get()->pluck('scheduler.name', 'schedule_id');

            $data = view('ajax-select2',compact('select_schedules'))->render();
            return response()->json(['options'=>$data]);
        }
    }

    public function ajaxCheckEnrolmentEntries()
    {
        $current_user = Auth::user()->indexno;
        $eformGrouped = Preenrolment::distinct('Te_Code')->where('INDEXID', '=', $current_user)
            ->where(function($q){ 
                $latest_term = \App\Helpers\GlobalFunction::instance()->currentEnrolTermObject()->Term_Code;
                // do NOT count number of submitted forms disapproved by manager or HR learning partner  
                $q->where('Term', $latest_term )->where('deleted_at', NULL)
                    ->where('is_self_pay_form', NULL)
                    ;
            })->count('eform_submit_count');

            $data = $eformGrouped;
            return response()->json($data);
            
    }

    public function ajaxCheckPlacementForm()
    {
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

    public function ajaxCheckPlacementEntries()
    {
        $current_user = Auth::user()->indexno;
        $termCode = \App\Helpers\GlobalFunction::instance()->currentEnrolTermObject()->Term_Code;

        $placementFromCount = PlacementForm::orderBy('Term', 'desc')
                ->where('INDEXID', $current_user)
                ->where('Term', $termCode)
                ->get();

        $data = $placementFromCount;
            return response()->json($data);
    }

    public function ajaxCheckSelfpayPlacementEntries()
    {
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

    public function ajaxCheckSelfpayEntries()
    {
        $current_user = Auth::user()->indexno;
        $eformGrouped = Preenrolment::distinct('Te_Code')->where('INDEXID', '=', $current_user)
            ->where(function($q){ 
                $latest_term = \App\Helpers\GlobalFunction::instance()->currentEnrolTermObject()->Term_Code;
                // do NOT count number of submitted forms disapproved by manager or HR learning partner  
                $q->where('Term', $latest_term )->where('deleted_at', NULL)
                    ->where('is_self_pay_form', 1)
                    ;
            })->count('eform_submit_count');

            $data = $eformGrouped;
            return response()->json($data);
    }
    
    /*
        checks whether student is NEW or missed 2 semesters
    */
    public function ajaxCheckPlacementCourse(Request $request)
    {
        if($request->ajax()){
            // get the last enrolment from PASHQ table
            $repos_lang = Repo::orderBy('Term', 'desc')->where('L', $request->L)->where('INDEXID', $request->index)->first();
            
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
            // // query placement exam table if student placement enrolment data exists or not
            $placementData = null; 

            // if latest term for selected language is less than the 2 terms then true, take placement
            if (($repos_value < $prev_prev_TermCode ) && $placementData == null) {
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
