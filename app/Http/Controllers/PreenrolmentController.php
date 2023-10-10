<?php

namespace App\Http\Controllers;

use App\ContractFile;
use App\Traits\VerifyAndNotAssignTwoRecords;
use App\File;
use App\FocalPoints;
use App\Mail\MailaboutCancel;
use App\Mail\SendMailable;
use App\Mail\SendReminderEmailHR;
use App\Mail\cancelConvocation;
use App\ModifiedForms;
use App\PlacementForm;
use App\Preenrolment;
use App\Repo;
use App\Term;
use App\Torgan;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Session;

class PreenrolmentController extends Controller
{
    use VerifyAndNotAssignTwoRecords;

    public function studentEditEnrolmentFormView($term, $indexid, $tecode)
    {
        // get id(s) of form
        $enrolment = Preenrolment::where('Term', $term)->where('INDEXID', $indexid)->where('Te_Code', $tecode)->orderBy('id', 'asc');
        // check if form exists
        $enrolment_first = Preenrolment::where('Term', $term)->where('INDEXID', $indexid)->where('Te_Code', $tecode)->orderBy('id', 'asc')->first();
        if (empty($enrolment_first)) {
            return view('errors.401_custom');
        }

        $enrolment_details = $enrolment->get();
        $enrolment_id = $enrolment->select('id')->get();
        $enrolment_id_array = [];
        foreach ($enrolment_id as $value) {
            $enrolment_id_array[] = $value->id;
        }
        $languages = DB::table('languages')->pluck("name", "code")->all();
        // create $terms object variable to apply conditions for summer term
        $terms = (object)array("Term_Code" => $enrolment_first->Term);

        return view('preenrolment.student-edit-enrolment-form-view', compact('enrolment_details', 'languages', 'enrolment_id_array', 'terms'));
    }

    public function studentUpdateEnrolmentForm(Request $request)
    {
        $this->validate($request, [
            'term_id' => 'required',
            'L' => 'required',
            'course_id' => 'required',
            'schedule_id' => 'required',
            'indexno' => 'required',
            'enrolment_id' => 'required',
            'flexibleDay' => 'required',
            'flexibleTime' => 'required',
            'flexibleFormat' => 'required',
        ]);

        $codeIndexId = $request->course_id . '-' . $request->schedule_id . '-' . $request->term_id . '-' . $request->indexno;
        $request->merge(['CodeIndexID' => $codeIndexId]);
        $this->validate($request, array(
            'CodeIndexID' => Rule::unique('tblLTP_Enrolment')->where(function ($query) use ($request) {
                $uniqueCodex = $request->CodeIndexID;
                $query->where('CodeIndexID', $uniqueCodex)
                    ->where('deleted_at', NULL);
            })
        ));

        $flexibleBtn = 1;
        if (!isset($request->flexibleBtn)) {
            $flexibleBtn = NULL;
        }

        $enrolment_forms_to_be_modified = Preenrolment::whereIn('id', $request->enrolment_id)->orderBy('id', 'asc')->get();

        foreach ($enrolment_forms_to_be_modified as $data) {
            $arr = $data->attributesToArray();
            $record = ModifiedForms::create($arr);
            // ModifiedForms::where('auto_id', $record->id)->update(['modified_by' => Auth::id()]);

            $data->update([
                'L' => $request->L,
                'Te_Code' => $request->course_id,
                'Term' => $request->term_id,
                'schedule_id' => $request->schedule_id,
                'CodeIndexID' => $request->course_id . '-' . $request->schedule_id . '-' . $request->term_id . '-' . $request->indexno,
                'Code' => $request->course_id . '-' . $request->schedule_id . '-' . $request->term_id,
                'flexibleBtn' => $flexibleBtn,
                'flexibleDay' => $request->flexibleDay,
                'flexibleTime' => $request->flexibleTime,
                'flexibleFormat' => $request->flexibleFormat,
                'modified_by' => Auth::id(),
            ]);

            if ($request->regular_enrol_comment != NULL) {
                $data->update(['std_comments' => $request->regular_enrol_comment]);
            }
        }

        if (count($request->enrolment_id) > 1) {
            $delform = Preenrolment::withTrashed()
                ->whereIn('id', $request->enrolment_id)
                ->orderBy('id', 'desc')
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

        return redirect()->route('previous-submitted')->with('success', 'Form successfully modified.');
    }

    public function queryOrphanFormsToAssign(Request $request)
    {
        if (Session::has('Term')) {
            $languages = DB::table('languages')->pluck("name", "code")->all();
            $term = Session::get('Term');
            $prev_term = Term::where('Term_Code', $term)->first()->Term_Prev;

            if (\Request::filled('L')) {

                // query total regular enrolment forms (assigned + not assigned)
                $arr3 = Preenrolment::where('Term', Session::get('Term'))
                    ->where('overall_approval', 1)
                    // ->whereNull('updated_by_admin')
                    ->select('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at', 'eform_submit_count', 'updated_by_admin', 'modified_by')
                    ->groupBy('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at', 'eform_submit_count', 'updated_by_admin', 'modified_by')
                    // ->get()
                ;

                // query regular enrolment forms which are unassigned to a course
                $assigned_forms_count = Preenrolment::where('Term', Session::get('Term'))
                    ->where('L', \Request::get('L'))
                    ->where('overall_approval', 1)
                    ->whereNull('updated_by_admin')
                    ->select('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at', 'eform_submit_count', 'updated_by_admin', 'modified_by')
                    ->groupBy('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at', 'eform_submit_count', 'updated_by_admin', 'modified_by')
                    ->get()
                    ->count();
                $queries = [];

                $columns = [
                    'L',
                ];


                foreach ($columns as $column) {
                    if (\Request::filled($column)) {
                        $arr3 = $arr3->where($column, \Request::input($column));
                        $queries[$column] = \Request::input($column);
                    }
                }
                if (Session::has('Term')) {
                    $arr3 = $arr3->where('Term', Session::get('Term'));
                    $queries['Term'] = Session::get('Term');
                }

                // $arr3 = $arr3->paginate(20)->appends($queries);
                $arr3 = $arr3->get();

                return view('preenrolment.query-orphan-forms-to-assign', compact('languages', 'arr3', 'assigned_forms_count'));
            }

            $arr3 = null;
            // $total_enrolment_forms = null;

            return view('preenrolment.query-orphan-forms-to-assign', compact('languages', 'arr3'));
        }
        return redirect('/admin');
    }

    public function queryRegularFormsToAssign(Request $request)
    {
        $languages = DB::table('languages')->pluck("name", "code")->all();
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

            $enrolment_forms = Preenrolment::select('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
                ->groupBy('selfpay_approval', 'INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
                ->where('Term', Session::get('Term'))
                ->where('overall_approval', 1)
                // ->whereNull('updated_by_admin')
                ->get();

            // echo "Total Number of Enrolment Forms in ".$term.": ".count($enrolment_forms);
            // echo "<br>";

            $arr2 = [];
            foreach ($enrolment_forms as $key2 => $value2) {
                $arr2[] = $value2->INDEXID;
            }
            $arr2 = array_unique($arr2);

            // echo "Total Number of People who submitted Re-Enrolment/Enrolment Forms for term ".$term.": ".count($arr2);
            // echo "<br>";

            $students_not_in_class = array_diff($arr2, $arr1); // get all enrolment_forms not included in students_in_class
            $unique_students_not_in_class = array_unique($students_not_in_class);

            // echo "Total Number of People NOT in Class for ".$term.": ".count($unique_students_not_in_class);
            // echo "<br>";

            $arr4 = [];
            foreach ($unique_students_not_in_class as $key4 => $value4) {
                $forms = Preenrolment::where('Term', $term)
                    ->where('INDEXID', $value4)
                    ->whereNull('updated_by_admin')
                    ->select('INDEXID', 'L', 'Te_Code', 'Term', 'updated_by_admin', 'modified_by')
                    ->groupBy('INDEXID', 'L', 'Te_Code', 'Term', 'updated_by_admin', 'modified_by')
                    ->get();
                foreach ($forms as $key5 => $value5) {
                    $arr4[] = $value5;
                }
            }
            $count_not_assigned = count($arr4);

            $arr3 = [];
            foreach ($unique_students_not_in_class as $key3 => $value3) {
                $forms = Preenrolment::where('Term', $term)
                    ->where('INDEXID', $value3)
                    // ->whereNull('updated_by_admin')
                    ->select('INDEXID', 'L', 'Te_Code', 'Term', 'updated_by_admin', 'modified_by')
                    ->groupBy('INDEXID', 'L', 'Te_Code', 'Term', 'updated_by_admin', 'modified_by')
                    ->get();
                foreach ($forms as $key4 => $value4) {
                    $arr3[] = $value4;
                }
            }

            if (\Request::filled('L')) {
                $arr3 = collect($arr3);
                $arr4 = collect($arr4);

                $queries = [];

                $columns = [
                    'L',
                ];


                foreach ($columns as $column) {
                    if (\Request::filled($column)) {
                        $arr3 = $arr3->where($column, \Request::input($column));
                        $queries[$column] = \Request::input($column);
                    }
                }
                if (Session::has('Term')) {
                    $arr3 = $arr3->where('Term', Session::get('Term'));
                    $queries['Term'] = Session::get('Term');
                }

                foreach ($columns as $column) {
                    if (\Request::filled($column)) {
                        $arr4 = $arr4->where($column, \Request::input($column));
                        $queries[$column] = \Request::input($column);
                    }
                }
                if (Session::has('Term')) {
                    $arr4 = $arr4->where('Term', Session::get('Term'));
                    $queries['Term'] = Session::get('Term');
                }

                $count_not_assigned = count($arr4);

                return view('preenrolment.query-regular-forms-to-assign', compact('languages', 'arr3', 'count_not_assigned'));
            }

            return view('preenrolment.query-regular-forms-to-assign', compact('languages', 'arr3', 'count_not_assigned'));
        }

        return view('preenrolment.query-regular-forms-to-assign', compact('languages'));
    }

    /**
     * Admin assign course view in non-assigned enrolment table
     */
    public function adminAssignCourseView(Request $request)
    {
        if ($request->ajax()) {
            $indexid = $request->indexid;
            $next_term = Term::where('Term_Code', Session::get('Term'))->first()->Term_Code;
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
                ->where('Te_Code', $request->Te_Code)
                ->select('INDEXID', 'L', 'Term', 'Te_Code', 'eform_submit_count', 'flexibleBtn', 'flexibleDay', 'flexibleTime', 'flexibleFormat', 'modified_by', 'updated_by_admin', 'admin_eform_comment', 'std_comments', 'teacher_comments', 'updatedOn')
                ->groupBy('INDEXID', 'L', 'Term', 'Te_Code', 'eform_submit_count', 'flexibleBtn', 'flexibleDay', 'flexibleTime', 'flexibleFormat', 'modified_by', 'updated_by_admin', 'admin_eform_comment', 'std_comments', 'teacher_comments', 'updatedOn')
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
                ->get(['schedule_id', 'mgr_email', 'approval', 'approval_hr', 'is_self_pay_form', 'DEPT', 'deleted_at', 'INDEXID', 'Term', 'Te_Code', 'eform_submit_count', 'form_counter']);

            $languages = DB::table('languages')->pluck("name", "code")->all();
            $org = Torgan::orderBy('Org name', 'asc')->get(['Org name', 'Org Full Name']);
            $historical_data = Repo::orderBy('Term', 'desc')->where('INDEXID', $indexid)->first();
            $history = Repo::orderBy('Term', 'desc')->where('INDEXID', $indexid)->get();

            // query placement table if student placement enrolment data exists or not for the previous term
            $selectedTerm = $next_term;
            $lastDigit = substr($selectedTerm, -1);

            if ($lastDigit == 9) {
                $prev_term = $selectedTerm - 1;
                $placement_flag = PlacementForm::where('Term', $prev_term)->whereNull('assigned_to_course')->where('L', $language)->where('INDEXID', $indexid)->first();
            } else {
                $placement_flag = null;
            }

            $last_placement_test = PlacementForm::orderBy('Term', 'desc')->where('INDEXID', $indexid)->first();

            $data = view('preenrolment.admin_assign_course', compact('arr1', 'enrolment_details', 'enrolment_schedules', 'languages', 'org', 'modified_forms', 'history', 'historical_data', 'placement_flag', 'last_placement_test'))->render();
            return response()->json([$data]);
        }
    }

    /**
     * Admin assign course view in Admin ManageUser view
     */
    public function adminManageUserAssignCourseView(Request $request)
    {
        if ($request->ajax()) {
            $indexid = $request->indexid;
            $next_term = Term::where('Term_Code', $request->Term)->first()->Term_Code;
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
                ->where('Te_Code', $request->Te_Code)
                ->select('INDEXID', 'L', 'Term', 'Te_Code', 'eform_submit_count', 'flexibleBtn', 'flexibleDay', 'flexibleTime', 'flexibleFormat', 'modified_by', 'updated_by_admin', 'admin_eform_comment', 'std_comments', 'updatedOn')
                ->groupBy('INDEXID', 'L', 'Term', 'Te_Code', 'eform_submit_count', 'flexibleBtn', 'flexibleDay', 'flexibleTime', 'flexibleFormat', 'modified_by', 'updated_by_admin', 'admin_eform_comment', 'std_comments', 'updatedOn')
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
                ->get(['schedule_id', 'mgr_email', 'approval', 'approval_hr', 'is_self_pay_form', 'DEPT', 'deleted_at', 'INDEXID', 'Term', 'Te_Code', 'eform_submit_count', 'form_counter']);

            $languages = DB::table('languages')->pluck("name", "code")->all();
            $org = Torgan::orderBy('Org name', 'asc')->get(['Org name', 'Org Full Name']);
            $historical_data = Repo::orderBy('Term', 'desc')->where('INDEXID', $indexid)->first();
            $history = Repo::orderBy('Term', 'desc')->where('INDEXID', $indexid)->get();

            // query placement table if student placement enrolment data exists or not for the previous term
            $selectedTerm = $next_term;
            $lastDigit = substr($selectedTerm, -1);

            if ($lastDigit == 9) {
                $prev_term = $selectedTerm - 1;
                $placement_flag = PlacementForm::where('Term', $prev_term)->whereNull('assigned_to_course')->where('L', $language)->where('INDEXID', $indexid)->first();
            } else {
                $placement_flag = null;
            }

            $last_placement_test = PlacementForm::orderBy('Term', 'desc')->where('INDEXID', $indexid)->first();

            $data = view('preenrolment.admin_assign_course', compact('arr1', 'enrolment_details', 'enrolment_schedules', 'languages', 'org', 'modified_forms', 'history', 'historical_data', 'placement_flag', 'last_placement_test'))->render();
            return response()->json([$data]);
        }
    }

    public function adminCheckScheduleCount(Request $request)
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

    public function adminNothingToModify(Request $request)
    {
        if ($request->ajax()) {
            $indexno = $request->qry_indexid;
            $term = $request->qry_term;
            $tecode = $request->qry_tecode;
            $eform_submit_count = $request->eform_submit_count;
            $admin_eform_comment = $request->admin_eform_comment;

            $enrolment_to_be_copied = Preenrolment::orderBy('id', 'asc')
                ->where('Te_Code', $tecode)
                ->where('INDEXID', $indexno)
                ->where('eform_submit_count', $eform_submit_count)
                ->where('Term', $term)
                ->get();

            $input_1 = ['admin_eform_comment' => $admin_eform_comment, 'updated_by_admin' => 1, 'modified_by' => Auth::user()->id];
            $input_1 = array_filter($input_1, 'strlen');

            foreach ($enrolment_to_be_copied as $data) {
                $data->fill($input_1)->save();
            }

            $data = $request->all();

            return response()->json($data);
        }
    }

    public function adminVerifyAndNotAssign(Request $request)
    {
        if ($request->ajax()) {
            $indexno = $request->qry_indexid;
            $term = $request->qry_term;
            $tecode = $request->qry_tecode;
            $eform_submit_count = $request->eform_submit_count;
            $admin_eform_comment = $request->admin_eform_comment;
            $assign_modal = 1;

            $enrolment_to_be_copied = Preenrolment::orderBy('id', 'asc')
                ->where('Te_Code', $tecode)
                ->where('INDEXID', $indexno)
                ->where('eform_submit_count', $eform_submit_count)
                ->where('Term', $term)
                ->get();

            if ($enrolment_to_be_copied->count() > 1) {
                $updated_enrolment_record = $this->verifyAndNotAssignRecords($assign_modal, $enrolment_to_be_copied, $admin_eform_comment);
                $data = $updated_enrolment_record;
                return response()->json($data);
            }

            $input_1 = ['admin_eform_comment' => $admin_eform_comment, 'updated_by_admin' => 0, 'modified_by' => Auth::user()->id];
            $input_1 = array_filter($input_1, 'strlen');

            foreach ($enrolment_to_be_copied as $data) {
                $data->fill($input_1)->save();
            }

            $data = $request->all();

            return response()->json($data);
        }
    }

    public function adminSaveAssignedCourse(Request $request)
    {
        if ($request->ajax()) {
            $indexno = $request->qry_indexid;
            $term = $request->qry_term;
            $tecode = $request->qry_tecode;
            $eform_submit_count = $request->eform_submit_count;
            $admin_eform_comment = $request->admin_eform_comment;

            if (is_null($request->Te_Code)) {
                $data = 0;
                return response()->json($data);
            }

            // check if assigned course was already assigned
            $assignedNewCourse = $request->Te_Code . '-' . $request->schedule_id . '-' . $term . '-' . $indexno;
            $checkNewCourseExists = Preenrolment::where('CodeIndexID', $assignedNewCourse)
                ->where('updated_by_admin', '1')
                ->first();
            if ($checkNewCourseExists) {
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

            $input_1 = ['admin_eform_comment' => $admin_eform_comment, 'updated_by_admin' => 1, 'modified_by' => Auth::user()->id];
            $input_1 = array_filter($input_1, 'strlen');

            foreach ($enrolment_to_be_copied as $data) {
                $arr = $data->attributesToArray();
                $clone_forms = ModifiedForms::create($arr);

                $data->fill($input_1)->save();
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

                $new_data->Code = $new_data->Te_Code . '-' . $new_data->schedule_id . '-' . $new_data->Term;
                $new_data->CodeIndexID = $new_data->Te_Code . '-' . $new_data->schedule_id . '-' . $new_data->Term . '-' . $new_data->INDEXID;
                $new_data->save();
            }

            $data = $input;

            return response()->json($data);
        }
    }

    public function ajaxStdComments(Request $request)
    {
        if ($request->ajax()) {
            $student_enrolments = Preenrolment::withTrashed()
                ->where('INDEXID', $request->indexno)
                ->where('Term', $request->term)
                ->where('Te_Code', $request->tecode)
                ->where('eform_submit_count', $request->eform_submit_count)
                ->groupBy(['Te_Code', 'Term', 'INDEXID', 'DEPT', 'is_self_pay_form', 'continue_bool', 'form_counter', 'deleted_at', 'eform_submit_count', 'cancelled_by_student', 'created_at', 'L', 'attachment_id', 'attachment_pay', 'modified_by', 'updated_by_admin', 'std_comments'])
                ->get(['Te_Code', 'Term', 'INDEXID', 'DEPT', 'is_self_pay_form', 'continue_bool', 'form_counter', 'deleted_at', 'eform_submit_count', 'cancelled_by_student', 'created_at', 'L', 'attachment_id', 'attachment_pay', 'modified_by', 'updated_by_admin', 'std_comments']);

            $data = $student_enrolments->first()->std_comments;
            return response()->json($data);
        }
    }

    /**
     * Send reminder emails to manager and HR focalpoints.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendReminderEmails()
    {
        //get current year and date
        $now_date = Carbon::now();
        $now_year = Carbon::now()->year;
        // get the correct enrolment term code
        $enrolment_term = Term::whereYear('Enrol_Date_Begin', $now_year)
            ->orderBy('Term_Code', 'desc')
            ->where('Enrol_Date_Begin', '<=', $now_date)
            ->where('Approval_Date_Limit_HR', '>=', $now_date)
            ->min('Term_Code');
        if (empty($enrolment_term)) {
            Log::info("Auto-sending of reminder emails failed. Term is null. No Emails sent.");
            echo "Term is null. No Emails sent.";
            return exit();
        }

        $enrolment_term_object = Term::findOrFail($enrolment_term);

        $remind_mgr_param = Term::where('Term_Code', $enrolment_term)->value('Remind_Mgr_After'); // get int value after how many days reminder email should be sent

        $arrRecipient = [];
        $enrolments_no_mgr_approval = Preenrolment::where('Term', $enrolment_term)->whereNull('is_self_pay_form')->whereNull('approval')->select('INDEXID', 'Te_Code', 'form_counter', 'mgr_email', 'created_at')->groupBy('INDEXID', 'Te_Code', 'form_counter', 'mgr_email', 'created_at')->get();

        if ($enrolments_no_mgr_approval->isEmpty()) {
            Log::info("No email addresses to pick up. No Emails sent.");
            echo $enrolment_term;
            echo  $enrolments_no_mgr_approval;
            // return exit();
        }
        foreach ($enrolments_no_mgr_approval as  $valueMgrEmails) {
            // if submission date < (Enrol_Date_End minus x days) then send reminder emails after x days of submission
            if ($valueMgrEmails->created_at < Carbon::parse($enrolment_term_object->Enrol_Date_End)->subDays($remind_mgr_param)) {
                if ($now_date >= Carbon::parse($valueMgrEmails->created_at)->addDays($remind_mgr_param)) {

                    $arrRecipient[] = $valueMgrEmails->mgr_email;
                    $recipient = $valueMgrEmails->mgr_email;

                    $staff = User::where('indexno', $valueMgrEmails->INDEXID)->first();
                    $input_course = Preenrolment::orderBy('Term', 'desc')->orderBy('id', 'desc')->where('INDEXID', $valueMgrEmails->INDEXID)->where('Term', $enrolment_term)->first();
                    $input_schedules = Preenrolment::orderBy('Term', 'desc')
                        ->where('INDEXID', $valueMgrEmails->INDEXID)
                        ->where('Term', $enrolment_term)
                        ->where('Te_Code', $valueMgrEmails->Te_Code)
                        ->where('form_counter', $valueMgrEmails->form_counter)
                        ->get();
                    Mail::to($recipient)->send(new SendMailable($input_course, $input_schedules, $staff));
                    // Mail::raw("This is a test automated message", function($message) use ($recipient){
                    //     $message->from('clm_language@unog.ch', 'CLM Language');
                    //     $message->to('allyson.frias@un.org')->subject('MGR - This is a test automated message');
                    // });
                    echo 'email sent to: ' . $recipient;
                    echo '<br>';
                    echo '<br>';
                }
            }

            // else if $now_date = Approval Date Limit then do send to all enrolment forms without manager approval    
            //     if ($now_date->toDateString() == Carbon::parse($enrolment_term_object->Approval_Date_Limit)->toDateString()) {
            //         echo "send to all";
            //         $recipient = $valueMgrEmails->mgr_email;

            //         $staff = User::where('indexno', $valueMgrEmails->INDEXID)->first();
            //         $input_course = Preenrolment::orderBy('Term', 'desc')->orderBy('id', 'desc')->where('INDEXID', $valueMgrEmails->INDEXID)->where('Term', $enrolment_term)->first();
            //         $input_schedules = Preenrolment::orderBy('Term', 'desc')
            //                             ->where('INDEXID', $valueMgrEmails->INDEXID)
            //                             ->where('Term', $enrolment_term)
            //                             ->where('Te_Code', $valueMgrEmails->Te_Code)
            //                             ->where('form_counter', $valueMgrEmails->form_counter)
            //                             ->get();
            //         Mail::to($recipient)->send(new SendMailable($input_course, $input_schedules, $staff));
            //     }
        } // end of foreach loop

        $remind_hr_param = Term::where('Term_Code', $enrolment_term)->value('Remind_HR_After');

        $arrDept = [];
        $arrHrEmails = [];
        $enrolments_no_hr_approval = Preenrolment::where('Term', $enrolment_term)->whereNull('is_self_pay_form')->whereNull('approval_hr')->where('approval', '1')->whereNotIn('DEPT', ['UNOG', 'JIU', 'DDA', 'OIOS', 'DPKO'])->select('INDEXID', 'Te_Code', 'form_counter', 'mgr_email', 'DEPT', 'UpdatedOn')->groupBy('INDEXID', 'Te_Code', 'form_counter', 'mgr_email', 'DEPT', 'UpdatedOn')->get();

        foreach ($enrolments_no_hr_approval as $valueDept) {
            if ($valueDept->UpdatedOn < Carbon::parse($enrolment_term_object->Enrol_Date_End)->subDays($remind_hr_param)) {
                if ($now_date >= Carbon::parse($valueDept->UpdatedOn)->addDays($remind_hr_param)) {

                    $arrDept[] = $valueDept->DEPT;
                    $torgan = Torgan::where('Org name', $valueDept->DEPT)->first();
                    $learning_partner = $torgan->has_learning_partner;

                    if ($learning_partner == '1') {
                        $query_hr_email = FocalPoints::where('org_id', $torgan->OrgCode)->get(['email']);
                        $fp_email = $query_hr_email->map(function ($val, $key) {
                            return $val->email;
                        });
                        $fp_email_arr = $fp_email->toArray();
                        $arrHrEmails[] = $fp_email_arr;

                        $formItems = Preenrolment::orderBy('Term', 'desc')
                            ->where('INDEXID', $valueDept->INDEXID)
                            ->where('Term', $enrolment_term)
                            ->where('Te_Code', $valueDept->Te_Code)
                            ->where('form_counter', $valueDept->form_counter)
                            ->get();
                        $formfirst = Preenrolment::orderBy('Term', 'desc')
                            ->where('INDEXID', $valueDept->INDEXID)
                            ->where('Term', $enrolment_term)
                            ->where('Te_Code', $valueDept->Te_Code)
                            ->where('form_counter', $valueDept->form_counter)
                            ->first();
                        $staff_name = $formfirst->users->name;
                        $mgr_email = $formfirst->mgr_email;

                        // get term values
                        $term = $enrolment_term;
                        // get term values and convert to strings
                        $term_en = Term::where('Term_Code', $term)->first()->Term_Name;
                        $term_fr = Term::where('Term_Code', $term)->first()->Term_Name_Fr;

                        $term_season_en = Term::where('Term_Code', $term)->first()->Comments;
                        $term_season_fr = Term::where('Term_Code', $term)->first()->Comments_fr;

                        $term_date_time = Term::where('Term_Code', $term)->first()->Term_Begin;
                        $term_year = new Carbon($term_date_time);
                        $term_year = $term_year->year;

                        $input_course = $formfirst;
                        // Mail::to($fp_email_arr);
                        Mail::to($fp_email_arr)->send(new SendReminderEmailHR($formItems, $input_course, $staff_name, $mgr_email, $term_en, $term_fr, $term_season_en, $term_season_fr, $term_year));
                    }
                }
            }

            if ($now_date->toDateString() == Carbon::parse($enrolment_term_object->Approval_Date_Limit_HR)->toDateString()) {
                echo "send to all HR Partners";
                $torgan = Torgan::where('Org name', $valueDept->DEPT)->first();
                $learning_partner = $torgan->has_learning_partner;

                if ($learning_partner == '1') {
                    $query_hr_email = FocalPoints::where('org_id', $torgan->OrgCode)->get(['email']);
                    $fp_email = $query_hr_email->map(function ($val, $key) {
                        return $val->email;
                    });
                    $fp_email_arr = $fp_email->toArray();
                    $arrHrEmails[] = $fp_email_arr;

                    $formItems = Preenrolment::orderBy('Term', 'desc')
                        ->where('INDEXID', $valueDept->INDEXID)
                        ->where('Term', $enrolment_term)
                        ->where('Te_Code', $valueDept->Te_Code)
                        ->where('form_counter', $valueDept->form_counter)
                        ->get();
                    $formfirst = Preenrolment::orderBy('Term', 'desc')
                        ->where('INDEXID', $valueDept->INDEXID)
                        ->where('Term', $enrolment_term)
                        ->where('Te_Code', $valueDept->Te_Code)
                        ->where('form_counter', $valueDept->form_counter)
                        ->first();
                    $staff_name = $formfirst->users->name;
                    $mgr_email = $formfirst->mgr_email;

                    // get term values
                    $term = $enrolment_term;
                    // get term values and convert to strings
                    $term_en = Term::where('Term_Code', $term)->first()->Term_Name;
                    $term_fr = Term::where('Term_Code', $term)->first()->Term_Name_Fr;

                    $term_season_en = Term::where('Term_Code', $term)->first()->Comments;
                    $term_season_fr = Term::where('Term_Code', $term)->first()->Comments_fr;

                    $term_date_time = Term::where('Term_Code', $term)->first()->Term_Begin;
                    $term_year = new Carbon($term_date_time);
                    $term_year = $term_year->year;

                    $input_course = $formfirst;
                    // Mail::to($fp_email_arr);
                    Mail::to($fp_email_arr)->send(new SendReminderEmailHR($formItems, $input_course, $staff_name, $mgr_email, $term_en, $term_fr, $term_season_en, $term_season_fr, $term_year));
                }
            }
        } // end of foreach loop

        // dd($arrRecipient, $enrolments_no_mgr_approval, $arrHrEmails,$formfirst);
        return 'reminder enrolment emails sent';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $languages = DB::table('languages')->pluck("name", "code")->all();
        $org = Torgan::orderBy('Org name', 'asc')->get(['Org name', 'Org Full Name']);
        $terms = Term::orderBy('Term_Code', 'desc')->get();
        $selectedTerm = Term::orderBy('Term_Code', 'desc')->where('Term_Code', Session::get('Term'))->first();

        if (!Session::has('Term')) {
            $enrolment_forms = null;
            return view('preenrolment.index', compact('enrolment_forms', 'languages', 'org', 'terms'));
        }

        $enrolment_forms = new Preenrolment;
        // $currentQueries = \Request::query();
        $queries = [];

        $columns = [
            'L', 'DEPT', 'Te_Code', 'is_self_pay_form', 'overall_approval',
        ];


        foreach ($columns as $column) {
            if (\Request::filled($column)) {
                $enrolment_forms = $enrolment_forms->where($column, \Request::input($column));
                $queries[$column] = \Request::input($column);
            }
        }
        if (Session::has('Term')) {
            $enrolment_forms = $enrolment_forms->where('Term', Session::get('Term'));
            $queries['Term'] = Session::get('Term');
        }

        if (\Request::filled('search')) {
            $name = \Request::input('search');
            $enrolment_forms = $enrolment_forms->with('users')
                ->whereHas('users', function ($q) use ($name) {
                    return $q->where('name', 'LIKE', '%' . $name . '%')->orWhere('email', 'LIKE', '%' . $name . '%');
                });
            $queries['search'] = \Request::input('search');
        }

        if (\Request::filled('sort')) {
            $enrolment_forms = $enrolment_forms->orderBy('created_at', \Request::input('sort'));
            $queries['sort'] = \Request::input('sort');
        } else {
            $enrolment_forms = $enrolment_forms->orderBy('created_at', 'asc');
        }

        if (\Request::exists('approval_hr')) {
            if (is_null(\Request::input('approval_hr'))) {
                $enrolment_forms = $enrolment_forms->whereNotIn('DEPT', ['UNOG', 'JIU', 'DDA', 'OIOS', 'DPKO'])->whereNull('is_self_pay_form')->whereNull('approval_hr');
                $queries['approval_hr'] = '';
            }
        }

        $enrolment_forms->select('INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'cancelled_by_student', 'approval', 'approval_hr', 'form_counter', 'eform_submit_count', 'attachment_id', 'attachment_pay', 'created_at', 'std_comments', 'is_self_pay_form', 'selfpay_approval', 'deleted_at', 'updated_by_admin', 'modified_by', 'cancelled_by_admin')->groupBy('INDEXID', 'Term', 'DEPT', 'L', 'Te_Code', 'cancelled_by_student', 'approval', 'approval_hr', 'form_counter', 'eform_submit_count', 'attachment_id', 'attachment_pay', 'created_at', 'std_comments', 'is_self_pay_form', 'selfpay_approval', 'deleted_at', 'updated_by_admin', 'modified_by', 'cancelled_by_admin');
        // $allQueries = array_merge($queries, $currentQueries);
        $enrolment_forms = $enrolment_forms->withTrashed()->paginate(20)->appends($queries);
        return view('preenrolment.index', compact('enrolment_forms', 'languages', 'org', 'terms', 'selectedTerm'));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($indexno, $term)
    {

        return view('preenrolment.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    public function editEnrolmentFields($indexno, $term, $tecode, $eform_submit_count)
    {
        $enrolment_details = Preenrolment::withTrashed()
            ->where('INDEXID', $indexno)
            ->where('Term', $term)->where('Te_Code', $tecode)->where('eform_submit_count', $eform_submit_count)
            ->groupBy(['Te_Code', 'Term', 'INDEXID', 'DEPT', 'is_self_pay_form', 'continue_bool', 'eform_submit_count', 'deleted_at', 'eform_submit_count', 'cancelled_by_student', 'created_at', 'L', 'attachment_id', 'attachment_pay', 'updated_by_admin', 'mgr_fname', 'mgr_lname'])
            ->first(['Te_Code', 'Term', 'INDEXID', 'DEPT', 'is_self_pay_form', 'continue_bool', 'eform_submit_count', 'deleted_at', 'eform_submit_count', 'cancelled_by_student', 'created_at', 'L', 'attachment_id', 'attachment_pay', 'updated_by_admin', 'mgr_fname', 'mgr_lname']);

        $enrolment_schedules = Preenrolment::withTrashed()
            ->orderBy('id', 'asc')
            ->where('Te_Code', $tecode)
            ->where('INDEXID', $indexno)
            ->where('eform_submit_count', $eform_submit_count)
            ->where('Term', $term)->get(['schedule_id', 'mgr_email', 'approval', 'approval_hr', 'is_self_pay_form', 'DEPT', 'deleted_at', 'INDEXID', 'Term', 'Te_Code']);

        $languages = DB::table('languages')->pluck("name", "code")->all();
        $org = Torgan::orderBy('Org name', 'asc')->get(['Org name', 'Org Full Name']);

        return view('preenrolment.edit', compact('enrolment_details', 'enrolment_schedules', 'languages', 'org'));
    }

    public function checkIfPashRecordExists(Request $request)
    {
        // compare Index ID, Term, Language in PASH
        // if exists, get the record(s) and show to user via ajax
        // get user input via ajax
        // continue the update appropriately

        $pashRecord = Repo::where('L', $request->language)->where('INDEXID', $request->INDEXID)->where('Term', $request->Term)->get();

        // if more than 1 record, flag so modal appears

        $data = $pashRecord;
        return response()->json($data);
    }

    public function nothingToModify(Request $request, $indexno, $term, $tecode, $eform_submit_count)
    {
        $enrolment_to_be_copied = Preenrolment::orderBy('id', 'asc')
            ->where('Te_Code', $tecode)
            ->where('INDEXID', $indexno)
            ->where('eform_submit_count', $eform_submit_count)
            ->where('Term', $term)
            ->get();

        $user_id = User::where('indexno', $indexno)->first(['id']);

        foreach ($enrolment_to_be_copied as $data) {
            $data->fill(['updated_by_admin' => 1, 'modified_by' => Auth::user()->id])->save();

            // $arr = $data->attributesToArray();
            // $clone_forms = ModifiedForms::create($arr);
        }
        $request->session()->flash('success', 'Admin confirmation successful!');
        // return redirect()->route('manage-user-enrolment-data', $user_id);
        return redirect()->route('users.index');
    }

    public function checkIfSameCourse($request, $indexno)
    {
        // check if assigned course was already assigned
        $assignedNewCourse = $request->Te_Code . '-' . $request->schedule_id . '-' . $request->Term . '-' . $indexno;
        $checkNewCourseExists = Preenrolment::where('CodeIndexID', $assignedNewCourse)->first();

        if ($checkNewCourseExists) {
            $data = 1;
            return $data;
        }
        $data = 0;
        return $data;
    }

    public function changeSelectedCourse($enrolment_to_be_copied, $indexno, $term, $tecode, $eform_submit_count, $input)
    {
        foreach ($enrolment_to_be_copied as $data) {
            $arr = $data->attributesToArray();
            $clone_forms = ModifiedForms::create($arr); // save original value in ModifiedForms table
        }

        $count_form = $enrolment_to_be_copied->count();
        // if 2 forms with same course was submitted, delete the 2nd greater id value 
        if ($count_form > 1) {
            $delform = Preenrolment::withTrashed()
                ->orderBy('id', 'desc')
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

        $enrolment_to_be_modified = Preenrolment::withTrashed()
            ->orderBy('id', 'asc')
            ->where('Te_Code', $tecode)
            ->where('INDEXID', $indexno)
            ->where('eform_submit_count', $eform_submit_count)
            ->where('Term', $term)
            ->get();

        foreach ($enrolment_to_be_modified as $new_data) {
            $new_data->fill($input)->save();
            // update fields with new data
            $new_data->Code = $new_data->Te_Code . '-' . $new_data->schedule_id . '-' . $new_data->Term;
            $new_data->CodeIndexID = $new_data->Te_Code . '-' . $new_data->schedule_id . '-' . $new_data->Term . '-' . $new_data->INDEXID;
            $new_data->save();
        }
    }

    public function changeHRApproval($request, $enrolment_to_be_copied, $input)
    {
        foreach ($enrolment_to_be_copied as $new_data) {
            $new_data->fill($input)->save();
            $new_data->overall_approval = $request->approval_hr;
            $new_data->save();
        }
    }

    public function changeOrgInForm($request, $enrolment_to_be_copied, $input)
    {
        foreach ($enrolment_to_be_copied as $new_data) {
            $new_data->fill($input)->save();
        }
    }

    public function convertToSelfPaymentForm($request, $enrolmentID)
    {
        foreach ($enrolmentID as $datum) {
            $enrolmentForm = Preenrolment::withTrashed()->find($datum->id);

            if (!is_null($enrolmentForm->INDEXID)) {
                $index_id = $enrolmentForm->INDEXID;
            }

            if (!is_null($enrolmentForm->Term)) {
                $term_id = $enrolmentForm->Term;
            }

            if (!is_null($enrolmentForm->Te_Code)) {
                $course_id = $enrolmentForm->Te_Code;
            }

            if (!is_null($enrolmentForm->L)) {
                $language_id = $enrolmentForm->L;
            }

            if ($request->hasFile('contractFile')) {
                $request->file('contractFile');
                $time = date("d-m-Y") . "-" . time();
                $filename = $time . '_converted_contract_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->contractFile->extension();
                //Store attachment
                $filestore = Storage::putFileAs('public/pdf/' . $index_id, $request->file('contractFile'), $time . '_converted_contract_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->contractFile->extension());
                //Create new record in db table
                $attachment_contract_file = new ContractFile([
                    'user_id' => $enrolmentForm->users->id,
                    'actor_id' => Auth::user()->id,
                    'enrolment_id' => $enrolmentForm->id,
                    'filename' => $filename,
                    'size' => $request->contractFile->getSize(),
                    'path' => $filestore,
                ]);
                $attachment_contract_file->save();
            }
        }
        $enrolmentInfo = Preenrolment::withTrashed()->find($enrolmentID->first()->id);
        // store the attachments to storage path and save in db table
        if ($request->hasFile('identityfile')) {
            $request->file('identityfile');
            $time = date("d-m-Y") . "-" . time();
            $filename = $time . '_converted_id_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->identityfile->extension();
            //Store attachment
            $filestore = Storage::putFileAs('public/pdf/' . $index_id, $request->file('identityfile'), $time . '_converted_id_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->identityfile->extension());
            //Create new record in db table
            $attachment_identity_file = new File([
                'user_id' => $enrolmentInfo->users->id,
                'actor_id' => Auth::user()->id,
                'filename' => $filename,
                'size' => $request->identityfile->getSize(),
                'path' => $filestore,
            ]);
            $attachment_identity_file->save();
        }
        if ($request->hasFile('payfile')) {
            $request->file('payfile');
            $time = date("d-m-Y") . "-" . time();
            $filename = $time . '_converted_payment_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->payfile->extension();
            //Store attachment
            $filestore = Storage::putFileAs('public/pdf/' . $index_id, $request->file('payfile'), $time . '_converted_payment_' . $index_id . '_' . $term_id . '_' . $language_id . '_' . $course_id . '.' . $request->payfile->extension());
            //Create new record in db table
            $attachment_pay_file = new File([
                'user_id' => $enrolmentInfo->users->id,
                'actor_id' => Auth::user()->id,
                'filename' => $filename,
                'size' => $request->payfile->getSize(),
                'path' => $filestore,
            ]);
            $attachment_pay_file->save();
        }

        // set is_self_pay_form flag and other relevant fields
        foreach ($enrolmentID as $valueObj) {
            $formToBeConverted = Preenrolment::withTrashed()->find($valueObj->id);
            $formToBeConverted->is_self_pay_form = 1;
            $formToBeConverted->approval_hr = null;
            $formToBeConverted->approval = null;
            $formToBeConverted->attachment_id = $attachment_identity_file->id;
            $formToBeConverted->attachment_pay = $attachment_pay_file->id;
            $formToBeConverted->save();
        }

        $isSelfPayValue = 1;
        $this->updatePASHRecord($request, $enrolmentID, $isSelfPayValue);
    }

    public function convertToRegularForm($request, $enrolmentID)
    {
        // set is_self_pay_form flag = 0 and other relevant fields
        foreach ($enrolmentID as $valueObj) {
            $formToBeConverted = Preenrolment::withTrashed()->find($valueObj->id);
            $formToBeConverted->is_self_pay_form = null;
            $formToBeConverted->selfpay_approval = null;
            $formToBeConverted->approval_hr = null;
            $formToBeConverted->approval = 1;
            $formToBeConverted->attachment_id = null;
            $formToBeConverted->attachment_pay = null;
            $formToBeConverted->save();
        }

        $isSelfPayValue = 0;
        $this->updatePASHRecord($request, $enrolmentID, $isSelfPayValue);
    }

    public function updatePASHRecord(Request $request, $enrolmentID, $isSelfPayValue)
    {
        $enrolmentForm = PlacementForm::withTrashed()
            ->orderBy('id', 'asc')
            ->where('id', $enrolmentID->first()->id)
            ->first();

        $pashRecord = Repo::where('CodeIndexID', $enrolmentForm->CodeIndexID)->get();

        if (!$pashRecord->isEmpty()) {
            // set is_self_pay_form field/flag
            foreach ($pashRecord as $record) {
                if ($isSelfPayValue == 1) {
                    $record->is_self_pay_form = 1;
                } else {
                    $record->is_self_pay_form = null;
                }
                $record->save();
            }
        }
    }

    public function updateEnrolmentFields(Request $request, $indexno, $term, $tecode, $eform_submit_count)
    {
        // dd(array_filter($request->all()), $indexno, $term, $tecode, $eform_submit_count);

        $enrolment_to_be_copied = Preenrolment::withTrashed()
            ->orderBy('id', 'asc')
            ->where('Te_Code', $tecode)
            ->where('INDEXID', $indexno)
            ->where('eform_submit_count', $eform_submit_count)
            ->where('Term', $term)
            ->get();

        $enrolmentID = Preenrolment::withTrashed()
            ->orderBy('id', 'asc')
            ->where('Te_Code', $tecode)
            ->where('INDEXID', $indexno)
            ->where('eform_submit_count', $eform_submit_count)
            ->where('Term', $term)
            ->get(['id']);

        $input = $request->all();
        $input = array_filter($input, 'strlen');

        // insert compare db field values to request values method here
        // if $data == 0 on any of the request values then copy original form(s) to Modified Forms table
        // else return nothing to modify message 

        // get what fields are being modified
        if ($request->radioFullSelectDropdown) {
            // check if assigned course was already assigned
            $data = $this->checkIfSameCourse($request, $indexno);

            if ($data == 1) {
                $request->session()->flash('msg-same-course', 'No modification done because existing course and schedule chosen.');
            }
            // change course
            if ($data == 0) {
                $this->changeSelectedCourse($enrolment_to_be_copied, $indexno, $term, $tecode, $eform_submit_count, $input);
                $request->session()->flash('msg-course-updated', 'Course selection has been updated.');
            }
        }

        if ($request->radioChangeHRApproval) {
            // change HR approval
            $this->changeHRApproval($request, $enrolment_to_be_copied, $input);
        }

        if ($request->radioChangeOrgInForm) {
            // change organization
            $this->changeOrgInForm($request, $enrolment_to_be_copied, $input);
            $request->session()->flash('msg-change-org', 'Organization field has been updated.');
        }

        if ($request->radioSelfPayOptions) {
            // self-payment options

            if ($request->decisionConvert == 1) {
                $this->validate($request, [
                    'identityfile' => 'mimes:pdf,doc,docx|max:8000',
                    'payfile' => 'mimes:pdf,doc,docx|max:8000',
                ]);

                // convert to self-payment
                $this->convertToSelfPaymentForm($request, $enrolmentID);
                $request->session()->flash('msg-convert-to-selfpay-form', 'Form has been converted.');
            }

            if ($request->decisionConvert == 0) {
                // convert to regular
                $this->convertToRegularForm($request, $enrolmentID);
                $request->session()->flash('msg-convert-to-selfpay-form', 'Form has been converted.');
            }
        }

        if ($request->radioUndoDeleteStatus) {
            foreach ($enrolment_to_be_copied as $enrolmentToBeRestore) {
                $enrolmentToBeRestore->restore();
                $request->session()->flash('msg-restore-form', 'Form has been restored.');
            }
        }

        // always log who modified the record
        $input_1 = ['modified_by' => Auth::user()->id];
        $input_1 = array_filter($input_1, 'strlen');

        foreach ($enrolmentID as $data) {
            $enrolmentForm = Preenrolment::withTrashed()->find($data->id);
            $enrolmentForm->fill($input_1)->save();

            if (!is_null($enrolmentForm->INDEXID)) {
                $indexnoNew = $enrolmentForm->INDEXID;
            }

            if (!is_null($enrolmentForm->Term)) {
                $termNew = $enrolmentForm->Term;
            }

            if (!is_null($enrolmentForm->Te_Code)) {
                $tecodeNew = $enrolmentForm->Te_Code;
            }

            if (!is_null($enrolmentForm->eform_submit_count)) {
                $eform_submit_countNew = $enrolmentForm->eform_submit_count;
            }

            // logic if need to delete or restore the record(s)
            // use triple = sign to compare datatype and value
            // et distinguez 0 <> null
            if ($request->approval_hr === '0') {
                $enrolmentForm->delete();
                $request->session()->flash('msg-delete-form', 'HR approval updated. Form has been cancelled.');
            } elseif ($request->approval_hr === '1') {
                $enrolmentForm->restore();
                $request->session()->flash('msg-restore-form', 'HR approval updated.');
            }
        }

        return redirect()->action('PreenrolmentController@editEnrolmentFields', [$indexnoNew, $termNew, $tecodeNew, $eform_submit_countNew]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $staff, $tecode,  $term, $form)
    {
        $current_user = $staff;
        $admin_id = Auth::user()->id;

        //query submitted forms based from tblLTP_Enrolment table
        $forms = Preenrolment::orderBy('Term', 'desc')
            ->where('Te_Code', $tecode)
            ->where('INDEXID', '=', $current_user)
            ->where('Term', $term)
            ->where('eform_submit_count', $form)
            ->get();
        $display_language = Preenrolment::orderBy('Term', 'desc')
            ->where('Te_Code', $tecode)
            ->where('INDEXID', '=', $current_user)
            ->where('Term', $term)
            ->where('eform_submit_count', $form)
            ->first();

        //get email address of the Manager
        $mgr_email = $forms->pluck('mgr_email')->first();

        // get is_self_pay_form value
        $is_self_pay_form = $forms->pluck('is_self_pay_form')->first();

        //if self-paying enrolment form do this
        if ($is_self_pay_form == 1) {
            $type = 0; // 0 = regular enrolment form
            $display_language_en = $display_language->courses->EDescription;
            $display_language_fr = $display_language->courses->FDescription;

            $arraySchedule = [];
            foreach ($forms as $valueForms) {
                $arraySchedule[] = $valueForms->schedule->name;
            }

            $schedule = implode(' / ', $arraySchedule);
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

            $enrol_form = [];
            for ($i = 0; $i < count($forms); $i++) {
                $enrol_form = $forms[$i]->id;
                $delform = Preenrolment::find($enrol_form);
                $delform->admin_eform_cancel_comment = $request->admin_eform_cancel_comment;
                // $delform->cancelled_by_student = 1;
                $delform->cancelled_by_admin = $admin_id;
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
            $delform->admin_eform_cancel_comment = $request->admin_eform_cancel_comment;
            // $delform->cancelled_by_student = 1;
            $delform->cancelled_by_admin = $admin_id;
            $delform->save();
            $delform->delete();
        }


        session()->flash('cancel_success', 'Enrolment Form for ' . $display_language->courses->EDescription . ' has been cancelled. If necessary, an email has been sent to the HR/Staff Development Office of the student.');
        return redirect()->back();
    }

    public function deleteNoEmail(Request $request)
    {
        if ($request->ajax()) {
            $indexno = $request->qry_indexid;
            $term = $request->qry_term;
            $tecode = $request->qry_tecode;
            $eform_submit_count = $request->eform_submit_count;
            $admin_eform_cancel_comment = $request->admin_eform_cancel_comment;

            $enrolment_to_be_deleted = Preenrolment::orderBy('id', 'asc')
                ->where('Te_Code', $tecode)
                ->where('INDEXID', $indexno)
                ->where('eform_submit_count', $eform_submit_count)
                ->where('Term', $term)
                ->get();

            $input_1 = [
                'admin_eform_cancel_comment' => $admin_eform_cancel_comment,
                // 'updated_by_admin' => 1,
                // 'modified_by' => Auth::user()->id, 
                'cancelled_by_admin' => Auth::user()->id
            ];
            $input_1 = array_filter($input_1, 'strlen');

            foreach ($enrolment_to_be_deleted as $data) {
                $data->fill($input_1)->save();
                $data->delete();
            }

            $data = $request->all();

            return response()->json($data);
        }
    }
}
