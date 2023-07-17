<?php

namespace App\Http\Controllers;

use App\Traits\VerifyAndNotAssignTwoRecords;
use App\Attendance;
use App\AttendanceRemarks;
use App\Classroom;
use App\Language;
use App\Mail\EmailClassroomsToTeachers;
use App\Mail\EmailSummerClassroomsToTeachers;
use App\ModifiedForms;
use App\NewUser;
use App\PlacementForm;
use App\Preenrolment;
use App\Repo;
use App\SDDEXTR;
use App\Teachers;
use App\Term;
use App\Torgan;
use App\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class TeachersController extends Controller
{
    use VerifyAndNotAssignTwoRecords;

    public function teacherDashboard(Request $request)
    {
        if (Auth::user()->hasRole('Teacher')) {
            $terms = Term::orderBy('Term_Code', 'desc')->get();

            if (is_object(Auth::user()->teachers)) {
                $assigned_classes = Classroom::where('Tch_ID', Auth::user()->teachers->Tch_ID)
                    ->where('Te_Term', Session::get('Term'))
                    ->get();
                $all_classes = Classroom::where('L', Auth::user()->teachers->Tch_L)
                    ->where('Tch_ID', '!=', 'TBD')
                    ->where('Te_Term', Session::get('Term'))
                    ->get();

                return view('teachers.teacher_dashboard', compact('terms', 'assigned_classes', 'all_classes'));
            }

            // notify admin by email
            Mail::raw("User->Teacher is a non-object id " . Auth::id(), function ($message) {
                $message->from('ltp_web_admin@unog.ch', 'CLM Language Web Admin');
                $message->to('allyson.frias@un.org')->subject("User->Teacher is a non-object" . Auth::id());
            });

            $request->session()->flash('error', 'Insufficient access rights. You have been redirected.');
            return redirect()->route('home');
        }

        $request->session()->flash('error', 'Insufficient access rights. You have been redirected.');
        return redirect()->route('home');
    }

    public function teacherSearchUser()
    {
        if (\Request::input('search')) {
            $queries = [];
            $query = \Request::input('search');
            // Returns an array of users that have the query string located somewhere within 
            // our users name or email fields. Paginates them so we can break up lots of search results.
            $queries['search'] = \Request::input('search');
            $users = User::search($query)->paginate(20);
            $users->appends($queries);
            if ($users->getCollection()->count() == 0) {
                return redirect()->route('teacher-search-user')->with('users', $users)->with('interdire-msg', 'No such user found in the login accounts records of the system. ');
            }

            return view('teachers.teacher_search_user')->with('users', $users);
        }
        $users = User::paginate(20);
        return view('teachers.teacher_search_user')->with('users', $users);
    }

    public function teacherLtpdataView(Request $request, $id)
    {
        $id = $id;
        $student = User::where('id', $id)->first();
        $terms = Term::orderBy('Term_Code', 'desc')->get();

        $student_enrolments = Preenrolment::withTrashed()->where('INDEXID', $student->indexno)
            ->where('Term', $request->Term)
            ->groupBy(['Te_Code', 'Term', 'INDEXID', 'DEPT', 'is_self_pay_form', 'selfpay_approval', 'continue_bool', 'form_counter', 'deleted_at', 'eform_submit_count', 'cancelled_by_student', 'cancelled_by_admin', 'created_at', 'L', 'approval', 'approval_hr', 'attachment_id', 'attachment_pay', 'modified_by', 'updated_by_admin', 'std_comments', 'admin_eform_cancel_comment'])
            ->get(['Te_Code', 'Term', 'INDEXID', 'DEPT', 'is_self_pay_form', 'selfpay_approval', 'continue_bool', 'form_counter', 'deleted_at', 'eform_submit_count', 'cancelled_by_student', 'cancelled_by_admin', 'created_at', 'L', 'approval', 'approval_hr', 'attachment_id', 'attachment_pay', 'modified_by', 'updated_by_admin', 'std_comments', 'admin_eform_cancel_comment']);

        $student_placements = PlacementForm::withTrashed()
            ->orderBy('id', 'asc')
            ->where('INDEXID', $student->indexno)
            ->where('Term', $request->Term)->get();

        $student_convoked = Repo::withTrashed()->whereNotNull('CodeIndexIDClass')->where('INDEXID', $student->indexno)->where('Term', $request->Term)->get();

        $batch_implemented = Repo::where('Term', $request->Term)->count(); // flag to indicate if batch has been ran or not

        $student_last_term = Repo::orderBy('Term', 'desc')->where('INDEXID', $student->indexno)->first(['Term']);
        $historical_data = Repo::orderBy('Term', 'desc')->where('INDEXID', $student->indexno)->get();
        $placement_records = PlacementForm::withTrashed()
            ->where('INDEXID', $student->indexno)
            ->get();

        $term_info = Term::where('Term_Code', $request->Term)->first();

        if ($student_last_term == null) {
            $repos_lang = null;
            return view('teachers.teacher_ltpdata_view', compact('terms', 'id', 'student', 'student_enrolments', 'student_placements', 'repos_lang', 'historical_data', 'placement_records', 'student_convoked', 'term_info', 'batch_implemented'));
        }

        $repos_lang = Repo::orderBy('Term', 'desc')->where('Term', $student_last_term->Term)
            ->where('INDEXID', $student->indexno)->first();

        if (is_null($request->Term)) {
            $student_enrolments = null;
            $student_placements = null;

            return view('teachers.teacher_ltpdata_view', compact('terms', 'id', 'student', 'student_enrolments', 'student_placements', 'repos_lang', 'historical_data', 'placement_records', 'student_convoked', 'term_info', 'batch_implemented'));
        }


        return view('teachers.teacher_ltpdata_view', compact('terms', 'id', 'student', 'student_enrolments', 'student_placements', 'repos_lang', 'historical_data', 'placement_records', 'student_convoked', 'term_info', 'batch_implemented'));
    }

    public function teacherEnrolmentPreview(Request $request)
    {
        $languages = DB::table('languages')->pluck("name", "code")->all();
        $org = Torgan::orderBy('Org name', 'asc')->get(['Org name', 'Org Full Name']);
        $terms = Term::orderBy('Term_Code', 'desc')->get();

        $term = Session::get('Term');

        if (!Session::has('Term')) {
            $enrolment_forms = null;
            return view('teachers.teacher_enrolment_preview', compact('enrolment_forms', 'languages', 'org', 'terms'));
        }

        $q = Preenrolment::where('Term', $term)->where('overall_approval', '1')->orderBy('created_at', 'asc')->get();
        $q2 = PlacementForm::where('Term', $term)->whereNotNull('Te_Code')->where('overall_approval', '1')->orderBy('created_at', 'asc')->get();
        $merge = collect($q)->merge($q2);

        $enrolment_forms = $merge->unique(function ($item) {
            return $item['INDEXID'] . $item['Te_Code'];
        })
            ->sortBy('created_at');

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

        if (\Request::exists('approval_hr')) {
            if (is_null(\Request::input('approval_hr'))) {
                $enrolment_forms = $enrolment_forms->whereNotIn('DEPT', ['UNOG', 'JIU', 'DDA', 'OIOS', 'DPKO'])->whereNull('is_self_pay_form')->whereNull('approval_hr');
                $queries['approval_hr'] = '';
            }
        }

        // $enrolment_forms->select('INDEXID', 'Term', 'DEPT','L', 'Te_Code', 'cancelled_by_student', 'approval', 'approval_hr', 'form_counter', 'eform_submit_count', 'attachment_id', 'attachment_pay', 'created_at','std_comments', 'is_self_pay_form','selfpay_approval','deleted_at', 'updated_by_admin', 'modified_by')->groupBy('INDEXID', 'Term', 'DEPT','L', 'Te_Code', 'cancelled_by_student', 'approval', 'approval_hr', 'form_counter', 'eform_submit_count', 'attachment_id', 'attachment_pay', 'created_at', 'std_comments', 'is_self_pay_form','selfpay_approval','deleted_at', 'updated_by_admin', 'modified_by');
        // $count = count($enrolment_forms->get());
        $count = count($enrolment_forms);
        // $enrolment_forms = $enrolment_forms->paginate(20)->appends($queries);

        return view('teachers.teacher_enrolment_preview', compact('enrolment_forms', 'languages', 'org', 'terms', 'count'));
    }

    public function teacherEnrolmentPreviewTableView(Request $request)
    {
        if (!Session::has('Term')) {
            $enrolment_forms = null;
            return view('teachers.teacher_enrolment_preview', compact('enrolment_forms'));
        }

        $term = Session::get('Term');
        $Te_Code = $request->Te_Code;

        return view('teachers.teacher_enrolment_preview_table_view', compact('Te_Code', 'term'));
    }

    /**
     * undocumented function summary
     *
     * Undocumented function long description
     *
     * @param Request $request
     * @return json $data
     * @throws conditon
     **/
    public function teacherEnrolmentPreviewTable(Request $request)
    {
        $term = Session::get('Term');

        $q = Preenrolment::with('courses')->with('modifyUser')->with('schedule')->with(['users' => function ($q0) {
            $q0->with('sddextr');
        }])->where('Term', $term)->where('overall_approval', '1')->orderBy('created_at', 'asc')->get();
        $q2 = PlacementForm::with('courses')->with('modifyUser')->with('schedule')->with(['users' => function ($q1) {
            $q1->with('sddextr');
        }])->where('Term', $term)->whereNotNull('Te_Code')->where('overall_approval', '1')->orderBy('created_at', 'asc')->get();
        $merge = collect($q)->merge($q2);

        $enrolment_forms = $merge->unique(function ($item) {
            return $item['INDEXID'] . $item['Te_Code'];
        })
            ->sortBy('created_at');

        $queries = [];

        $columns = [
            'Te_Code',
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

        $array = [];
        $indexnosArray = [];
        $eformSubmitCountArray = [];
        $languageArray = [];
        foreach ($enrolment_forms as $form) {
            $assign_status = null;
            $assigned_by = null;
            $assigned_course = null;
            $assigned_schedule = null;

            if ($form->updated_by_admin === 1) {
                $assign_status = 'YES';
                $assigned_by = $form->modifyUser->name;
                $assigned_course = $form->courses->Description;
                $assigned_schedule = $form->schedule->name;
            }
            if ($form->updated_by_admin === 0) {
                $assign_status = 'Verified and Not Assigned';
                $assigned_by = $form->modifyUser->name;
            }
            if ($form->updated_by_admin === null) {
                $assign_status = 'Not Assigned';
            }
            $phone = 'None';
            if ($form->users->sddextr->PHONE) {
                $phone = $form->users->sddextr->PHONE;
            }

            $dayInput = null;
            if (!is_null($form->dayInput)) {
                $dayInput = $form->dayInput;
            }

            $timeInput = null;
            if (!is_null($form->timeInput)) {
                $timeInput = $form->timeInput;
            }

            $deliveryMode = null;
            if (!is_null($form->deliveryMode)) {
                if ($form->deliveryMode === 0) {
                    $deliveryMode = 'in-person';
                } elseif ($form->deliveryMode === 1) {
                    $deliveryMode = 'online';
                } elseif ($form->deliveryMode === 2) {
                    $deliveryMode = 'both in-person and online';
                }
            }

            $flexibleDay = null;
            if (!is_null($form->flexibleDay))
                if ($form->flexibleDay === 1) {
                    $flexibleDay = 'YES';
                }
            if ($form->flexibleDay === 0) {
                $flexibleDay = 'NOT FLEXIBLE';
            }

            $flexibleTime = null;
            if (!is_null($form->flexibleTime)) {
                if ($form->flexibleTime === 1) {
                    $flexibleTime = 'YES';
                }
                if ($form->flexibleTime === 0) {
                    $flexibleTime = 'NOT FLEXIBLE';
                }
            }

            $flexibleFormat = null;
            if (!is_null($form->flexibleFormat)) {
                if ($form->flexibleFormat === 1) {
                    $flexibleFormat = 'YES';
                }
                if ($form->flexibleFormat === 0) {
                    $flexibleFormat = 'NOT FLEXIBLE';
                }
            }

            $hr_approval = null;
            if (is_null($form->is_self_pay_form)) {
                if (in_array($form->DEPT, ['UNOG', 'JIU', 'DDA', 'OIOS', 'DPKO'])) {
                    $hr_approval = 'N/A - Non-paying organization';
                } else {
                    if (is_null($form->approval) && is_null($form->approval_hr)) {
                        $hr_approval = 'Pending Approval';
                    }
                    if ($form->approval == 0 && (is_null($form->approval_hr) || isset($form->approval_hr))) {
                        $hr_approval = 'N/A - Disapproved by Manager';
                    }
                    if ($form->approval == 1 && is_null($form->approval_hr)) {
                        $hr_approval = 'Pending Approval';
                    }
                    if ($form->approval == 1 && $form->approval_hr == 1) {
                        $hr_approval = 'Approved';
                    }
                    if ($form->approval == 1 && $form->approval_hr == 0) {
                        $hr_approval = 'Disapproved';
                    }
                }
            } else {
                $hr_approval = 'N/A - Self-Payment';
            }

            $payment_status = null;
            if (is_null($form->is_self_pay_form)) {
                $payment_status = 'N/A';
            } else {
                if ($form->selfpay_approval === 1) {
                    $payment_status = 'Approved';
                }
                if ($form->selfpay_approval === 2) {
                    $payment_status = 'Pending Valid Document';
                }
                if ($form->selfpay_approval === 0) {
                    $payment_status = 'Disapproved';
                }
                if ($form->selfpay_approval === null) {
                    $payment_status = 'Waiting for Admin';
                }
            }

            $student_comment = null;
            $placement_form = null;
            if ($form->placement_schedule_id) {
                $student_comment = $form->std_comments . $form->course_preference_comment;
                $placement_form = 'Placement Form';
            } else {
                if ($form->std_comments) {
                    $student_comment = $form->std_comments;
                }
            }

            $re_enrolment = $this->checkIfReEnrolment($term, $form->INDEXID, $form);
            $not_in_a_class = $this->checkNotInClass($term, $form->INDEXID, $form);
            $waitlisted = $this->checkIfWaitlisted($term, $form->INDEXID, $form);
            $within_2_terms = $this->checkIfWithin2Terms($term, $form->INDEXID, $form);

            $wishlist_schedule = $this->checkIfWishlistSchedule($term, $form->INDEXID, $form);

            $indexnosArray[] = $form->INDEXID;
            $eformSubmitCountArray[] = $form->eform_submit_count;
            $languageArray[] = $form->L;

            $deleted_at = null;
            if (!is_null($form->deleted_at)) {
                $deleted_at = $form->deleted_at->format('Y-m-d H:i:s');
            }

            $array[] =  (object) [
                'assign_status' => $assign_status,
                'assigned_by' => $assigned_by,
                'assigned_course' => $assigned_course,
                'assigned_schedule' => $assigned_schedule,
                're_enrolment' => $re_enrolment,
                'placement_form' => $placement_form,
                'not_in_a_class' => $not_in_a_class,
                'waitlisted' => $waitlisted,
                'within_2_terms' => $within_2_terms,
                'wishlist_schedule' => $wishlist_schedule,
                'INDEXID' => $form->INDEXID,
                'name' => $form->users->name,
                'email' => $form->users->email,
                'PHONE' => $phone,
                'courses_Description' => $form->courses->Description,
                'dayInput' => $dayInput,
                'timeInput' => $timeInput,
                'deliveryMode' => $deliveryMode,
                'flexibleDay' => $flexibleDay,
                'flexibleTime' => $flexibleTime,
                'flexibleFormat' => $flexibleFormat,
                'DEPT' => $form->DEPT,
                'cancelled_by_student' => $form->cancelled_by_student,
                'hr_approval' => $hr_approval,
                'payment_status' => $payment_status,
                'student_comment' => $student_comment,
                'admin_eform_comment' => $form->admin_eform_comment,
                'admin_plform_comment' => $form->admin_plform_comment,
                'created_at' => $form->created_at->format('Y-m-d H:i:s'),
                'deleted_at' => $deleted_at,
            ];
        }
        $data = $array;
        $priority = $this->getStudentPriorityStatus($term, $indexnosArray, $eformSubmitCountArray, $languageArray);

        return response()->json(['data' => $data, 'ps' => $priority]);
    }

    public function checkIfWishlistSchedule($term, $index, $form)
    {
        $current_user = $index;
        $term_code = $term;

        $user = User::where('indexno', $current_user)->first();
        $user = $user->name;

        // check the original wishlist of student in placement forms table
        $check_placement_forms = PlacementForm::where('INDEXID', $current_user)
            ->where('Te_Code', $form->Te_Code)
            ->where('Term', $term_code)->count();

        if ($check_placement_forms > 0) {
            // query submitted forms based from Modified Forms table
            $schedules = PlacementForm::withTrashed()
                ->where('Te_Code', $form->Te_Code)
                ->where('INDEXID', $current_user)
                ->where('eform_submit_count', $form->eform_submit_count)
                ->where('Term', $term_code)
                ->get(['schedule_id', 'mgr_email', 'approval', 'approval_hr', 'is_self_pay_form', 'DEPT', 'deleted_at', 'INDEXID', 'Term', 'Te_Code', 'selfpay_approval', 'assigned_to_course']);



            $wishlist_schedule = [];
            foreach ($schedules as $sched_value) {
                $wishlist_schedule[] = $sched_value->schedule->name;
            }

            return $wishlist_schedule;
        }

        // check the original wishlist of student in modified forms table first 
        $check_modified_forms = ModifiedForms::where('INDEXID', $current_user)->where('Te_Code', $form->Te_Code)->where('Term', $term_code)->count();

        if ($check_modified_forms > 0) {
            // query submitted forms based from Modified Forms table
            $schedules = ModifiedForms::withTrashed()
                // ->where('Te_Code', $form->Te_Code)
                ->where('INDEXID', $current_user)
                ->where('eform_submit_count', $form->eform_submit_count)
                ->where('Term', $term_code)
                ->get(['schedule_id', 'mgr_email', 'approval', 'approval_hr', 'is_self_pay_form', 'DEPT', 'deleted_at', 'INDEXID', 'Term', 'Te_Code', 'selfpay_approval']);



            $wishlist_schedule = [];
            foreach ($schedules as $sched_value) {
                $wishlist_schedule[] = $sched_value->schedule->name;
            }

            return $wishlist_schedule;
        }

        // query submitted forms based from tblLTP_Enrolment table
        $schedules = Preenrolment::withTrashed()
            ->where('Te_Code', $form->Te_Code)
            ->where('INDEXID', $current_user)
            ->where('eform_submit_count', $form->eform_submit_count)
            ->where('Term', $term_code)
            ->get(['schedule_id', 'mgr_email', 'approval', 'approval_hr', 'is_self_pay_form', 'DEPT', 'deleted_at', 'INDEXID', 'Term', 'Te_Code', 'selfpay_approval']);



        $wishlist_schedule = [];
        foreach ($schedules as $sched_value) {
            $wishlist_schedule[] = $sched_value->schedule->name;
        }

        return $wishlist_schedule;
    }

    public function checkIfReEnrolment($term, $index, $form)
    {
        $prev_term = Term::where('Term_Code', $term)->first()->Term_Prev;

        // query students in class
        $students_in_class = Repo::where('INDEXID', $index)->where('Term', $prev_term)->whereHas('classrooms', function ($query) {
            $query->whereNotNull('Tch_ID')
                ->where('Tch_ID', '!=', 'TBD');
        })
            ->get();
        // put inside array
        $arr1 = [];
        foreach ($students_in_class as $key1 => $value1) {
            $arr1[] = $value1->INDEXID;
        }
        $arr1 = array_unique($arr1); // contains index of re-enrolled students

        $re_enrolment = null;
        foreach ($arr1 as $indexno) {
            if ($indexno = $form->INDEXID) {
                $re_enrolment = 'Re-enrolment';
            }
        }

        return $re_enrolment;
    }
    public function checkNotInClass($term, $index, $form)
    {
        $prev_term = Term::where('Term_Code', $term)->first()->Term_Prev;

        // query students in class
        $students_in_class = Repo::where('INDEXID', $index)->where('Term', $prev_term)->whereHas('classrooms', function ($query) {
            $query->whereNotNull('Tch_ID')
                ->where('Tch_ID', '!=', 'TBD');
        })
            ->get();
        // put inside array
        $arr1 = [];
        foreach ($students_in_class as $key1 => $value1) {
            $arr1[] = $value1->INDEXID;
        }
        $arr1 = array_unique($arr1);


        $q = Preenrolment::where('INDEXID', $index)->where('Term', $term)->where('overall_approval', '1')->orderBy('created_at', 'asc')->get();
        $arr2 = [];
        foreach ($q as $key2 => $value2) {
            $arr2[] = $value2->INDEXID;
        }
        $arr2 = array_unique($arr2);

        // Compares array1 against one or more other arrays and returns the values in array1 that are not present in any of the other arrays
        $students_not_in_class = array_diff($arr2, $arr1); // get all enrolment_forms not included in students_in_class
        $unique_students_not_in_class = array_unique($students_not_in_class);

        $not_in_a_class = null;
        foreach ($unique_students_not_in_class as $indexno) {
            if ($indexno = $form->INDEXID) {
                $not_in_a_class = 'Not in a class';
            }
        }

        return $not_in_a_class;
    }
    public function checkIfWaitlisted($term, $index, $form)
    {
        $prev_term = Term::where('Term_Code', $term)->first()->Term_Prev;

        // query waitlisted students
        $students_waitlisted = Repo::where('INDEXID', $index)->where('Term', $prev_term)->whereHas('classrooms', function ($query) {
            $query->whereNull('Tch_ID')
                ->orWhere('Tch_ID', '=', 'TBD');
        })
            ->get();
        // put inside array
        $waitlisted = [];
        foreach ($students_waitlisted as $key3 => $value3) {
            $waitlisted[] = $value3->INDEXID;
        }
        $waitlisted = array_unique($waitlisted);

        $waitlisted_estudyante = null;
        foreach ($waitlisted as $indexno) {
            if ($indexno = $form->INDEXID) {
                $waitlisted_estudyante = 'Waitlisted';
            }
        }

        return $waitlisted_estudyante;
    }
    public function checkIfWithin2Terms($term, $index, $form)
    {
        $prev_term = Term::where('Term_Code', $term)->first()->Term_Prev;
        // query students in class
        $students_in_class = Repo::where('INDEXID', $index)->where('Term', $prev_term)->whereHas('classrooms', function ($query) {
            $query->whereNotNull('Tch_ID')
                ->where('Tch_ID', '!=', 'TBD');
        })
            ->get();
        // put inside array
        $arr1 = [];
        foreach ($students_in_class as $key1 => $value1) {
            $arr1[] = $value1->INDEXID;
        }
        $arr1 = array_unique($arr1);


        $q = Preenrolment::where('INDEXID', $index)->where('Term', $term)->where('overall_approval', '1')->orderBy('created_at', 'asc')->get();
        $arr2 = [];
        foreach ($q as $key2 => $value2) {
            $arr2[] = $value2->INDEXID;
        }
        $arr2 = array_unique($arr2);

        // Compares array1 against one or more other arrays and returns the values in array1 that are not present in any of the other arrays
        $students_not_in_class = array_diff($arr2, $arr1); // get all enrolment_forms not included in students_in_class
        $unique_students_not_in_class = array_unique($students_not_in_class);

        $prev_prev_term = Term::where('Term_Code', $prev_term)->first()->Term_Prev;

        $students_within_two_terms = Repo::whereIn('INDEXID', $unique_students_not_in_class)->where('Term', $prev_prev_term)->get();
        // put inside array
        $within_two_terms = [];
        foreach ($students_within_two_terms as $key4 => $value4) {
            $within_two_terms[] = $value4->INDEXID;
        }
        $within_two_terms = array_unique($within_two_terms);

        $within_2_terms = null;
        foreach ($within_two_terms as $indexno) {
            if ($indexno = $form->INDEXID) {
                $within_2_terms = 'Within 2 terms';
            }
        }

        return $within_2_terms;
    }
    public function getStudentPriorityStatus($term, $indexnosArray, $eformSubmitCountArray, $languageArray)
    {
        $prev_term = Term::where('Term_Code', $term)->first()->Term_Prev;

        // query students in class
        $students_in_class = Repo::whereIn('INDEXID', $indexnosArray)->where('Term', $prev_term)->whereHas('classrooms', function ($query) {
            $query->whereNotNull('Tch_ID')
                ->where('Tch_ID', '!=', 'TBD');
        })
            ->get();
        // put inside array
        $arr1 = [];
        foreach ($students_in_class as $key1 => $value1) {
            $arr1[] = $value1->INDEXID;
        }
        $arr1 = array_unique($arr1);


        $q = Preenrolment::whereIn('INDEXID', $indexnosArray)->where('Term', $term)->where('overall_approval', '1')->orderBy('created_at', 'asc')->get();
        $arr2 = [];
        foreach ($q as $key2 => $value2) {
            $arr2[] = $value2->INDEXID;
        }
        $arr2 = array_unique($arr2);

        // Compares array1 against one or more other arrays and returns the values in array1 that are not present in any of the other arrays
        $students_not_in_class = array_diff($arr2, $arr1); // get all enrolment_forms not included in students_in_class
        $unique_students_not_in_class = array_unique($students_not_in_class);


        // query waitlisted students
        $students_waitlisted = Repo::whereIn('INDEXID', $indexnosArray)->where('Term', $prev_term)->whereHas('classrooms', function ($query) {
            $query->whereNull('Tch_ID')
                ->orWhere('Tch_ID', '=', 'TBD');
        })
            ->get();
        // put inside array
        $waitlisted = [];
        foreach ($students_waitlisted as $key3 => $value3) {
            $waitlisted[] = $value3->INDEXID;
        }
        $waitlisted = array_unique($waitlisted);


        $prev_prev_term = Term::where('Term_Code', $prev_term)->first()->Term_Prev;

        $students_within_two_terms = Repo::whereIn('INDEXID', $unique_students_not_in_class)->where('Term', $prev_prev_term)->get();
        // put inside array
        $within_two_terms = [];
        foreach ($students_within_two_terms as $key4 => $value4) {
            $within_two_terms[] = $value4->INDEXID;
        }
        $within_two_terms = array_unique($within_two_terms);


        // count how many schedules were originally chosen
        $check_modified_forms = ModifiedForms::whereIn('INDEXID', $indexnosArray)->whereIn('eform_submit_count', $eformSubmitCountArray)->where('Term', $term)->where('L', $languageArray)->where('overall_approval', '1')->get();

        $count_schedule_in_modified_table = [];
        foreach ($check_modified_forms as $key7 => $value7) {
            $count_schedule_in_modified_table[] = $value7->INDEXID;
        }

        $count_schedule_in_modified_table = array_count_values($count_schedule_in_modified_table);

        $qry = Preenrolment::whereIn('INDEXID', $indexnosArray)->whereIn('eform_submit_count', $eformSubmitCountArray)->where('Term', $term)->where('L', $languageArray)->where('overall_approval', '1')->orderBy('created_at', 'asc')->get();

        $count_schedule = [];
        foreach ($qry as $key6 => $value6) {
            $count_schedule[] = $value6->INDEXID;
        }

        $count_schedule = array_count_values($count_schedule);

        $priority = [$arr1, $unique_students_not_in_class, $waitlisted, $within_two_terms, $count_schedule, $count_schedule_in_modified_table];
        return $priority;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $languages = DB::table('languages')->pluck("name", "code")->all();

        $teachers = new Teachers;
        // $currentQueries = \Request::query();
        $queries = [];

        $columns = [
            'Tch_L',
        ];


        foreach ($columns as $column) {
            if (\Request::filled($column)) {
                $teachers = $teachers->where($column, \Request::input($column));
                $queries[$column] = \Request::input($column);
            }
        }

        if (\Request::filled('search')) {
            $name = \Request::input('search');
            $teachers = $teachers->with('users')
                ->whereHas('users', function ($q) use ($name) {
                    return $q->where('name', 'LIKE', '%' . $name . '%')->orWhere('email', 'LIKE', '%' . $name . '%');
                });
            $queries['search'] = \Request::input('search');
        }

        $teachers = $teachers->orderBy('In_Out', 'desc')->orderBy('Tch_Lastname', 'asc')->get();


        return view('teachers.index', compact('teachers', 'languages'));
    }

    public function teacherEmailClassroomsToTeachers(Request $request)
    {
        $terms = Term::orderBy('Term_Code', 'desc')->get();

        if ($request->session()->has('Term')) {
            $selectedTerm = Term::orderBy('Term_Code', 'desc')->where('Term_Code', $request->session()->get('Term'))->first();
            $languages = Language::all();

            // get all teachers with a class of the selected term
            $queryTeachers = Teachers::whereHas('classrooms', function ($query) use ($request) {
                $query->where('Te_Term', $request->session()->get('Term'))
                    ->whereNotNull('Tch_ID')
                    ->where('Tch_ID', '!=', 'TBD');
            })
                ->with(['classrooms' => function ($q) use ($request) {
                    $q->where('Te_Term', $request->session()->get('Term'))
                        ->whereNotNull('Tch_ID')
                        ->where('Tch_ID', '!=', 'TBD')
                        ->with('course')
                        ->with('scheduler');
                }])
                ->orderBy('Tch_L', 'asc')
                ->get();

            $recipients = [];
            foreach ($queryTeachers as $teacher) {
                $recipients[] = $teacher->email;
            }

            if ($selectedTerm->Comments === 'SUMMER') {
                Mail::to($recipients)->send(new EmailSummerClassroomsToTeachers($languages, $queryTeachers, $selectedTerm));
            } else {
                Mail::to($recipients)->send(new EmailClassroomsToTeachers($languages, $queryTeachers, $selectedTerm));
            }


            $request->session()->flash('success', 'Email sent to the teachers.');
            return redirect()->back();
        }

        return view('teachers.teacher_show_classrooms_per_teacher', compact('terms'));
    }

    public function teacherEmailClassroomsToTeachersView(Request $request)
    {
        if ($request->session()->has('Term')) {
            $selectedTerm = Term::orderBy('Term_Code', 'desc')->where('Term_Code', $request->session()->get('Term'))->first();
            $languages = Language::all();

            // get all teachers with a class of the selected term
            $queryTeachers = Teachers::whereHas('classrooms', function ($query) use ($request) {
                $query->where('Te_Term', $request->session()->get('Term'))
                    ->whereNotNull('Tch_ID')
                    ->where('Tch_ID', '!=', 'TBD');
            })
                ->with(['classrooms' => function ($q) use ($request) {
                    $q->where('Te_Term', $request->session()->get('Term'))
                        ->whereNotNull('Tch_ID')
                        ->where('Tch_ID', '!=', 'TBD')
                        ->with('course')
                        ->with('scheduler');
                }])
                ->orderBy('Tch_L', 'asc')
                ->get();

            $recipients = [];
            foreach ($queryTeachers as $teacher) {
                $recipients[] = $teacher->email;
            }

            if ($selectedTerm->Comments === 'SUMMER') {
                return view('emails.emailSummerClassroomsToTeachers', compact('languages', 'queryTeachers', 'selectedTerm'));
            } else {
                return view('emails.emailClassroomsToTeachers', compact('languages', 'queryTeachers', 'selectedTerm'));
            }
        }

        return 'Please set the term first.';
    }

    public function teacherShowClassroomsPerTeacher(Request $request)
    {
        $terms = Term::orderBy('Term_Code', 'desc')->get();

        // get all teachers of the selected term
        $queryTeachers = Classroom::where('Te_Term', $request->session()->get('Term'))->select('Tch_ID')->groupBy('Tch_ID')
            ->whereNotNull('Tch_ID')
            ->where('Tch_ID', '!=', 'TBD')
            ->with('teachers')
            ->get()
            ->sortBy('teachers.Tch_Lastname');
        $teachers = [];
        if ($request->session()->has('Term')) {
            $selectedTerm = Term::orderBy('Term_Code', 'desc')->where('Term_Code', $request->session()->get('Term'))->first();

            foreach ($queryTeachers as $key => $value) {
                $teachers[] = Teachers::where('Tch_ID', $value->Tch_ID)
                    ->with(['classrooms' => function ($query) use ($request) {
                        $query->where('Te_Term', $request->session()->get('Term'))
                            ->whereNotNull('Tch_ID')
                            ->where('Tch_ID', '!=', 'TBD');
                    }])
                    ->get()
                    // ->take(5)
                ;
            }

            // dd($queryTeachers);
            return view('teachers.teacher_show_classrooms_per_teacher', compact('terms', 'selectedTerm', 'teachers'));
        }

        return view('teachers.teacher_show_classrooms_per_teacher', compact('terms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::where('id', '>', 2)->get();
        $cat = DB::table('LTP_Cat')->pluck("Description", "Cat")->all();
        $org = Torgan::get(["Org Full Name", "Org name"]);
        $languages = Language::all();

        return view('teachers.create', compact('roles', 'cat', 'org', 'languages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->decision == 0) {
            //validate the data
            $this->validate($request, array(
                'gender' => 'required|string|',
                'title' => 'required|',
                'profile' => 'required|',
                'nameLast' => 'required|string|max:255',
                'nameFirst' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:tblLTP_New_Users,email',
                'org' => 'required|string|max:255',
                'contact_num' => 'required|max:255',
                'dob' => 'required',
            ));

            //store in database
            $newUser = new NewUser;
            $newUser->gender = $request->gender;
            $newUser->title = $request->title;
            $newUser->profile = $request->profile;
            $newUser->name = $request->nameFirst . ' ' . strtoupper($request->nameLast);
            $newUser->nameLast = strtoupper($request->nameLast);
            $newUser->nameFirst = $request->nameFirst;
            $newUser->email = $request->email;
            $newUser->org = $request->org;
            $newUser->contact_num = $request->contact_num;
            $newUser->dob = $request->dob;
            $newUser->approved_account = 1;
            $newUser->save();

            $ext_index = 'EXT' . $newUser->id;
            $request->merge(['indexno' => $ext_index]);

            $user = $this->teacherWithIndexID($request);
            $user = $this->storeAccountInTeacherTable($request);

            return redirect()->route('manage-user-enrolment-data', $user->id)
                ->with(
                    'flash_message',
                    'User successfully added.'
                );
        }

        // else if decision == 1, then create with index no. 
        $user = $this->teacherWithIndexID($request);
        $user = $this->storeAccountInTeacherTable($request);

        return redirect()->route('manage-user-enrolment-data', $user->id)
            ->with(
                'flash_message',
                'User successfully added.'
            );
    }

    public function teacherWithIndexID($request)
    {
        //Validate name, email and password fields
        $rules_user = [
            'indexno' => 'required|unique:users',
            'nameFirst' => 'required|max:120',
            'nameLast' => 'required|max:120',
            'email' => 'required|email|unique:users',
            // 'password'=>'required|min:6|confirmed'
        ];
        $customMessagesUser = [
            'unique' => 'The :attribute already exists in the Auth Table.'
        ];

        $this->validate($request, $rules_user, $customMessagesUser);

        // if staff exists in sddextr table, copy data to auth table
        $query_sddextr_record = SDDEXTR::where('INDEXNO', $request->indexno)->orWhere('EMAIL', $request->email)->first();

        // if staff does not exist in auth table but index or email exists in sddextr, create auth record and send credentials
        if ($query_sddextr_record) {
            $query_sddextr_record_array = $query_sddextr_record->toArray();

            $validator = Validator::make($query_sddextr_record_array, [
                'INDEXNO' => 'required|unique:users,indexno',
                'INDEXNO_old' => 'required|unique:users,indexno_old',
                'EMAIL' => 'required|email|unique:users,email'
            ]);

            $user = User::create([
                'indexno_old' => $query_sddextr_record->INDEXNO_old,
                'indexno' => $query_sddextr_record->INDEXNO,
                'profile' => $request->profile,
                'email' => strtolower($query_sddextr_record->EMAIL),
                'nameFirst' => $query_sddextr_record->FIRSTNAME,
                'nameLast' => strtoupper($query_sddextr_record->LASTNAME),
                'name' => $query_sddextr_record->FIRSTNAME . ' ' . strtoupper($query_sddextr_record->LASTNAME),
                'password' => Hash::make('Welcome2CLM'),
                'must_change_password' => 1,
                'approved_account' => 1,
            ]);

            return $user;
        }


        // if not in auth table and sddextr table, create
        $user = User::create([
            'indexno' => $request->indexno,
            'indexno_old' => $request->indexno,
            'profile' => $request->profile,
            'email' => strtolower($request->email),
            'nameFirst' => $request->nameFirst,
            'nameLast' => strtoupper($request->nameLast),
            'name' => $request->nameFirst . ' ' . strtoupper($request->nameLast),
            'password' => Hash::make('Welcome2CLM'),
            'must_change_password' => 1,
            'approved_account' => 1,
        ]);

        //Send Auth credentials to student via email
        $sddextr_email_address = $request->email;
        // send credential email to user using email from sddextr 
        // Mail::to($sddextr_email_address)->send(new SendAuthMail($sddextr_email_address));

        $this->validate($request, [
            'indexno' => 'required|unique:SDDEXTR,INDEXNO_old',
            'indexno' => 'required|unique:SDDEXTR,INDEXNO',
            'email' => 'required|unique:SDDEXTR,EMAIL',
        ]);

        $user->sddextr()->create([
            'INDEXNO' => $request->indexno,
            'INDEXNO_old' => $request->indexno,
            'TITLE' => $request->title,
            'FIRSTNAME' => $request->nameFirst,
            'LASTNAME' => strtoupper($request->nameLast),
            'EMAIL' => strtolower($request->email),
            'SEX' => $request->gender,
            'DEPT' => $request->org,
            'PHONE' => $request->contact_num,
            // 'CAT' => $request->cat,
        ]);

        $roles = $request['roles']; //Retrieving the roles field
        //Checking if a role was selected
        if (isset($roles)) {

            foreach ($roles as $role) {
                $role_r = Role::where('id', '=', $role)->firstOrFail();
                $user->assignRole($role_r); //Assigning role to user
            }
        }

        return $user;
    }

    public function storeAccountInTeacherTable($request)
    {
        //Validate name, email and password fields
        $rules_user = [
            'indexno' => 'required|unique:LTP_TEACHERS',
            'nameFirst' => 'required|max:120',
            'nameLast' => 'required|max:120',
            'email' => 'required|email|unique:LTP_TEACHERS',
            // 'password'=>'required|min:6|confirmed'
        ];
        $customMessagesUser = [
            'unique' => 'The :attribute already exists in the Teachers Table.'
        ];

        $this->validate($request, $rules_user, $customMessagesUser);


        //store in Teachers table
        $newTeacher = new Teachers;
        $newTeacher->In_Out = 1;
        $newTeacher->IndexNo = $request->indexno;
        $newTeacher->Tch_Title = $request->title;
        $newTeacher->Tch_Name = strtoupper($request->nameLast) . ', ' . $request->nameFirst;
        $newTeacher->Tch_Lastname = strtoupper($request->nameLast);
        $newTeacher->Tch_Firstname = $request->nameFirst;
        $newTeacher->User_Type = 'Teacher';

        $newTeacher->Tch_L = $request->L;

        $newTeacher->email = $request->email;
        $newTeacher->DoB = $request->dob;
        $newTeacher->sex = $request->gender;
        $newTeacher->Phone = $request->contact_num;

        $firstCharLastName = mb_substr($request->nameLast, 0, 1, "UTF-8");
        $firstCharFirstName = mb_substr($request->nameFirst, 0, 1, "UTF-8");
        $combineChar = $firstCharLastName . $firstCharFirstName;

        $checkTchID = Teachers::where('Tch_ID', $combineChar)->first();

        $b = 1;
        if ($checkTchID) {
            for ($i = 0; $i < $b; $i++) {

                $c = $combineChar . $i;
                $checkTchID2 = Teachers::where('Tch_ID', $c)->first();
                if (!$checkTchID2) {
                    $newTeacher->Tch_ID = $c;
                    $b = $i;
                } else {
                    $b++;
                }
            }
        } else {
            $newTeacher->Tch_ID = $combineChar;
        }


        $newTeacher->save();
        $user = $newTeacher->users;

        return $user;
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

        // change data in the User table and SDDEXTR table
        $user = User::where('indexno', $teacher->IndexNo)->first();
        $sddextr = SDDEXTR::where('INDEXNO', $teacher->IndexNo)->first();

        if ($request->email) {
            $user->update(['email' => strtolower($request->email)]);
            $sddextr->update(['EMAIL' => strtolower($request->email)]);
        }
        if ($request->Tch_Firstname) {
            $user->update(['nameFirst' => $request->Tch_Firstname]);
            $user->update(['name' => $request->Tch_Firstname . ' ' . strtoupper($user->nameLast)]);
            $sddextr->update(['FIRSTNAME' => $request->Tch_Firstname]);
            $teacher->update(['Tch_Name' => strtoupper($teacher->Tch_Lastname) . ', ' . $request->Tch_Firstname]);
        }
        if ($request->Tch_Lastname) {
            $user->update(['nameLast' => strtoupper($request->Tch_Lastname)]);
            $user->update(['name' => $user->nameFirst . ' ' . strtoupper($request->Tch_Lastname)]);
            $sddextr->update(['LASTNAME' => strtoupper($request->Tch_Lastname)]);
            $teacher->update(['Tch_Name' => strtoupper($request->Tch_Lastname) . ', ' . $teacher->Tch_Firstname]);
        }

        $data = $input;
        return response()->json($data);
    }

    public function teacherViewClassrooms(Request $request)
    {
        if (Auth::user()->hasRole('Teacher')) {
            $assigned_classes = Classroom::where('Tch_ID', Auth::user()->teachers->Tch_ID)
                ->where('Te_Term', Session::get('Term'))
                ->get();

            return view('teachers.teacher_view_classrooms', compact('assigned_classes'));
        }

        $request->session()->flash('error', 'Insufficient access rights. You have been redirected.');
        return redirect()->route('home');
    }

    public function teacherViewAllClassrooms()
    {
        $assigned_classes = Classroom::where('L', Auth::user()->teachers->Tch_L)
            // ->where('Tch_ID', '!=', 'TBD')
            ->where('Te_Term', Session::get('Term'))
            ->get()
            ->sortBy('course.level');

        return view('teachers.teacher_view_all_classrooms', compact('assigned_classes'));
    }

    /**
     * Show the students for specific classrooms
     * @param  Request $request get session parameters
     * @return json           html view
     */
    public function teacherShowStudents(Request $request)
    {
        $form_info = Repo::withTrashed()
            ->where('CodeClass', $request->Code)
            ->where('Term', Session::get('Term'))
            ->join('users', 'LTP_PASHQTcur.INDEXID', '=', 'users.indexno')
            ->orderBy('users.nameLast', 'asc')
            ->select('LTP_PASHQTcur.*')
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

    public function teacherShowStudentEmailsOnly(Request $request)
    {
        $form_info = Repo::withTrashed()
            ->where('CodeClass', $request->Code)
            ->where('Term', Session::get('Term'))
            ->join('users', 'LTP_PASHQTcur.INDEXID', '=', 'users.indexno')
            ->orderBy('users.nameLast', 'asc')
            ->select('LTP_PASHQTcur.*')
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

        $data = view('teachers.teacher_show_student_emails_only', compact('course', 'form_info'))->render();
        return response()->json([$data]);
    }

    public function ajaxShowOverallAttendance(Request $request)
    {
        if ($request->ajax()) {
            $qry = Attendance::whereIn('pash_id', $request->id)->get();

            // if no attendance has been entered yet, then 0 value
            if ($qry->isEmpty()) {

                $data = 0;
                return response()->json($data);
            }

            $array_attributes = [];
            foreach ($qry as $key => $value) {
                $arr = $value;
                $array_attributes[] = $arr->getAttributes();
            }

            $sumP = [];
            $sumE = [];
            $sumA = [];
            $info = [];
            $collector = [];
            foreach ($array_attributes as $x => $y) {
                $info['pash_id'] = $y['pash_id'];

                foreach ($y as $k => $v) {
                    if ($v == 'P') {
                        $sumP[] = 'P';
                    }

                    if ($v == 'E') {
                        $sumE[] = 'E';
                    }

                    if ($v == 'A') {
                        $sumA[] = 'A';
                    }
                }

                $info['P'] = count($sumP);
                $info['E'] = count($sumE);
                $info['A'] = count($sumA);

                $collector[] = $info;
                // clear contents of array for the next loop
                $sumP = [];
                $sumE = [];
                $sumA = [];
            }

            $data = $collector;
            return response()->json($data);
        }
    }

    public function ajaxShowIfEnrolledNextTermPlacement(Request $request)
    {
        if ($request->ajax()) {

            $indexid = $request->indexid;

            $selectedTerm = Session::get('Term');
            $lastDigit = substr($selectedTerm, -1);

            if ($lastDigit == 9) {
                $next_term = $selectedTerm + 2;
            }

            if ($lastDigit == 1) {
                $next_term = $selectedTerm + 3;
            }

            if ($lastDigit == 4) {
                $next_term = $selectedTerm + 5;
            }
            if ($lastDigit == 8) {
                $next_term = $selectedTerm + 1;
            }

            $enrolled_next_term_placement = PlacementForm::whereIn('INDEXID', $indexid)
                ->where('Term', $next_term)
                ->with('languages')
                ->get();

            $data = $enrolled_next_term_placement;

            return response()->json($data);
        }
    }

    public function ajaxShowIfEnrolledNextTerm(Request $request)
    {
        if ($request->ajax()) {

            $indexid = $request->indexid;
            $language = $request->L;
            // $next_term = Term::where('Term_Code', Session::get('Term') )->first()->Term_Next;

            $selectedTerm = Session::get('Term'); // No need of type casting
            // echo substr($selectedTerm, 0, 1); // get first value
            // echo substr($selectedTerm, -1); // get last value
            $lastDigit = substr($selectedTerm, -1);

            if ($lastDigit == 9) {
                $next_term = $selectedTerm + 2;
            }

            if ($lastDigit == 1) {
                $next_term = $selectedTerm + 3;
            }

            if ($lastDigit == 4) {
                $next_term = $selectedTerm + 5;
            }
            if ($lastDigit == 8) {
                $next_term = $selectedTerm + 1;
            }

            // $next_term_string = Term::where('Term_Code', $next_term )->first();

            $enrolled_next_term_regular = Preenrolment::whereIn('INDEXID', $indexid)
                ->where('L', $language)
                ->where('Term', $next_term)
                ->select('Te_Code', 'INDEXID')
                ->groupBy('Te_Code', 'INDEXID')
                ->with('courses')
                ->get();

            $data = $enrolled_next_term_regular;

            return response()->json($data);
        }
    }

    public function ajaxCheckIfAssigned(Request $request)
    {
        if ($request->ajax()) {
            $indexid = $request->indexid;
            $language = $request->L;

            $selectedTerm = Session::get('Term'); // No need of type casting
            $lastDigit = substr($selectedTerm, -1);

            if ($lastDigit == 9) {
                $next_term = $selectedTerm + 2;
            }

            if ($lastDigit == 1) {
                $next_term = $selectedTerm + 3;
            }

            if ($lastDigit == 4) {
                $next_term = $selectedTerm + 5;
            }
            if ($lastDigit == 8) {
                $next_term = $selectedTerm + 1;
            }

            $check_if_assigned_regular = Preenrolment::whereIn('INDEXID', $indexid)
                ->where('L', $language)
                ->where('Term', $next_term)
                ->where('updated_by_admin', 1)
                ->select('Te_Code', 'modified_by', 'INDEXID')
                ->groupBy('Te_Code', 'modified_by', 'INDEXID')
                ->with('modifyUser')
                ->get();

            $data = $check_if_assigned_regular;

            return response()->json($data);
        }
    }

    public function ajaxCheckIfNotAssigned(Request $request)
    {

        if ($request->ajax()) {
            $indexid = $request->indexid;
            $language = $request->L;

            $selectedTerm = Session::get('Term'); // No need of type casting
            $lastDigit = substr($selectedTerm, -1);

            if ($lastDigit == 9) {
                $next_term = $selectedTerm + 2;
            }

            if ($lastDigit == 1) {
                $next_term = $selectedTerm + 3;
            }

            if ($lastDigit == 4) {
                $next_term = $selectedTerm + 5;
            }
            if ($lastDigit == 8) {
                $next_term = $selectedTerm + 1;
            }

            $check_if_assigned_regular = Preenrolment::whereIn('INDEXID', $indexid)
                ->where('L', $language)
                ->where('Term', $next_term)
                ->where('updated_by_admin', 0)
                ->select('Te_Code', 'modified_by', 'INDEXID')
                ->groupBy('Te_Code', 'modified_by', 'INDEXID')
                ->with('modifyUser')
                ->get();

            $data = $check_if_assigned_regular;

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
            ->join('users', 'LTP_PASHQTcur.INDEXID', '=', 'users.indexno')
            ->orderBy('users.nameLast', 'asc')
            ->select('LTP_PASHQTcur.*')
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
            if (!Session::has('Term')) {
                $data = 'missingSelectedTerm';
                return response()->json([$data]);
            }

            $indexid = $request->indexid;
            $language = $request->L;
            // $next_term = Term::where('Term_Code', Session::get('Term') )->first()->Term_Next; 

            $selectedTerm = Session::get('Term'); // No need of type casting
            // echo substr($selectedTerm, 0, 1); // get first value
            // echo substr($selectedTerm, -1); // get last value
            $lastDigit = substr($selectedTerm, -1);

            if ($lastDigit == 9) {
                $next_term = $selectedTerm + 2;
            }

            if ($lastDigit == 1) {
                $next_term = $selectedTerm + 3;
            }

            if ($lastDigit == 4) {
                $next_term = $selectedTerm + 5;
            }
            if ($lastDigit == 8) {
                $next_term = $selectedTerm + 1;
            }

            $next_term_string = Term::where('Term_Code', $next_term)->first();

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
                ->select('INDEXID', 'L', 'Term', 'Te_Code', 'eform_submit_count', 'flexibleBtn', 'flexibleDay', 'flexibleTime', 'flexibleFormat', 'modified_by', 'updated_by_admin', 'updatedOn', 'teacher_comments', 'admin_eform_comment', 'std_comments')
                ->groupBy('INDEXID', 'L', 'Term', 'Te_Code', 'eform_submit_count', 'flexibleBtn', 'flexibleDay', 'flexibleTime', 'flexibleFormat', 'modified_by', 'updated_by_admin', 'updatedOn', 'teacher_comments', 'admin_eform_comment', 'std_comments')
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

            $last_placement_test = PlacementForm::orderBy('Term', 'desc')->where('INDEXID', $indexid)->first();
            $history = Repo::orderBy('Term', 'desc')->where('INDEXID', $indexid)->get();

            $data = view('teachers.teacher_assign_course', compact('arr1', 'enrolment_details', 'enrolment_schedules', 'languages', 'org', 'modified_forms', 'last_placement_test', 'history', 'next_term_string'))->render();
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
            $teacher_comments = $request->teacher_comments;

            $enrolment_to_be_copied = Preenrolment::orderBy('id', 'asc')
                ->where('Te_Code', $tecode)
                ->where('INDEXID', $indexno)
                ->where('eform_submit_count', $eform_submit_count)
                ->where('Term', $term)
                ->get();

            $input_1 = ['teacher_comments' => $teacher_comments, 'updated_by_admin' => 1, 'modified_by' => Auth::user()->id];
            $input_1 = array_filter($input_1, 'strlen');

            foreach ($enrolment_to_be_copied as $data) {
                $data->fill($input_1)->save();
            }

            $data = $request->all();

            return response()->json($data);
        }
    }

    public function teacherVerifyAndNotAssign(Request $request)
    {
        if ($request->ajax()) {
            $indexno = $request->qry_indexid;
            $term = $request->qry_term;
            $tecode = $request->qry_tecode;
            $eform_submit_count = $request->eform_submit_count;
            $teacher_comments = $request->teacher_comments;
            $assign_modal = 0;

            $enrolment_to_be_copied = Preenrolment::orderBy('id', 'asc')
                ->where('Te_Code', $tecode)
                ->where('INDEXID', $indexno)
                ->where('eform_submit_count', $eform_submit_count)
                ->where('Term', $term)
                ->get();

            if ($enrolment_to_be_copied->count() > 1) {
                $updated_enrolment_record = $this->verifyAndNotAssignRecords($assign_modal, $enrolment_to_be_copied, $teacher_comments);
                $data = $updated_enrolment_record;
                return response()->json($data);
            }

            $input_1 = ['teacher_comments' => $teacher_comments, 'updated_by_admin' => 0, 'modified_by' => Auth::user()->id];
            $input_1 = array_filter($input_1, 'strlen');

            foreach ($enrolment_to_be_copied as $data) {
                $data->fill($input_1)->save();
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
            $teacher_comments = $request->teacher_comments;

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

            $input_1 = ['teacher_comments' => $teacher_comments, 'updated_by_admin' => 1, 'modified_by' => Auth::user()->id];
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
            ->join('users', 'LTP_PASHQTcur.INDEXID', '=', 'users.indexno')
            ->orderBy('users.nameLast', 'asc')
            ->select('LTP_PASHQTcur.*')
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
        // return view('teachers.teacher_manage_attendance', compact('course', 'form_info', 'classroom','day','time','week'));
        $data = view('teachers.teacher_manage_attendance', compact('course', 'form_info', 'classroom', 'day', 'time', 'week'))->render();
        return response()->json([$data]);
    }

    public function ajaxGetRemark(Request $request)
    {
        $attendance_id_check = Attendance::whereIn('pash_id', explode(",", $request->id))->get();
        $count_attendance_id = $attendance_id_check->count();

        if ($count_attendance_id > 0) {
            // $attendance_id = Attendance::where('pash_id', $request->id)->first()->id;
            // 
            $attendance_id = Attendance::whereIn('pash_id', explode(",", $request->id))->with('attendanceRemarks')->get();
            $data = $attendance_id;

            // foreach ($attendance_id as $key => $value) {
            //     $remark = AttendanceRemarks::where('attendance_id', $attendance_id)
            //         ->where('wk_id', $request->wk)
            //         ->orderBy('created_at', 'desc')
            //         // ->first();
            //         ->get();
            // }

            // if (!empty($remark)) {
            //     $data = $remark->remarks;
            //     return response()->json($data);
            // }

            // $data = '';
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
        // $student_to_update = Repo::whereIn('id', explode(",", $ids))->get();
        $student_to_update = explode(",", $ids);

        $attendance_status = explode(",", $request->attendanceStatus);
        $countAttendanceStatus = count($attendance_status);

        $remarks = explode(",", $request->remarks);

        for ($i = 0; $i < $countAttendanceStatus; $i++) {
            // $data_details[] = $student_to_update[$i]['id'];
            $data_details[] = $student_to_update[$i];

            $data_update = Attendance::where('pash_id', $student_to_update[$i])->get();

            if (count($data_update) > 0) {

                // update record
                $record_update = Attendance::where('pash_id', $student_to_update[$i]);
                $record_update->update([
                    $request->wk => $attendance_status[$i],
                ]);

                $query = Attendance::where('pash_id', $student_to_update[$i])->first();
                if (!empty($remarks[$i])) {

                    $attendance_remark = new AttendanceRemarks;
                    $attendance_remark->attendance_id = $query->id;
                    $attendance_remark->wk_id = $week;
                    $attendance_remark->remarks = $remarks[$i];
                    $attendance_remark->save();
                }
            } else {

                // insert to attendance table
                $record = new Attendance;
                $record->pash_id = $student_to_update[$i];
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
    public function teacherDeleteForm(Request $request)
    {
        if ($request->ajax()) {
            $indexno = $request->qry_indexid;
            $term = $request->qry_term;
            $tecode = $request->qry_tecode;
            $eform_submit_count = $request->eform_submit_count;
            $teacher_comments = $request->teacher_comments;

            $enrolment_to_be_deleted = Preenrolment::orderBy('id', 'asc')
                ->where('Te_Code', $tecode)
                ->where('INDEXID', $indexno)
                ->where('eform_submit_count', $eform_submit_count)
                ->where('Term', $term)
                ->get();

            $input_1 = [
                'teacher_comments' => $teacher_comments,
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

    public function markNoShow(Request $request)
    {
        $pash_record = Repo::find($request->pash_id);
        if ($pash_record) {
            $pash_record->update([
                'no_show' => 1,
                'no_show_by' => Auth::user()->id,

            ]);
            $classroom = $pash_record->classrooms->course->Description;
            $schedule = $pash_record->classrooms->scheduler->name;

            Mail::raw("Student (" . $pash_record->users->name . ") marked as NO-SHOW in " . $classroom . " - " . $schedule . " by " . Auth::user()->name, function ($message) use ($pash_record) {
                $message->from('clm_language@unog.ch', 'CLM Online [Do Not Reply]');
                $message->to(['clm_language@un.org'])->subject('Student (' . $pash_record->users->name . ') marked as NO-SHOW');
            });
            $data = $pash_record;

            return response()->json($data);
        }

        return response()->json("no id selected");
    }

    public function undoNoShow(Request $request)
    {
        $pash_record = Repo::find($request->pash_id);
        $pash_record->update([
            'no_show' => 0
        ]);
        $data = $pash_record;

        return response()->json($data);
    }
}
