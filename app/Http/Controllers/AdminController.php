<?php

namespace App\Http\Controllers;

use App\Classroom;
use App\CourseSchedule;
use App\NewUser;
use App\PlacementForm;
use App\Preenrolment;
use App\Preview;
use App\Repo;
use App\Services\User\ExistingUserImport;
use App\Services\User\UserImport;
use App\Term;
use App\Torgan;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Session;


class AdminController extends Controller
{
    public function adminExportOcha()
    {
        return view('admin.admin-export-ocha');
    }

    public function adminExtractData(Request $request)
    {
        if ($request->ajax()) {

            $terms = Repo::where('Term', '>', '190')->select('Term')->groupBy('Term')->get()->toArray();
            $array = [];
            foreach ($terms as $v) {
                $array[] = $v['Term'];
            }
            $collect_term = Term::whereIn('Term_Code', $array)->get();

            $records_merged = [];

            foreach ($collect_term as $value) {
                $term = $value->Term_Code;
                $termCancelDeadline = Term::where('Term_Code', $term)->first()->Cancel_Date_Limit;
                
                $records = new Repo;
                $records = $records->where('Term', $value->Term_Code)->whereNull('is_self_pay_form');
                    
                $records_1 = $records->with('users')
                    ->where('DEPT', 'OCHA')
                    ->with('terms')
                    ->with('courses')
                    ->with('languages')
                    ->whereNull('exclude_from_billing')
                    ->with(['courseschedules' => function ($q1) {
                        $q1->with('prices')->with('courseduration');
                    }])
                    ->with('classrooms')
                    ->whereHas('classrooms', function ($query1) {
                        $query1->whereNotNull('Tch_ID')
                            ->where('Tch_ID', '!=', 'TBD');
                    })
                    ->with('enrolments')
                    ->whereHas('enrolments', function ($query11) use ($term) {
                        $query11->where('Term', $term)->whereNull('is_self_pay_form');
                    })
                    ->get();

                $pashFromPlacement = new Repo;
                $pashFromPlacement = $pashFromPlacement->where('Term', $value->Term_Code)->whereNull('is_self_pay_form');
                
                $records_0 = $pashFromPlacement->with('users')
                    ->where('DEPT', 'OCHA')
                    ->with('terms')
                    ->with('courses')
                    ->with('languages')
                    ->whereNull('exclude_from_billing')
                    ->with(['courseschedules' => function ($q0) {
                        $q0->with('prices')->with('courseduration');
                    }])
                    ->with('classrooms')
                    ->whereHas('classrooms', function ($query0) {
                        $query0->whereNotNull('Tch_ID')
                            ->where('Tch_ID', '!=', 'TBD');
                    })
                    ->with('placements')
                    ->whereHas('placements', function ($query00) use ($term) {
                        $query00->where('Term', $term)->whereNull('is_self_pay_form');
                    })
                    ->get();

                
                // MUST INCLUDE QUERY WHERE deleted_at > cancellation deadline
                $cancelledEnrolmentRecords = new Repo;
                $cancelledEnrolmentRecords = $cancelledEnrolmentRecords->where('Term', $value->Term_Code)->whereNull('is_self_pay_form');
    
                $records_2 = $cancelledEnrolmentRecords->onlyTrashed()->with('users')
                    ->where('DEPT', 'OCHA')
                    ->with('terms')
                    ->where('deleted_at', '>', $termCancelDeadline)
                    ->whereNull('cancelled_but_not_billed')
                    ->with('courses')
                    ->with('languages')
                    ->whereNull('exclude_from_billing')
                    ->with(['courseschedules' => function ($q2) {
                        $q2->with('prices')->with('courseduration');
                    }])
                    ->with('classrooms')
                    ->whereHas('classrooms', function ($query2) {
                        $query2->whereNotNull('Tch_ID')
                            ->where('Tch_ID', '!=', 'TBD');
                    })
                    ->with('enrolments')
                    ->whereHas('enrolments', function ($query22) use ($term) {
                        $query22->where('Term', $term)->whereNull('is_self_pay_form');
                    })
                    ->get();


                $cancelledPlacementRecords = new Repo;
                $cancelledPlacementRecords = $cancelledPlacementRecords->where('Term', $value->Term_Code)->whereNull('is_self_pay_form');
    
                $records_3 = $cancelledPlacementRecords->onlyTrashed()->with('users')
                    ->where('DEPT', 'OCHA')
                    ->with('terms')
                    ->where('deleted_at', '>', $termCancelDeadline)
                    ->whereNull('cancelled_but_not_billed')
                    ->with('courses')
                    ->with('languages')
                    ->whereNull('exclude_from_billing')
                    ->with(['courseschedules' => function ($q3) {
                        $q3->with('prices')->with('courseduration');
                    }])
                    ->with('classrooms')
                    ->whereHas('classrooms', function ($query3) {
                        $query3->whereNotNull('Tch_ID')
                            ->where('Tch_ID', '!=', 'TBD');
                    })
                    ->with('placements')
                    ->whereHas('placements', function ($query33) use ($term) {
                        $query33->where('Term', $term)->whereNull('is_self_pay_form');
                    })
                    ->get();
                
                
                $records_merged[] = $records_1->merge($records_0)->merge($records_2)->merge($records_3);
            }
            
            $arr = [];
            foreach ($records_merged as $val) {
                foreach ($val as $balyu) {
                    $arr[] = $balyu;
                }
            }
            
            $data = $arr;
            
            return response()->json(['data' => $data]);
        }
    }

    public function adminExtractData2018(Request $request)
    {
        $terms = Repo::whereBetween('Term', ['180' , '190'])->select('Term')->groupBy('Term')->get()->toArray();
        $array = [];
        foreach ($terms as $v) {
            $array[] = $v['Term'];
        }

        $collect_term = Term::whereIn('Term_Code', $array)->get();

        $records_merged = [];

        foreach ($collect_term as $value) {
            $term = $value->Term_Code;
            
            $records = new Repo;
            $records = $records->where('Term', $term);
        
            $records_1 = $records
                ->where('DEPT', 'OCHA')
                ->with('terms')
                ->with('coursesOld')
                ->with('languages')
                ->get();

            $records_merged[] = $records_1;
        }

        $arr = [];
        foreach ($records_merged as $val) {
            foreach ($val as $balyu) {
                $arr[] = $balyu;
            }
        }
        
        $data = $arr;

        return response()->json(['data' => $data]);
    }


    public function adminExportMoodle()
    {
        $terms = Term::where('Term_Code', '>', '190')->orderBy('Term_Code', 'desc')->get();
        return view('admin.admin-export-moodle', compact('terms'));
    }

    public function adminPlacementExportMoodle(Request $request)
    {
        if ($request->term) {
            $term = $request->term;
            $fromPlacements = Repo::where('Term', $term)
                ->whereHas('classrooms', function($q3)
                {
                    $q3->whereNotNull('Tch_ID')
                        ->where('Tch_ID', '!=', 'TBD');
                })
                ->whereHas('courses', function($q4)
                {
                    $q4->where('level', '!=', '1');
                })
                ->whereHas('placements', function($query) use ($term){
                    $query->where('Term', $term)->whereIn('L', ['A','C','R','S'])->whereNotNull('CodeIndexID');
                })
                // ->whereRaw('SUBSTRING(Te_Code, 2, 2) = "1R"')
                ->whereIn('L', ['A','C','R','S'])
                ->with('users')->select('INDEXID')->groupBy('INDEXID')->get()->sortBy('INDEXID');
            
            $array2 = [];
            $arr2_exists = [];
            foreach ($fromPlacements as $value2) {
                $existing2 = Repo::where('Term', '>', '190')->where('Term', '<', $term)->where('INDEXID', $value2->INDEXID)->exists();
                if($existing2 === false){
                    $array2[] = [
                        'INDEXID' => $value2->INDEXID,
                        'lastname' => $value2->users->nameLast,
                        'firstname' => $value2->users->nameFirst,
                        'email' => $value2->users->email,
                        // 'Te_Code' => $value2->Te_Code,
                    ];
                } else {
                    $arr2_exists[] = [
                        'INDEXID' => $value2->INDEXID,
                        'lastname' => $value2->users->nameLast,
                        'firstname' => $value2->users->nameFirst,
                        'email' => $value2->users->email,
                        // 'Te_Code' => $value2->Te_Code,
                    ];
                }
            }
    
            $data = $array2;
            
            return response()->json($data);
        }

    }

    public function adminQueryExportMoodle(Request $request)
    {
        if ($request->term) {
            $term = $request->term;
            $pash_records = Repo::where('Term', $term)
                ->whereHas('classrooms', function($q)
                {
                    $q->whereNotNull('Tch_ID')
                        ->where('Tch_ID', '!=', 'TBD');
                })
                // ->where('Te_Code', 'like', "%1R%")
                // ->where(\DB::raw('substr(Te_Code, 2, 2)'), '=' , '1R')
                // ->whereRaw('SUBSTRING(Te_Code, 2, 2) = "1R"')
                // ->whereIn('L', ['A','C','R','S'])
                ->whereHas('courses', function($q2)
                {
                    $q2->where('level', '1');
                })
                ->with('users')->select('INDEXID')->groupBy('INDEXID')->get()->sortBy('Te_Code');
            $array = [];
            $arr_exists = [];
            // dd($pash_records);
            foreach ($pash_records as $key => $value) {
                $existing = Repo::where('Term', '>', '190')->where('Term', '<', $term)->where('INDEXID', $value->INDEXID)->exists();
                // $array[] = $existing;
                if($existing === false){
                    $array[] = [
                        'INDEXID' => $value->INDEXID,
                        'lastname' => $value->users->nameLast,
                        'firstname' => $value->users->nameFirst,
                        'email' => $value->users->email,
                        // 'Te_Code' => $value->Te_Code,
                    ];
                } else {
                    $arr_exists[] = [
                        'INDEXID' => $value->INDEXID,
                        'lastname' => $value->users->nameLast,
                        'firstname' => $value->users->nameFirst,
                        'email' => $value->users->email,
                        // 'Te_Code' => $value->Te_Code,
                    ];
                }
            }
    
            $data = $array;
            
            return response()->json($data);
        }
    }

    public function adminExcelSchedule()
    {
        $term = Session::get('Term');
        $course_schedule = CourseSchedule::orderBy('L', 'asc')->where('Te_Term', $term)->get();

        return view('admin.admin-excel-schedule', compact('course_schedule', 'term'));
    }

    public function adminStudentEmailView()
    {
        return view('admin.admin-student-email-view');
    }

    public function getAdminAllCurrentStudentInTerm(Request $request)
    {
        if ($request->ajax()) {
            $term = Term::orderBy('Term_Code', 'desc')->where('Term_Code', $request->term)->first();
            // query all students enrolled to current term excluding waitlisted
            $query_students_current_term = Repo::select('INDEXID', 'Term', 'CodeClass', 'Code', 'Te_Code', 'L', 'DEPT')->where('Term', $term->Term_Code)
                ->whereHas('classrooms', function ($q) {
                    $q->select('CodeClass', 'Code', 'Tch_ID')->whereNotNull('Tch_ID')->where('Tch_ID', '!=', 'TBD');
                })
                ->with('users.sddextr') // access sddextr model via user model relationship
                ->with('languages')
                ->with('courses')
                ->with('classrooms.teachers')
                ->get();

            $data = $query_students_current_term;
            return response()->json($data);
        }
    }

    public function adminStudentsWithWaitlistView()
    {
        return view('admin.admin-students-with-waitlist-view');
    }
    public function getAdminAllCurrentStudentWithWaitlistInTerm(Request $request)
    {
        if ($request->ajax()) {
            $term = Term::orderBy('Term_Code', 'desc')->where('Term_Code', $request->term)->first();
            // query all students enrolled to current term including waitlisted
            $query_students_current_term = Repo::select('INDEXID', 'Term', 'CodeClass', 'Code', 'Te_Code', 'L', 'DEPT')->where('Term', $term->Term_Code)
                // ->whereHas('classrooms', function ($q) {
                //     $q->select('CodeClass', 'Code', 'Tch_ID')->whereNotNull('Tch_ID')->where('Tch_ID', '!=', 'TBD');
                // })
                ->with('users.sddextr') // access sddextr model via user model relationship
                ->with('languages')
                ->with('courses')
                ->with('classrooms.teachers')
                ->get();

            $data = $query_students_current_term;
            return response()->json($data);
        }
    }

    /**
     * Copy Preview table to PASH table
     */
    public function moveToPash()
    {
        $results = \DB::select("INSERT into LTP_PASHQTcur (INDEXID,CodeIndexIDClass,CodeClass,CodeIndexID,Code,schedule_id,Te_Code,L,is_self_pay_form,flexibleBtn,convocation_email_sent,form_counter,eform_submit_count,Term,DEPT,PS,created_at,UpdatedOn,deleted_at,EMAIL,Comments,std_comments, hr_comments, teacher_comments,  admin_eform_comment, admin_plform_comment, course_preference_comment) SELECT INDEXID,CodeIndexIDClass,CodeClass,CodeIndexID,Code,schedule_id,Te_Code,L,is_self_pay_form,flexibleBtn,convocation_email_sent,form_counter,eform_submit_count,Term,DEPT,PS,created_at,UpdatedOn,deleted_at,EMAIL,Comments,std_comments, hr_comments, teacher_comments, admin_eform_comment, admin_plform_comment, course_preference_comment FROM tblLTP_preview");
    }

    public function setSessionTerm(Request $request)
    {
        $new_user_count = NewUser::where('approved_account', 0)->count();
        $terms = Term::orderBy('Term_Code', 'desc')->get();

        $request->session()->put('Term', $request->Term);

        // return view('admin.index',compact('terms'))->withNew_user_count($new_user_count);   
        return redirect()->back();
    }

    public function adminFullyApprovedFormsNotInClass(Request $request)
    {
        $terms = Term::orderBy('Term_Code', 'desc')->get();

        if ($request->session()->has('Term')) {
            $termSet = Term::orderBy('Term_Code', 'desc')->where('Term_Code', $request->session()->get('Term'))->first();

            $arrIndexEnrolment = [];
            $approvedEnrolmentForms = Preenrolment::select('INDEXID', 'L')
                ->where('Term', $request->session()->get('Term'))
                ->where('overall_approval', 1)
                ->whereNotNull('modified_by')
                ->groupBy('INDEXID', 'L')
                ->get();

            foreach ($approvedEnrolmentForms as $key => $value) {
                $arrIndexEnrolment[] = $value->INDEXID.'-'.$value->L;
            }

            $arrIndexPlacement = [];
            $approvedPlacementForms = PlacementForm::select('INDEXID','L')
                ->where('Term', $request->session()->get('Term'))
                ->where('overall_approval', 1)
                ->whereNotNull('modified_by')
                ->groupBy('INDEXID','L')
                ->get();

            foreach ($approvedPlacementForms as $keyP => $valueP) {
                $arrIndexPlacement[] = $valueP->INDEXID.'-'.$valueP->L;
            }

            $arrIndexPASH = [];
            $qryPASH = Repo::withTrashed()->select('INDEXID','L')
                ->where('Term', $request->session()->get('Term'))
                ->groupBy('INDEXID','L')
                ->get();

            foreach ($qryPASH as $key1 => $value1) {
                $arrIndexPASH[] = $value1->INDEXID.'-'.$value1->L;
            }

            $diffEnrolPASH = array_diff($arrIndexEnrolment, $arrIndexPASH);
            $diffPlacementPASH = array_diff($arrIndexPlacement, $arrIndexPASH);

            $merge = array_merge($diffPlacementPASH, $diffEnrolPASH);

            $explodeArray = [];
            $explodeIndex = [];
            $explodeLang = [];
            foreach ($merge as $keyM => $valueM) {
                $explodeArray[] = explode('-', $valueM);
            }
            foreach ($explodeArray as $keyE => $valueE) {
                $explodeIndex[] = $valueE[0];
                $explodeLang[] = $valueE[1];
            }

            $studentIndexEnrol = User::whereIn('indexno', $explodeIndex)
                ->with(['preenrolment' => function ($qry) use ($request, $explodeLang) {
                    $qry->where('Term', $request->session()->get('Term'))->whereIn('L', $explodeLang);
                }])
                ->whereHas('preenrolment', function ($q1) use ($request, $explodeLang) {
                    $q1->where('Term', $request->session()->get('Term'))
                        ->whereIn('L', $explodeLang)
                        ->whereNotNull('modified_by');
                })
                ->get();

            $studentIndexPlacement = User::whereIn('indexno', $explodeIndex)
                ->with(['placement' => function ($qry) use ($request, $explodeLang) {
                    $qry->where('Term', $request->session()->get('Term'))->whereIn('L', $explodeLang);
                }])
                ->whereHas('placement', function ($q2) use ($request, $explodeLang) {
                    $q2->where('Term', $request->session()->get('Term'))
                        ->whereIn('L', $explodeLang)
                        ->whereNotNull('modified_by');
                })
                ->get();
            // dd($studentIndexEnrol,$studentIndexPlacement);
            return view('admin.fully-approved-forms-not-in-class', compact('terms', 'termSet', 'merge', 'studentIndexEnrol', 'studentIndexPlacement'));
        }

        return view('admin.fully-approved-forms-not-in-class');
    }

    public function adminViewClassrooms(Request $request)
    {
        $assigned_classes = Classroom::where('Code', $request->Code)
            ->where('Te_Term', Session::get('Term'))
            ->get();

        return view('admin.admin-view-classrooms', compact('assigned_classes'));
    }

    public function adminIndex(Request $request)
    {
        $terms = Term::orderBy('Term_Code', 'desc')->get();

        $new_user_count = NewUser::where('approved_account', 0)->count();
        $cancelled_convocations = Repo::onlyTrashed()->where('Term', Session::get('Term'))->count();
        $enrolment_forms = Preenrolment::select('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
            ->groupBy('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
            ->where('Term', Session::get('Term'))
            ->get()->count();
        $selfpay_enrolment_forms = Preenrolment::select('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
            ->groupBy('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
            ->where('Term', Session::get('Term'))
            ->where('is_self_pay_form', '1')
            ->get()->count();

        $selfpay_enrolment_forms_validated = Preenrolment::select('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
            ->groupBy('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
            ->where('Term', Session::get('Term'))
            ->where('is_self_pay_form', '1')
            ->where('selfpay_approval', '1')
            ->get()->count();
        $selfpay_enrolment_forms_pending = Preenrolment::select('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
            ->groupBy('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
            ->where('Term', Session::get('Term'))
            ->where('is_self_pay_form', '1')
            ->where('selfpay_approval', '2')
            ->get()->count();
        $selfpay_enrolment_forms_disapproved = Preenrolment::select('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
            ->groupBy('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
            ->where('Term', Session::get('Term'))
            ->where('is_self_pay_form', '1')
            ->where('selfpay_approval', '0')
            ->get()->count();
        $selfpay_enrolment_forms_waiting = Preenrolment::select('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
            ->groupBy('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
            ->where('Term', Session::get('Term'))
            ->where('is_self_pay_form', '1')
            ->where('selfpay_approval', null)
            ->get()->count();


        $placement_forms = PlacementForm::select('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
            ->groupBy('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
            ->where('Term', Session::get('Term'))
            ->get()->count();
        $selfpay_placement_forms = PlacementForm::select('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
            ->groupBy('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
            ->where('Term', Session::get('Term'))
            ->where('is_self_pay_form', '1')
            ->get()->count();
        $selfpay_placement_forms_validated = PlacementForm::select('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
            ->groupBy('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
            ->where('Term', Session::get('Term'))
            ->where('is_self_pay_form', '1')
            ->where('selfpay_approval', '1')
            ->get()->count();
        $selfpay_placement_forms_pending = PlacementForm::select('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
            ->groupBy('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
            ->where('Term', Session::get('Term'))
            ->where('is_self_pay_form', '1')
            ->where('selfpay_approval', '2')
            ->get()->count();
        $selfpay_placement_forms_disapproved = PlacementForm::select('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
            ->groupBy('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
            ->where('Term', Session::get('Term'))
            ->where('is_self_pay_form', '1')
            ->where('selfpay_approval', '0')
            ->get()->count();
        $selfpay_placement_forms_waiting = PlacementForm::select('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
            ->groupBy('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
            ->where('Term', Session::get('Term'))
            ->where('is_self_pay_form', '1')
            ->where('selfpay_approval', null)
            ->get()->count();

        $arr3_count = 0;
        if (Session::has('Term')) {
            $term = Session::get('Term');
            $prev_term = Term::where('Term_Code', $term)->first()->Term_Prev;

            $students_in_class = Repo::where('Term', $prev_term)->whereHas('classrooms', function ($query) {
                $query->whereNotNull('Tch_ID')
                    ->where('Tch_ID', '!=', 'TBD');
            })
                ->get();
            $arr1 = [];
            foreach ($students_in_class as $key1 => $value1) {
                $arr1[] = $value1->INDEXID;
            }
            $arr1 = array_unique($arr1);
            // echo "Total Number of Students in Class for ".$prev_term.": ".count($arr1);
            // echo "<br>";

            $enrolment_forms_2 = Preenrolment::select('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
                ->groupBy('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
                ->where('Term', Session::get('Term'))
                ->where('overall_approval', 1)
                ->whereNull('updated_by_admin')
                ->get();

            // echo "Total Number of Enrolment Forms in ".$term.": ".count($enrolment_forms_2);
            // echo "<br>";

            $arr2 = [];
            foreach ($enrolment_forms_2 as $key2 => $value2) {
                $arr2[] = $value2->INDEXID;
            }
            $arr2 = array_unique($arr2);

            // echo "Total Number of People who submitted Re-Enrolment/Enrolment Forms for term ".$term.": ".count($arr2);
            // echo "<br>";

            $students_not_in_class = array_diff($arr2, $arr1); // get all enrolment_forms not included in students_in_class
            $unique_students_not_in_class = array_unique($students_not_in_class);

            // echo "Total Number of People NOT in Class for ".$term.": ".count($unique_students_not_in_class);
            // echo "<br>";

            $arr3 = [];
            foreach ($unique_students_not_in_class as $key3 => $value3) {
                $forms = Preenrolment::where('Term', $term)->where('INDEXID', $value3)
                    ->select('INDEXID', 'L', 'Te_Code', 'Term')
                    ->groupBy('INDEXID', 'L', 'Te_Code', 'Term')
                    ->get();
                foreach ($forms as $key4 => $value4) {
                    $arr3[] = $value4;
                }
            }
            $arr3_count = count($arr3);
        }

        $countNonAssignedPlacement = PlacementForm::where('overall_approval', 1)->where('Term', Session::get('Term'))->whereNull('assigned_to_course')->get()->count();

        // query regular enrolment forms which are unassigned to a course
        $all_unassigned_enrolment_form = Preenrolment::select('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at', 'eform_submit_count')
            ->groupBy('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at', 'eform_submit_count')
            ->where('Term', Session::get('Term'))
            ->where('overall_approval', 1)
            ->whereNull('updated_by_admin')
            ->get()->count();

        $merge = [];

        if ($request->session()->has('Term')) {
            $termSet = Term::orderBy('Term_Code', 'desc')->where('Term_Code', $request->session()->get('Term'))->first();

            $arrIndexEnrolment = [];
            $approvedEnrolmentForms = Preenrolment::select('INDEXID','L')
                ->where('Term', $request->session()->get('Term'))
                ->where('overall_approval', 1)
                ->whereNotNull('modified_by')
                ->groupBy('INDEXID','L')
                ->get();

            foreach ($approvedEnrolmentForms as $key => $value) {
                $arrIndexEnrolment[] = $value->INDEXID.'-'.$value->L;
            }

            $arrIndexPlacement = [];
            $approvedPlacementForms = PlacementForm::select('INDEXID','L')
                ->where('Term', $request->session()->get('Term'))
                ->where('overall_approval', 1)
                ->whereNotNull('modified_by')
                ->groupBy('INDEXID','L')
                ->get();

            foreach ($approvedPlacementForms as $keyP => $valueP) {
                $arrIndexPlacement[] = $valueP->INDEXID.'-'.$valueP->L;
            }

            $arrIndexPASH = [];
            $qryPASH = Repo::withTrashed()->select('INDEXID','L')
                ->where('Term', $request->session()->get('Term'))
                ->groupBy('INDEXID','L')
                ->get();

            foreach ($qryPASH as $key1 => $value1) {
                $arrIndexPASH[] = $value1->INDEXID.'-'.$value1->L;
            }

            $diffEnrolPASH = array_diff($arrIndexEnrolment, $arrIndexPASH);
            $diffPlacementPASH = array_diff($arrIndexPlacement, $arrIndexPASH);

            $merge = array_merge($diffPlacementPASH, $diffEnrolPASH);
        }

        $term_for_timer = \App\Helpers\GlobalFunction::instance()->currentEnrolTermObject();

        return view('admin.index', compact('all_unassigned_enrolment_form', 'countNonAssignedPlacement', 'arr3_count', 'terms', 'cancelled_convocations', 'new_user_count', 'enrolment_forms', 'placement_forms', 'selfpay_enrolment_forms', 'selfpay_placement_forms', 'selfpay_enrolment_forms_validated', 'selfpay_enrolment_forms_pending', 'selfpay_enrolment_forms_disapproved', 'selfpay_enrolment_forms_waiting', 'selfpay_placement_forms_validated', 'selfpay_placement_forms_pending', 'selfpay_placement_forms_disapproved', 'selfpay_placement_forms_waiting', 'merge', 'term_for_timer'));
    }


    public function importUser()
    {
        return view('admin.import-user');
    }

    public function importExistingUser()
    {
        return view('admin.import-existing-user');
    }

    public function handleImportUser(Request $request, UserImport $userImport)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator);
        }

        if ($request->hasFile('file')) {
            # code...
            $file = $request->file('file');
            $csvData = file_get_contents($file);

            // check utf8 BOM and remove BOM characters
            $bom = pack("CCC", 0xef, 0xbb, 0xbf);
            if (0 === strncmp($csvData, $bom, 3)) {
                echo "BOM detected - file is UTF-8\n";
                $csvData = substr($csvData, 3);
            }

            $rows = array_map("str_getcsv", explode("\n", $csvData));
            $header = array_shift($rows);
            // dd($rows);
            if (!$userImport->checkImportData($rows, $header)) {
                $request->session()->flash('error_rows', $userImport->getErrorRows());
                // $request->session()->flash('error_row_id', $userImport->getErrorRowId());
                // $request->session()->flash('valid_row_id', $userImport->getValidRowId());
                Session::flash('interdire-msg', 'Error in data. Correct and re-upload');
                return redirect()->back();
            }

            $userImport->createUsers($header, $rows);

            session()->flash('success', 'Users imported');
            return redirect()->back();
        } else
            return 'no file';
    }

    public function handleImportExistingUser(Request $request, ExistingUserImport $userImport)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator);
        }

        if ($request->hasFile('file')) {
            # code...
            $file = $request->file('file');
            $csvData = file_get_contents($file);

            // check utf8 BOM and remove BOM characters
            $bom = pack("CCC", 0xef, 0xbb, 0xbf);
            if (0 === strncmp($csvData, $bom, 3)) {
                echo "BOM detected - file is UTF-8\n";
                $csvData = substr($csvData, 3);
            }

            $rows = array_map("str_getcsv", explode("\n", $csvData));
            $header = array_shift($rows);
            // dd($userImport->checkImportData($rows, $header));
            if (!$userImport->checkImportData($rows, $header)) {
                $request->session()->flash('error_rows', $userImport->getErrorRows());
                // $request->session()->flash('error_row_id', $userImport->getErrorRowId());
                // $request->session()->flash('valid_row_id', $userImport->getValidRowId());
                Session::flash('interdire-msg', 'Error in data. Correct and re-upload');
                return redirect()->back();
            }

            $userImport->createUsers($header, $rows);

            session()->flash('success', 'Existing Users imported');
            return redirect()->back();
        } else
            return 'no file';
    }
}
