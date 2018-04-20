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
use App\Schedule;
use App\Preenrolment;
use App\SDDEXTR;
use App\Torgan;
use App\PlacementSchedule;
use Session;
use Carbon\Carbon;
use DB;


class AjaxController extends Controller
{
    public function ajaxIsCancelled()
    {
        $current_user = Auth::user()->indexno;

        //query the current term based on year and Term_End column is greater than today's date
        //whereYear('Term_End', $now_year)  
                        //->first();
        $now_date = Carbon::now()->toDateString();
        $terms = Term::orderBy('Term_Code', 'desc')
                ->whereDate('Term_End', '>=', $now_date)
                ->get()->min();
        $next_term_code = Term::orderBy('Term_Code', 'desc')->where('Term_Code', '=', $terms->Term_Next)->get()->min('Term_Code');
        
        //query submitted forms based from tblLTP_Enrolment table
        $forms_submitted = Preenrolment::withTrashed()
            ->distinct('Te_Code')
            ->where('INDEXID', '=', $current_user)
            ->where('Term', $next_term_code )
            ->get(['Te_Code', 'INDEXID' ,'approval','approval_hr', 'DEPT', 'is_self_pay_form', 'continue_bool', 'deleted_at', 'form_counter']);

        $data = $forms_submitted;

        return response()->json($data); 
    }
    public function ajaxOrgSelect()
    {
        $select_org = Torgan::orderBy('Org Name', 'asc')->get()->pluck('Org name','Org name');
        $data = view('ajax-org-select',compact('select_org'))->render();
        return response()->json([$data]);  
    }

    public function ajaxOrgCompare(Request $request)
    {
        $ajaxOrg = $request->organization;
        $id = Auth::user()->id;
        $studentOrg = User::findOrFail($id)->sddextr->DEPT;
        if ($ajaxOrg != $studentOrg) {
            $data = false;
        } else {
            $data = true;
        }
        return response()->json($data);  
    }

    public function ajaxGetDate()
    {
        //get current year and date
        $now_date = Carbon::now();
        $now_year = Carbon::now()->year;       
        //return string of Cancel_Date_Limit of CURRENT term
        $cancel_date_limit = Term::whereYear('Term_End', $now_year)
                        ->orderBy('Term_Code', 'desc')
                        ->where('Term_End', '>=', $now_date)
                        ->min('Cancel_Date_Limit');
        
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
                //QUERY THE SCHEDULE BASED ON THE NEXT TERM 'Term_Next' of the CURRENT TERM RECORD/ROW in TERMS TABLE
                //NEXT TERM CODE MUST BE ENTERED IN TEVENTCUR TABLE
                $q->where('Te_Term', $latest_term->Term_Next );
            })

            //Eager Load scheduler function and pluck using "dot" 
            ->with('scheduler')->get()->pluck('scheduler.name', 'schedule_id');

            $data = view('ajax-select2',compact('select_schedules'))->render();
            return response()->json(['options'=>$data]);
        }
    }

    public function ajaxCheckPlacementCourse(Request $request)
    {
        if($request->ajax()){

            $repos_lang = Repo::orderBy('Term', 'desc')->where('L', $request->L)->where('INDEXID', $request->index)->first();
            
            if (is_null($repos_lang)) {
                $repos_value = 0;
            } else {
                $repos_value = $repos_lang->Term;
            }

            $now_date = Carbon::now()->toDateString();
            $terms = Term::orderBy('Term_Code', 'desc')
                    ->whereDate('Term_End', '>=', $now_date)
                    ->get()->min();

            $next_term = Term::orderBy('Term_Code', 'desc')->where('Term_Code', '=', $terms->Term_Next)->get()->min();
            // // query placement exam table if student placement enrolment data exists or not
            $placementData = null; 

            $difference =  $next_term->Term_Code - $repos_value;
            if (($repos_value == 0 || $difference > 9) && $placementData == null) {
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

            $now_date = Carbon::now()->toDateString();
            $terms = Term::orderBy('Term_Code', 'desc')
                    ->whereDate('Term_End', '>=', $now_date)
                    ->get()->min();

            $next_term = Term::orderBy('Term_Code', 'desc')->where('Term_Code', '=', $terms->Term_Next)->get()->min();
            $placement_schedule = PlacementSchedule::where('language_id', $request->L)->where('term', $next_term->Term_Code)->get();
            $data = $placement_schedule;
            return response()->json($data);            
        }
    }
}
