<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\AttendanceRemarks;
use App\Classroom;
use App\ModifiedForms;
use App\NewUser;
use App\PlacementForm;
use App\Preenrolment;
use App\Repo;
use App\Teachers;
use App\Term;
use App\Torgan;
use App\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TeachersController extends Controller
{
    public function teacherDashboard()
    {
        
        $terms = Term::orderBy('Term_Code', 'desc')->get();       
        $assigned_classes = Classroom::where('Tch_ID', Auth::user()->teachers->Tch_ID)
            ->where('Te_Term', Session::get('Term'))
            ->get();
        $all_classes = Classroom::where('L', Auth::user()->teachers->Tch_L)
            ->where('Tch_ID', '!=', 'TBD')
            ->where('Te_Term', Session::get('Term'))
            ->get();
        
        return view('teachers.teacher_dashboard',compact('terms', 'assigned_classes', 'all_classes'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $languages = DB::table('languages')->pluck("name","code")->all();

        $teachers = new Teachers;
        // $currentQueries = \Request::query();
        $queries = [];

        $columns = [
            'Tch_L', 
        ];

        
        foreach ($columns as $column) {
            if (\Request::has($column)) {
                $teachers = $teachers->where($column, \Request::input($column) );
                $queries[$column] = \Request::input($column);
            }

        } 

                if (\Request::has('search')) {
                    $name = \Request::input('search');
                    $teachers = $teachers->with('users')
                        ->whereHas('users', function($q) use ( $name) {
                            return $q->where('name', 'LIKE', '%' . $name . '%')->orWhere('email', 'LIKE', '%' . $name . '%');
                        });
                    $queries['search'] = \Request::input('search');
            } 

        $teachers = $teachers->orderBy('In_Out', 'desc')->orderBy('Tch_Lastname', 'asc')->get();


        return view('teachers.index')->withTeachers($teachers)->withLanguages($languages);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Teachers  $teachers
     * @return \Illuminate\Http\Response
     */
    public function show(Teachers $teachers)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Teachers  $teachers
     * @return \Illuminate\Http\Response
     */
    public function edit(Teachers $teachers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Teachers  $teachers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Teachers $teachers)
    {
        
    }

    public function ajaxTeacherUpdate(Request $request)
    {
        $teacher = Teachers::where('Tch_ID', $request->Tch_ID)->first();

        $input = $request->all();
        $input = array_filter($input, 'strlen');

        $teacher->fill($input)->save(); 


        $data = $input;
        return response()->json($data);
    }

    public function teacherViewClassrooms()
    {
        $assigned_classes = Classroom::where('Tch_ID', Auth::user()->teachers->Tch_ID)
            ->where('Te_Term', Session::get('Term'))
            ->get();

        return view('teachers.teacher_view_classrooms', compact('assigned_classes'));
    }

    public function teacherViewAllClassrooms()
    {
        $assigned_classes = Classroom::where('L', Auth::user()->teachers->Tch_L)
            ->where('Tch_ID', '!=', 'TBD')
            ->where('Te_Term', Session::get('Term'))
            ->get();

        return view('teachers.teacher_view_all_classrooms', compact('assigned_classes'));
    }

    /**
     * Show the students for specific classrooms
     * @param  Request $request get session parameters
     * @return json           html view
     */
    public function teacherShowStudents(Request $request)
    {
        $form_info = Repo::where('CodeClass', $request->Code)
            ->where('Term', Session::get('Term'))
            ->get();

        $course = Repo::where('CodeClass', $request->Code)
            ->where('Term', Session::get('Term'))
            ->first();

        if (is_null($course)) {
            return view('errors.404_custom');
        }

        if (is_null($form_info)) {
            return view('errors.404_custom');
        }
        
        $data = view('teachers.teacher_show_students', compact('course', 'form_info'))->render();
        return response()->json([$data]);
    }

    public function ajaxShowOverallAttendance(Request $request)
    {
        if ($request->ajax()) {
            $qry = Attendance::where('pash_id', $request->id)->get();

            if ($qry->isEmpty()) {
                $sumP_count = 0;
                $sumE_count = 0;
                $sumA_count = 0;

                $data = [$sumP_count,$sumE_count,$sumA_count];
                return response()->json($data); 
            }

            $arr = [];
            foreach ($qry as $key => $value) {
                $arr = $value;
            }
            
            $arr2 = $arr->getAttributes();
            
            $sumP = [];
            $sumE = [];
            $sumA = [];
            foreach ($arr2 as $k => $v) {
                if($v == 'P'){
                    $sumP[] = 'P';  
                }
                if($v == 'E'){
                    $sumE[] = 'E';  
                }
                if($v == 'A'){
                    $sumA[] = 'A';  
                }
            }
            
            $sumP_count = count($sumP);
            $sumE_count = count($sumE);
            $sumA_count = count($sumA);

            $data = [$sumP_count,$sumE_count,$sumA_count];
            return response()->json($data);  
        }
    }

    public function ajaxShowIfEnrolledNextTerm(Request $request)
    {
        if ($request->ajax()) {
            
            $indexid = $request->indexid;
            $language = $request->L;
            $next_term = Term::where('Term_Code', Session::get('Term') )->first()->Term_Next;
            
            $enrolled_next_term_regular = Preenrolment::where('INDEXID', $indexid)
                ->where('L', $language)
                ->where('Term', $next_term)
                ->select('Te_Code')
                ->groupBy('Te_Code')
                ->get();

            $arr1 = [];
            foreach ($enrolled_next_term_regular as $value1) {
                $arr1[] = $value1->courses->Description;
            }

            $enrolled_next_term_placement = PlacementForm::where('INDEXID', $indexid)
                // ->where('L', $language)
                ->where('Term', $next_term)
                ->get();

            $arr2 = [];
            foreach ($enrolled_next_term_placement as $value2) {
                $arr2[] = $value2->languages->name;
            }

            if (count($enrolled_next_term_regular) < 1 && count($enrolled_next_term_placement) < 1) {
                $data = 'not enrolled';
                return response()->json($data); 
            } 

            $data = [$arr1, $arr2];
            // $data = [$enrolled_next_term_regular, $enrolled_next_term_placement];
            return response()->json($data);  
            
        }
    }

    /**
     * View to enter end of term results per student
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function teacherEnterResults(Request $request)
    {
        $form_info = Repo::where('CodeClass', $request->Code)
            ->where('Term', Session::get('Term'))
            ->get();

        $course = Repo::where('CodeClass', $request->Code)
            ->where('Term', Session::get('Term'))
            ->first();
        
        if (is_null($course)) {
            return view('errors.404_custom');
        }

        if (is_null($form_info)) {
            return view('errors.404_custom');
        }

        $data = view('teachers.teacher_enter_results', compact('course', 'form_info'))->render();
        return response()->json([$data]);
    }

    public function ajaxSaveResults(Request $request)
    {
        if ($request->ajax()) {
            
            $filtered = (array_filter($request->all()));
            $record = Repo::find($request->id);

            $record->update($filtered);

            $data = $record;

            return response()->json($data); 
        }
    }

    public function teacherAssignCourseView(Request $request)
    {
        if ($request->ajax()) {
            $indexid = $request->indexid;
            $next_term = Term::where('Term_Code', Session::get('Term') )->first()->Term_Next; 
            $language = $request->L;

            $qry_enrolment_details = Preenrolment::withTrashed()
                ->where('INDEXID', $indexid)
                ->where('L', $language)
                ->where('Term', $next_term)
                ->get();

            $modified_forms = [];

            foreach ($qry_enrolment_details as $k => $v) {
                $qry_mod_forms = ModifiedForms::where('INDEXID', $v->INDEXID)
                    ->where('Term', $v->Term)
                    ->where('L', $v->L)
                    ->where('eform_submit_count', $v->eform_submit_count)
                    ->get();

                $modified_forms[] = $qry_mod_forms;
            }    

            $enrolment_details = Preenrolment::where('INDEXID', $indexid)
                ->where('L', $language)
                ->where('Term', $next_term)
                ->select('INDEXID', 'L', 'Term','Te_Code', 'eform_submit_count', 'flexibleBtn','modified_by', 'updatedOn')
                ->groupBy('INDEXID', 'L', 'Term','Te_Code', 'eform_submit_count', 'flexibleBtn','modified_by', 'updatedOn')
                ->get();

            $arr1 = []; 
            foreach ($enrolment_details as $key => $value) {
                $arr1[] = Preenrolment::where('INDEXID', $indexid)
                ->where('L', $language)
                ->where('Term', $next_term)
                ->where('Te_Code', $value->Te_Code)
                ->get()
                ->count();
            }

            $enrolment_schedules = Preenrolment::orderBy('id', 'asc')
                ->where('INDEXID', $indexid)
                ->where('L', $language)
                ->where('Term', $next_term)
                ->get(['schedule_id', 'mgr_email', 'approval', 'approval_hr', 'is_self_pay_form', 'DEPT', 'deleted_at', 'INDEXID', 'Term','Te_Code', 'eform_submit_count', 'form_counter' ]);

            $languages = DB::table('languages')->pluck("name","code")->all();
            $org = Torgan::orderBy('Org Name', 'asc')->get(['Org Name','Org Full Name']);

            $data = view('teachers.teacher_assign_course', compact('arr1','enrolment_details', 'enrolment_schedules', 'languages', 'org', 'modified_forms'))->render();
            return response()->json([$data]);             
        }
    }

    public function teacherCheckScheduleCount(Request $request)
    {
        if ($request->ajax()) {
            $indexid = $request->INDEXID;
            $term = $request->term_id; 
            $language = $request->L;

            $enrolment_details = Preenrolment::where('INDEXID', $indexid)
                ->where('L', $language)
                ->where('Term', $term)            
                ->where('eform_submit_count', $request->eform_submit_count)            
                ->get();

            $data = count($enrolment_details);

            return response()->json($data);
        }
    }

    public function teacherNothingToModify(Request $request)
    {
        if ($request->ajax()) {
            $indexno = $request->qry_indexid; 
            $term = $request->qry_term;
            $tecode = $request->qry_tecode;
            $eform_submit_count = $request->eform_submit_count;

            $enrolment_to_be_copied = Preenrolment::orderBy('id', 'asc')
                ->where('Te_Code', $tecode)
                ->where('INDEXID', $indexno)
                ->where('eform_submit_count', $eform_submit_count)
                ->where('Term', $term)
                ->get();
            
            foreach ($enrolment_to_be_copied as $data) {
                $data->fill(['updated_by_admin' => 1,'modified_by' => Auth::user()->id ])->save();
            }

            $data = $request->all();

            return response()->json($data);
        }
    }

    public function teacherSaveAssignedCourse(Request $request)
    {
        if ($request->ajax()) {
            $indexno = $request->qry_indexid; 
            $term = $request->qry_term;
            $tecode = $request->qry_tecode;
            $eform_submit_count = $request->eform_submit_count;

            if (is_null($request->Te_Code)) {
                $data = 0;
                return response()->json($data);
            }

            $enrolment_to_be_copied = Preenrolment::orderBy('id', 'asc')
                ->where('Te_Code', $tecode)
                ->where('INDEXID', $indexno)
                ->where('eform_submit_count', $eform_submit_count)
                ->where('Term', $term)
                ->get();

            $user_id = User::where('indexno', $indexno)->first(['id']);

            foreach ($enrolment_to_be_copied as $data) {
                $data->fill(['updated_by_admin' => 1,'modified_by' => Auth::user()->id ])->save();

                $arr = $data->attributesToArray();
                $clone_forms = ModifiedForms::create($arr);
            }


            $count_form = $enrolment_to_be_copied->count();
            if ($count_form > 1) {
                $delform = Preenrolment::orderBy('id', 'desc')
                ->where('Te_Code', $tecode)
                ->where('INDEXID', $indexno)
                ->where('eform_submit_count', $eform_submit_count)
                ->where('Term', $term)
                ->first();
                $delform->Code = null;
                $delform->CodeIndexID = null;
                $delform->Te_Code = null;
                $delform->INDEXID = null;
                $delform->Term = null;
                $delform->schedule_id = null;             
                $delform->save();
                $delform->delete();
            }

            $enrolment_to_be_modified = Preenrolment::orderBy('id', 'asc')
                ->where('Te_Code', $tecode)
                ->where('INDEXID', $indexno)
                ->where('eform_submit_count', $eform_submit_count)
                ->where('Term', $term)
                ->get();

            $input = $request->all();
            $input = array_filter($input, 'strlen');
            
            foreach ($enrolment_to_be_modified as $new_data) {
                $new_data->fill($input)->save();    

                $new_data->Code = $new_data->Te_Code.'-'.$new_data->schedule_id.'-'.$new_data->Term;
                $new_data->CodeIndexID = $new_data->Te_Code.'-'.$new_data->schedule_id.'-'.$new_data->Term.'-'.$new_data->INDEXID;
                $new_data->save();
            }

            $data = $request->all();

            return response()->json($data);
        }
    }


    public function teacherSelectWeek($code)
    {
        $term = Term::where('Term_Code', Session::get('Term'))->first();

        $course = Repo::where('CodeClass', $code)
            ->where('Term', Session::get('Term'))
            ->first();

        if (is_null($course)) {
            return view('errors.404_custom');
        }

        if (is_null($term)) {
            return view('errors.404_custom');
        }

        return view('teachers.teacher_select_week', compact('course', 'term'));
        // $data = view('teachers.teacher_manage_attendance', compact('course', 'form_info'))->render();
        // return response()->json([$data]);
    }

    public function teacherWeekTable(Request $request)
    {
        $day_time = Classroom::where('Code', $request->CodeClass)->first();
        $wk = $request->Wk;

        $data = view('teachers.teacher_week_table', compact('day_time', 'wk'))->render();
        return response()->json([$data]);
    }

    public function teacherManageAttendances(Request $request)
    {   
        $form_info = Repo::where('CodeClass', $request->Code)
            ->where('Term', Session::get('Term'))
            ->get();

        $course = Repo::where('CodeClass', $request->Code)
            ->where('Term', Session::get('Term'))
            ->first();

        if (is_null($course)) {
            return view('errors.404_custom');
        }

        $classroom = Classroom::where('Code', $request->Code)->first();
        $day = $request->day;
        $time = $request->time;
        $week = $request->wk;

        // $arr = [];
        // foreach ($form_info as $value) {
        //     $remark = Attendance::where('pash_id', $value->id)->whereHas('attendanceRemarks', function($q){
        //             $q->whereNotNull('remarks')->orderBy('created_at', 'desc');
        //     })
        //     ->get();
        //     foreach ($remark as $key => $valueR) {
        //         $arr[] = $valueR->attendanceRemarks[$key]['id'];
                
        //     }
        // }
        // dd($arr);
        return view('teachers.teacher_manage_attendance', compact('course', 'form_info', 'classroom','day','time','week'));
        // $data = view('teachers.teacher_manage_attendance', compact('course', 'form_info'))->render();
        // return response()->json([$data]);
    }

    public function ajaxGetRemark(Request $request)
    {
        $attendance_id_check = Attendance::where('pash_id', $request->id)->get();
        $count_attendance_id = $attendance_id_check->count();

        if ($count_attendance_id > 0){
            $attendance_id = Attendance::where('pash_id', $request->id)->first()->id;
            
            $remark = AttendanceRemarks::where('attendance_id', $attendance_id)
                ->where('wk_id', $request->wk)
                ->orderBy('created_at', 'desc')
                ->first();

            if (!empty($remark)) {
                $data = $remark->remarks;
                return response()->json($data);
            }
            
            $data = '';
            return response()->json($data);  
        }

        $data = '';
        return response()->json($data);        

    }

    public function ajaxTeacherAttendanceUpdate(Request $request)
    {
        $ids = $request->ids;
        $week = $request->wk;
        
        $data_details = [];
        $student_to_update = Repo::whereIn('id',explode(",",$ids))->get();

        $attendance_status = explode(",", $request->attendanceStatus);
        $countAttendanceStatus = count($attendance_status);

        $remarks = explode(",", $request->remarks);

        for ($i=0; $i < $countAttendanceStatus; $i++) { 
            $data_details[] = $student_to_update[$i]['id'];

            $data_update = Attendance::where('pash_id', $student_to_update[$i]['id'])->get();

            if (count($data_update) > 0 ) {

                // update record
                $record_update = Attendance::where('pash_id', $student_to_update[$i]['id']);
                $record_update->update([
                    $request->wk => $attendance_status[$i],
                ]); 

                $query = Attendance::where('pash_id', $student_to_update[$i]['id'])->first();
                if (!empty($remarks[$i])) {

                    $attendance_remark = new AttendanceRemarks;
                    $attendance_remark->attendance_id = $query->id;
                    $attendance_remark->wk_id = $week;
                    $attendance_remark->remarks = $remarks[$i];
                    $attendance_remark->save();
                }

                
            } 
                else {

                // insert to attendance table
                $record = new Attendance;
                $record->pash_id = $student_to_update[$i]['id'];
                $record->$week = $attendance_status[$i];
                $record->save();

                if (!empty($remarks[$i])) {
                    $attendance_remark = new AttendanceRemarks;
                    $attendance_remark->attendance_id = $record->id;
                    $attendance_remark->wk_id = $week;
                    $attendance_remark->remarks = $remarks[$i];
                    $attendance_remark->save();
                }
            }
        }

        $data = 'success';
        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Teachers  $teachers
     * @return \Illuminate\Http\Response
     */
    public function destroy(Teachers $teachers)
    {
        //
    }
}
