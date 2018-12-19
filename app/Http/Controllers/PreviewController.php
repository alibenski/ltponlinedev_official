<?php

namespace App\Http\Controllers;

use App\Classroom;
use App\Course;
use App\CourseSchedule;
use App\Day;
use App\Language;
use App\Mail\MailPlacementTesttoApprover;
use App\Mail\MailaboutCancel;
use App\Mail\MailtoApprover;
use App\Mail\cancelConvocation;
use App\Mail\sendConvocation;
use App\ModifiedForms;
use App\PlacementForm;
use App\PlacementSchedule;
use App\Preenrolment;
use App\Preview;
use App\PreviewTempSort;
use App\Repo;
use App\SDDEXTR;
use App\Schedule;
use App\Teachers;
use App\Term;
use App\Torgan;
use App\User;
use App\Waitlist;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Session;

class PreviewController extends Controller
{
    public function cancelledConvocaitonView()
    {
        $cancelled_convocations = Preview::onlyTrashed()->orderBy('UpdatedOn', 'asc')->get();

        return view('admin.cancelled-convocation-view', compact('cancelled_convocations'));
    }

    public function cancelConvocation($codeindexidclass)
    {
        $record = Preview::where('CodeIndexIDClass', $codeindexidclass)->first();
        $staff_name = $record->users->name;
        $display_language_en = $record->courses->EDescription;
        $display_language_fr = $record->courses->FDescription;
        $schedule = $record->schedules->name;
        $std_email = $record->users->email;

        Mail::to($std_email)->send(new cancelConvocation($staff_name, $display_language_fr, $display_language_en, $schedule));

        $record_delete = Preview::where('CodeIndexIDClass', $codeindexidclass)->delete();
        
        session()->flash('cancel_success', 'Your enrolment has been successfully cancelled. ');
        return back();
    }

    public function previewWaitlisted()
    {
        $languages = DB::table('languages')->pluck("name","code")->all();

        $convocation_waitlist = new Preview;
        // $currentQueries = \Request::query();
        $queries = [];

        $columns = [
            'L', 
        ];

        
        foreach ($columns as $column) {
            if (\Request::has($column)) {
                $convocation_waitlist = $convocation_waitlist->where($column, \Request::input($column) );
                $queries[$column] = \Request::input($column);
            }

        } 
            if (Session::has('Term')) {
                    $convocation_waitlist = $convocation_waitlist->where('Term', Session::get('Term') );
                    $queries['Term'] = Session::get('Term');
            }

        $convocation_waitlist = $convocation_waitlist->whereHas('classrooms', function ($query) {
                    $query->whereNull('Tch_ID')
                            ->orWhere('Tch_ID', '=', 'TBD')
                            ;
                    })
                    ->get();

        return view('preview-waitlisted', compact('convocation_waitlist', 'languages'));
    }
    /**
     * sends convocation emails
     * @return \Illuminate\Http\Response reroute to admin dashboard
     */
    public function sendConvocation()
    {
        $convocation_all = Preview::all();
        // with('classrooms')->get()->pluck('classrooms.Code', 'CodeIndexIDClass');
        
        // query students who will be put in waitlist
        $convocation_waitlist = Preview::whereHas('classrooms', function ($query) {
                    $query->whereNull('Tch_ID')
                            ->orWhere('Tch_ID', '=', 'TBD')
                            ;
                    })
                    ->get();

        // query students who will receive convocation
        $convocation = Preview::whereHas('classrooms', function ($query) {
                    $query->whereNotNull('Tch_ID')
                            ->orWhere('Tch_ID', '!=', 'TBD')
                            ;
                    })
                    ->where('Te_Code','!=','F3R2')
                    ->get();


        $convocation_diff = $convocation_all->diff($convocation);
        $convocation_diff2 = $convocation_waitlist->diff($convocation_diff);
        $convocation_diff3 = $convocation->diff($convocation_waitlist); // send email convocation to this collection

        // $cours3 = Preview::where('Te_Code','=','F3R2')->get();

        // dd($cours3,$convocation_all, $convocation_waitlist, $convocation, $convocation_diff,$convocation_diff2,$convocation_diff3);
        
        $convocation_diff3 = $convocation_diff3->where('convocation_email_sent', null);
        // $convocation_diff3 = $convocation_diff3->where('INDEXID', '17942');

        foreach ($convocation_diff3 as $value) {
            
            $course_name = Course::where('Te_code_New', $value->Te_Code)->first(); 
            $course_name_en = $course_name->EDescription; 
            $course_name_fr = $course_name->FDescription; 

            $schedule = $value->schedules->name; 
            // $room = $value->CodeClass; 
            // get schedule and room details from classroom table
            $classrooms = Classroom::where('Code', $value->CodeClass)->get();


            $teacher = $value->classrooms->Tch_ID;
            $teacher = Teachers::where('Tch_ID', $teacher)->first()->Tch_Name;

            // get term values
            $term = $value->Term;
            // get term values and convert to strings
            $term_en = Term::where('Term_Code', $term)->first()->Term_Name;
            $term_fr = Term::where('Term_Code', $term)->first()->Term_Name_Fr;
            
            $term_season_en = Term::where('Term_Code', $term)->first()->Comments;
            $term_season_fr = Term::where('Term_Code', $term)->first()->Comments_fr;

            $term_date_time = Term::where('Term_Code', $term)->first()->Term_Begin;
            $term_year = new Carbon($term_date_time);
            $term_year = $term_year->year;

            $staff_name = $value->users->name; 
            $staff_email = $value->users->email;
            
            Mail::to($staff_email)->send(new sendConvocation($staff_name, $course_name_en, $course_name_fr, $classrooms, $teacher, $term_en, $term_fr, $schedule, $term_season_en, $term_season_fr, $term_year));

            $convocation_email_sent = Preview::where('CodeIndexIDClass', $value->CodeIndexIDClass)->update([
                        'convocation_email_sent' => 1,
                        ]);
        }
        
        return count($convocation_diff3);
    }

    public function sendIndividualConvocation(Request $request)
    {
        if($request->ajax()){
            $select_student =  Preview::where('CodeIndexIDClass', $request->CodeIndexIDClass)->first();
                
                $course_name = Course::where('Te_code_New', $select_student->Te_Code)->first(); 
                $course_name_en = $course_name->EDescription; 
                $course_name_fr = $course_name->FDescription; 

                $schedule = $select_student->schedules->name; 

                $classrooms = Classroom::where('Code', $select_student->CodeClass)->get();


                $teacher = $select_student->classrooms->Tch_ID;
                $teacher = Teachers::where('Tch_ID', $teacher)->first()->Tch_Name;

                // get term values
                $term = $select_student->Term;
                // get term values and convert to strings
                $term_en = Term::where('Term_Code', $term)->first()->Term_Name;
                $term_fr = Term::where('Term_Code', $term)->first()->Term_Name_Fr;
                
                $term_season_en = Term::where('Term_Code', $term)->first()->Comments;
                $term_season_fr = Term::where('Term_Code', $term)->first()->Comments_fr;

                $term_date_time = Term::where('Term_Code', $term)->first()->Term_Begin;
                $term_year = new Carbon($term_date_time);
                $term_year = $term_year->year;

                $staff_name = $select_student->users->name; 
                $staff_email = $select_student->users->email;
                
            Mail::to($staff_email)->send(new sendConvocation($staff_name, $course_name_en, $course_name_fr, $classrooms, $teacher, $term_en, $term_fr, $schedule, $term_season_en, $term_season_fr, $term_year));

            $convocation_email_sent = Preview::where('CodeIndexIDClass', $select_student->CodeIndexIDClass)->update([
                    'convocation_email_sent' => 1,
                ]);

            $data = 'success';
            return response()->json([$data]);
            
        }
        $data = 'fail';
        return response()->json([$data]);
    }

    /**
     * @param  Request
     * @return \Illuminate\Http\Response
     */
    public function ajaxGetPriority(Request $request)
    {
        // get previous term
        $selectedTerm = $request->Term; // No need of type casting
        // echo substr($selectedTerm, 0, 1); // get first value
        // echo substr($selectedTerm, -1); // get last value
        $lastDigit = substr($selectedTerm, -1);

        if ($lastDigit == 9) {
            $prev_term = $selectedTerm - 5;
        }
        // if last digit is 1, check Term table for previous term value or subtract 2 from selectedTerm value
        if ($lastDigit == 1) {
            $prev_term = $selectedTerm - 2;
        }
        // if last digit is 4, check Term table for previous term value or subtract 3 from selectedTerm value
        if ($lastDigit == 4) {
            $prev_term = $selectedTerm - 3;
        }
        if ($lastDigit == 8) {
            $prev_term = $selectedTerm - 4;
        }

        // check if re-enrolled student or not
        $student_reenrolled = Repo::where('Term', $prev_term)
                ->where('L', $request->L)
                ->where('INDEXID', $request->INDEXID)
                ->count();

        $priority_status = Preview::withTrashed()->where('CodeIndexID', $request->CodeIndexID)->first();
        // $data = $priority_status->PS;
        if ($priority_status->PS == 1) {
            $data = '1: Re-enrolment';
        } 
        if ($priority_status->PS == 2) {
            $data = '2: In Waitlist';
        }
        if ($priority_status->PS == 3){
            $data = '3: Within 2 Terms/Not Re-enrolment';
        }
        if ($priority_status->PS == 4) {
            $data = '4: Placement Forms/Others';
        }

        return response()->json($data);
    }

    public function ajaxPreview(Request $request)
    {
        $form_info_arr = [];
        $student = Preview::where('Te_Code', $request->Te_Code)->where('schedule_id', $request->schedule_id)->get();
        
        foreach ($student as $value) {
            $form = Preview::orderBy('created_at', 'asc')->where('CodeIndexID', $value->CodeIndexID)
                ->get();
                foreach ($form as $value) {
                    $form_info_arr[] = $value;
                }
        }
        $form_info = collect($form_info_arr)->sortBy('id');

        $data = view('preview-ajax', compact('student', 'form_info'))->render();
        return response()->json([$data]);
    }


    public function previewClassrooms($code)
    {
        $classrooms = Classroom::where('cs_unique', $code)->get();

        $arr = [];
        $classrooms_2 = Classroom::where('cs_unique', $code)->select('Code')->groupBy('Code')->get();

            foreach ($classrooms_2 as $class) {
                $students = Preview::where('CodeClass', $class->Code)->orderBy('PS', 'asc')->get();
                foreach ($students as $value) {
                    $arr[] = $value;
                }
            }

        $classroom_3 = Classroom::where('cs_unique', $code)->first();

        $form_info_arr = [];
        $student = Preview::withTrashed()->where('Te_Code', $classroom_3->Te_Code_New)->where('schedule_id', $classroom_3->schedule_id)->get();
        

        foreach ($student as $value) {
            $form = Preview::withTrashed()->orderBy('created_at', 'asc')
                ->where('CodeIndexID', $value->CodeIndexID)
                ->get();
                foreach ($form as $value) {
                    $form_info_arr[] = $value;
                }
        }
        $form_info = collect($form_info_arr)->sortBy('PS');

        return view('preview-classrooms', compact('arr','classrooms','form_info', 'classroom_3'));
    }

    public function ajaxMoveStudentsForm(Request $request)
    {
        if ($request->ajax()) {
            $ids = $request->ids;
            $student_to_move = Preview::whereIn('id',explode(",",$ids))->get();
            $languages = DB::table('languages')->pluck("name","code")->all();

            $data = view('preview-move-students-form', compact('student_to_move', 'languages'))->render();
            return response()->json([$data]);
        }
    }

    public function ajaxMoveStudents(Request $request)
    {   
        if ($request->ajax()) {
            $ids = $request->ids;
            // get classroom details from teventcur table via $request->classroom_id
            $classroom_details = Classroom::where('Code', $request->classroom_id)->first();
            
            
            $data_details = [];
            $student_to_move = Preview::whereIn('id',explode(",",$ids))->get();

            foreach ($student_to_move as $value) {
                $data_details[] = $value['id'];

                $data_update = Preview::find($value['id']);
                $data_update->update([
                    'CodeIndexIDClass' => $request->classroom_id.'-'.$value['INDEXID'],
                    'CodeClass' => $request->classroom_id,
                    'CodeIndexID' => $classroom_details->cs_unique.'-'.$value['INDEXID'],
                    'Code' => $classroom_details->cs_unique,
                    'schedule_id' => $classroom_details->schedule_id,
                    'Te_Code' => $classroom_details->Te_Code_New,
                    'L' => $classroom_details->L,
                    'Term' => $classroom_details->Te_Term,
                    'convocation_email_sent' => null,
                ]); 
            }

            $data = $data_details;
            return response()->json(['success'=>"Student(s) moved successfully. Click OK to refresh the page."]);
        }
    }

    public function ajaxSelectClassroom(Request $request)
    {
        if ($request->ajax()) {
            $collection = Classroom::where('Te_Code_New', $request->course_id)->where('Te_Term', $request->term_id )->get();

            $data = view('ajax-select5',compact('collection'))->render();
            return response()->json(['options'=>$data]);
        }
    }

    public function ajaxPreviewModal(Request $request)
    {
        if($request->ajax()){
            $current_user = $request->indexno;
            $term_code = $request->term;

            $user = User::where('indexno', $current_user)->first();
            $user = $user->name;

            // check the original wishlist of student in placement forms table
            $check_placement_forms = PlacementForm::where('INDEXID', $current_user)
                ->where('Te_Code', $request->tecode)
                ->where('Term', $term_code)->count();

            if ($check_placement_forms > 0) {
                // query submitted forms based from Modified Forms table
                $schedules = PlacementForm::withTrashed()
                    ->where('Te_Code', $request->tecode)
                    ->where('INDEXID', $current_user)
                    ->where('form_counter', $request->form_counter)
                    ->where('Term', $term_code)
                    ->get(['schedule_id', 'mgr_email', 'approval', 'approval_hr', 'is_self_pay_form', 'DEPT', 'deleted_at', 'INDEXID', 'Term','Te_Code', 'selfpay_approval', 'assigned_to_course' ]);

                $query = PlacementForm::withTrashed()
                    ->where('INDEXID', $current_user)
                    ->where('Term', $term_code)
                    ->where('Te_Code', $request->tecode)
                    ->where('form_counter', $request->form_counter)
                    ->groupBy(['Te_Code', 'Term', 'INDEXID','form_counter', 'deleted_at'])
                    ->get(['Te_Code', 'Term', 'INDEXID', 'form_counter', 'deleted_at']);

                // render and return data values via AJAX
                $data = view('ajax-preview-modal', compact('schedules', 'query', 'user'))->render();
                return response()->json([$data]);
            }

            // check the original wishlist of student in modified forms table first 
            $check_modified_forms = ModifiedForms::where('INDEXID', $current_user)->where('Te_Code', $request->tecode)->where('Term', $term_code)->count();

            if ($check_modified_forms > 0) {
                // query submitted forms based from Modified Forms table
                $schedules = ModifiedForms::withTrashed()
                    // ->where('Te_Code', $request->tecode)
                    ->where('INDEXID', $current_user)
                    ->where('form_counter', $request->form_counter)
                    ->where('Term', $term_code)
                    ->get(['schedule_id', 'mgr_email', 'approval', 'approval_hr', 'is_self_pay_form', 'DEPT', 'deleted_at', 'INDEXID', 'Term','Te_Code', 'selfpay_approval' ]);

                $query = ModifiedForms::withTrashed()
                    ->where('INDEXID', $current_user)
                    ->where('Term', $term_code)
                    // ->where('Te_Code', $request->tecode)
                    ->where('form_counter', $request->form_counter)
                    ->groupBy(['Te_Code', 'Term', 'INDEXID','form_counter', 'deleted_at'])
                    ->get(['Te_Code', 'Term', 'INDEXID', 'form_counter', 'deleted_at']);

                // render and return data values via AJAX
                $data = view('ajax-preview-modal', compact('schedules', 'query', 'user'))->render();
                return response()->json([$data]);
            }

            // query submitted forms based from tblLTP_Enrolment table
            $schedules = Preenrolment::withTrashed()
                ->where('Te_Code', $request->tecode)
                ->where('INDEXID', $current_user)
                ->where('form_counter', $request->form_counter)
                ->where('Term', $term_code)
                ->get(['schedule_id', 'mgr_email', 'approval', 'approval_hr', 'is_self_pay_form', 'DEPT', 'deleted_at', 'INDEXID', 'Term','Te_Code', 'selfpay_approval' ]);

            $query = Preenrolment::withTrashed()->where('INDEXID', $current_user)
                ->where('Term', $term_code)
                ->where('Te_Code', $request->tecode)
                ->where('form_counter', $request->form_counter)
                ->groupBy(['Te_Code', 'Term', 'INDEXID','form_counter', 'deleted_at'])
                ->get(['Te_Code', 'Term', 'INDEXID', 'form_counter', 'deleted_at']);

            // render and return data values via AJAX
            $data = view('ajax-preview-modal', compact('schedules', 'query', 'user'))->render();
            return response()->json([$data]);
            
        }
    }

    public function vsaPage2()
    {
        $languages = DB::table('languages')->pluck("name","code")->all();
        $term = PreviewTempSort::orderBy('id', 'desc')->first();
        return view('preview-course-2')->withLanguages($languages)->withTerm($term);
    }

	public function previewCourse3(Request $request)
    {
    	$preview = Preview::where('Te_Code', $request->course_id)->select(['schedule_id', 'Code'])->groupBy(['schedule_id', 'Code'])->get(['schedule_id', 'Code']);
    	
    	$preview_course = Preview::where('Te_Code', $request->course_id)->first();
        if (empty($preview_course)) {
            $request->session()->flash('interdire-msg', 'No students assigned to this Course');
            return back();
        }
    	$arr_key =[];
        $arr_count = [];
        $code = Preview::where('Te_Code', $request->course_id)->select(['schedule_id', 'Code'])->groupBy(['schedule_id', 'Code'])->get(['schedule_id', 'Code']);

	        foreach ($code as $key => $value) {
	            $arr_key[] = $value->schedules->name;
	            // var_dump($value->schedule_id);
	            // var_dump($value->Code);
	            $count_enrolment_forms = Preview::where('Te_Code', $request->course_id)->where('Code', $value->Code)->where('schedule_id', $value->schedule_id)->count();
	            $arr_count[] = $count_enrolment_forms;
	            $arr_count = array_combine($arr_key, $arr_count);
	            // var_dump($count_enrolment_forms);
	        }
            
    	return view('preview-course-3')->withPreview($preview)
    		->withPreview_course($preview_course)
    		->withArr_count($arr_count);
    }

    public function vsaPage1()
    {
    	DB::table('tblLTP_preview_TempSort')->truncate();
        $terms = Term::orderBy('Term_Code', 'desc')->get();
        return view('preview-course')->withTerms($terms);
    }


	public function getApprovedEnrolmentForms(Request $request)
    {

        // sort enrolment forms by date of submission
        $approved_0_1_collect = Preenrolment::whereIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])->where('Term', $request->Term)->where('approval','1')->orderBy('created_at', 'asc')->get();
        
        $approved_0_1 = Preenrolment::select('INDEXID')->whereIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])->where('Term', $request->Term)->where('approval','1')->orderBy('created_at', 'asc')->get();
        // apply unique() method to remove dupes 
        // apply values() method to reset key series of the array 
        $approved_1 = $approved_0_1->unique('INDEXID')->values()->all(); // becomes an array

        $approved_0_2_collect = Preenrolment::whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])->where('Term', $request->Term)->where('approval','1')->where('approval_hr', '1')->orderBy('created_at', 'asc')->get();
        
        $approved_0_2 = Preenrolment::select('INDEXID')->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])->where('Term', $request->Term)->where('approval','1')->where('approval_hr', '1')->orderBy('created_at', 'asc')->get();
        $approved_2 = $approved_0_2->unique('INDEXID')->values()->all();

        // !!!!!! add where selfpay_approval == 1 !!!!!!
        $approved_0_3_collect = Preenrolment::where('selfpay_approval','1')->whereNotNull('is_self_pay_form')->where('Term', $request->Term)->orderBy('created_at', 'asc')->get();
        
        $approved_0_3 = Preenrolment::select('INDEXID')->where('selfpay_approval','1')->whereNotNull('is_self_pay_form')->where('Term', $request->Term)->orderBy('created_at', 'asc')->get();
        $approved_3 = $approved_0_3->unique('INDEXID')->values()->all(); 
        

        $approved_collections = collect($approved_0_1_collect)->merge($approved_0_2_collect)->merge($approved_0_3_collect)->sortBy('created_at'); // merge collections with sorting by submission date and time
        $approved_collections = $approved_collections->unique('INDEXID')->values()->all(); 
        
        // merge collections but without sorting
        // $approved_all = array_merge($approved_1,$approved_2,$approved_3);
        
        /*
        Priority 1 re-enrolled students and Priority 2 waitlisted students
         */
        $arrCollect = [];
        // foreach ($approved_collections as $value) {
        // 	// $arrCollect[] = $value->INDEXID;
        // 	$arrCollect[] = $value->L;
        // }
        
        // logic to get previous Term of current/existing Term
        // 9 is 4, 1 is 9, 4 is 1
        // if last digit value is 9, subtract 5 from selectedTerm value
        $selectedTerm = $request->Term; // No need of type casting
        // echo substr($selectedTerm, 0, 1); // get first value
        // echo substr($selectedTerm, -1); // get last value
        $lastDigit = substr($selectedTerm, -1);

        if ($lastDigit == 9) {
            $prev_term = $selectedTerm - 5;
            // dd($term);
        }
        // if last digit is 1, check Term table for previous term value or subtract 2 from selectedTerm value
        if ($lastDigit == 1) {
            $prev_term = $selectedTerm - 2;
        }
        // if last digit is 4, check Term table for previous term value or subtract 3 from selectedTerm value
        if ($lastDigit == 4) {
            $prev_term = $selectedTerm - 3;
        }
        if ($lastDigit == 8) {
            $prev_term = $selectedTerm - 4;
        }

        $arrINDEXID = [];
        $arrL = [];
        $arrStudentReEnrolled = [];
        $arrValue = [];
        $countApprovedCollections = count($approved_collections);
        for ($i=0; $i < $countApprovedCollections; $i++) { 
            $arrINDEXID[] = $approved_collections[$i]['INDEXID'];
            $arrL[] = $approved_collections[$i]['L'];
            // echo $i. " - " .$arrINDEXID[$i] ;
            // echo "<br>";
        
            // priority 1: check each index id if they are already in re-enroling students from previous term via PASHQTcur table
            $student_reenrolled = Repo::select('INDEXID')
            	->where('Term', $prev_term)
            	->where('L', $arrL[$i])
            	->where('INDEXID', $arrINDEXID[$i])
            	->groupBy('INDEXID')
            	->get()->toArray();

            $arrStudentReEnrolled[] = $student_reenrolled;
            $student_reenrolled_filtered = array_filter($student_reenrolled);

            // iterate to get the index id of staff who are re-enroling
            foreach($student_reenrolled_filtered as $item) {
                // to know what's in $item
                // echo '<pre>'; var_dump($item);
                foreach ($item as $value) {
                    $arrValue[] = $value; // store the reenrolled INDEXID values in array
                    // echo $value['INDEXID'];
                    // echo "<br>";
                    // echo '<pre>'; var_dump($value['INDEXID']);
                }
            }
        }

        $arr_enrolment_forms_reenrolled = [];
        $ingredients = []; 
        $countArrValue = count($arrValue);
        for ($i=0; $i < $countArrValue; $i++) {
        	// collect priority 1 enrolment forms 
            $enrolment_forms_reenrolled = Preenrolment::where('Term', $request->Term)->where('INDEXID', $arrValue[$i])->orderBy('created_at', 'asc')->get();
            // $enrolment_forms_reenrolled = $enrolment_forms_reenrolled->unique('INDEXID')->values()->all();
            $arr_enrolment_forms_reenrolled[] = $enrolment_forms_reenrolled;

            // assigning of students to classes and saved in Preview TempSort table
            foreach ($enrolment_forms_reenrolled as $value) {
                $ingredients[] = new  PreviewTempSort([
                'CodeIndexID' => $value->CodeIndexID,
                'Code' => $value->Code,
                'schedule_id' => $value->schedule_id,
                'L' => $value->L,
                'profile' => $value->profile,
                'Te_Code' => $value->Te_Code,
                'Term' => $value->Term,
                'INDEXID' => $value->INDEXID,
                "created_at" =>  $value->created_at,
                "UpdatedOn" =>  $value->UpdatedOn,
                'mgr_email' =>  $value->mgr_email,
                'mgr_lname' => $value->mgr_lname,
                'mgr_fname' => $value->mgr_fname,
                'continue_bool' => $value->continue_bool,
                'DEPT' => $value->DEPT, 
                'eform_submit_count' => $value->eform_submit_count,              
                'form_counter' => $value->form_counter,  
                'agreementBtn' => $value->agreementBtn,
                'flexibleBtn' => $value->flexibleBtn,
                'PS' => 1,
                ]); 
                    foreach ($ingredients as $data) {
                        $data->save();
                    }     
            }   
        }

        /**
         * Priority 2 query enrolment forms/placement forms and check if they exist in waitlist table of 
         * 2 previous terms
         */
        $arrPriority2 = [];
        $ingredients2 = [];
        $arrValue2 = [];

        $priority2_not_reset = array_diff($arrINDEXID,$arrValue); // get the difference of INDEXID's between reenrolled and others

        $priority2 = array_values($priority2_not_reset) ;
        $countPriority2 = count($priority2);    
        for ($y=0; $y < $countPriority2; $y++) { 
            $waitlist_indexids = Waitlist::where('INDEXID', $priority2[$y])->select('INDEXID')->groupBy('INDEXID')->get()->toArray(); 
            $arrPriority2[] = $waitlist_indexids;
            $arrPriority2_filtered = array_filter($arrPriority2);

            // iterate to get the index id of staff who are waitlisted
            foreach($waitlist_indexids as $item2) {
                foreach ($item2 as $value2) {
                    $arrValue2[] = $value2; // store the waitlisted INDEXID values in array
                }
            }
        }

        $arr_enrolment_forms_waitlisted = [];
        $ingredients2 = []; 
        $countArrValue2 = count($arrValue2);

        for ($z=0; $z < $countArrValue2; $z++) {
            // collect priority 2 enrolment forms 
            $enrolment_forms_waitlisted = Preenrolment::where('Term', $request->Term)->where('INDEXID', $arrValue2[$z])->orderBy('created_at', 'asc')->get();

            $arr_enrolment_forms_waitlisted[] = $enrolment_forms_waitlisted;

            // assigning of students to classes and saved in Preview TempSort table
            foreach ($enrolment_forms_waitlisted as $value) {
                $ingredients2[] = new  PreviewTempSort([
                'CodeIndexID' => $value->CodeIndexID,
                'Code' => $value->Code,
                'schedule_id' => $value->schedule_id,
                'L' => $value->L,
                'profile' => $value->profile,
                'Te_Code' => $value->Te_Code,
                'Term' => $value->Term,
                'INDEXID' => $value->INDEXID,
                "created_at" =>  $value->created_at,
                "UpdatedOn" =>  $value->UpdatedOn,
                'mgr_email' =>  $value->mgr_email,
                'mgr_lname' => $value->mgr_lname,
                'mgr_fname' => $value->mgr_fname,
                'continue_bool' => $value->continue_bool,
                'DEPT' => $value->DEPT, 
                'eform_submit_count' => $value->eform_submit_count,              
                'form_counter' => $value->form_counter,  
                'agreementBtn' => $value->agreementBtn,
                'flexibleBtn' => $value->flexibleBtn,
                'PS' => 2,
                ]); 
                    foreach ($ingredients2 as $data2) {
                        $data2->save();
                    }     
            }   
        }

        /**
         * Get all approved/ validated placement forms 
         * and compare to waitlist table to set priority 2
         */
        // sort enrolment forms by date of submission
        $approved_0_1_collect_placement = PlacementForm::whereNotNull('CodeIndexID')->whereIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])->where('Term', $request->Term)->where('approval','1')->orderBy('created_at', 'asc')->get();

        $approved_0_1_placement = PlacementForm::select('INDEXID')->whereNotNull('CodeIndexID')->whereIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])->where('Term', $request->Term)->where('approval','1')->orderBy('created_at', 'asc')->get();
        // apply unique() method to remove dupes 
        // apply values() method to reset key series of the array 
        $approved_1_placement = $approved_0_1_placement->unique('INDEXID')->values()->all(); // becomes an array

        $approved_0_2_collect_placement = PlacementForm::whereNotNull('CodeIndexID')->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])->where('Term', $request->Term)->where('approval','1')->where('approval_hr', '1')->orderBy('created_at', 'asc')->get();
        
        $approved_0_2_placement = PlacementForm::select('INDEXID')->whereNotNull('CodeIndexID')->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])->where('Term', $request->Term)->where('approval','1')->where('approval_hr', '1')->orderBy('created_at', 'asc')->get();
        $approved_2_placement = $approved_0_2_placement->unique('INDEXID')->values()->all();

        // !!!!!! add where selfpay_approval == 1 !!!!!!
        $approved_0_3_collect_placement = PlacementForm::whereNotNull('CodeIndexID')->where('selfpay_approval','1')->whereNotNull('is_self_pay_form')->where('Term', $request->Term)->orderBy('created_at', 'asc')->get();
        
        $approved_0_3_placement = PlacementForm::select('INDEXID')->whereNotNull('CodeIndexID')->where('selfpay_approval','1')->whereNotNull('is_self_pay_form')->where('Term', $request->Term)->orderBy('created_at', 'asc')->get();
        $approved_3_placement = $approved_0_3_placement->unique('INDEXID')->values()->all(); 
        

        $approved_collections_placement = collect($approved_0_1_collect_placement)->merge($approved_0_2_collect_placement)->merge($approved_0_3_collect_placement)->sortBy('created_at'); // merge collections with sorting by submission date and time
        // unique query should not be implemented to separate enrolments to different language course and schedule in placement test forms
        $approved_collections_placement = $approved_collections_placement
                                            ->unique('INDEXID')
                                            ->values()->all();

        $arrINDEXIDPlacement = [];
        $arrLPlacement = [];
        $arrWaitlistedIndexPlacement = [];
        $arrValuePlacement = [];
        $countApprovedCollectionsPlacement = count($approved_collections_placement);

        for ($p=0; $p < $countApprovedCollectionsPlacement; $p++) { 
            $arrINDEXIDPlacement[] = $approved_collections_placement[$p]['INDEXID'];
        
            // placement forms priority 2: check each index id if they are in the waitlist table
            $waitlist_indexids_placement = Waitlist::where('INDEXID', $arrINDEXIDPlacement[$p])->select('INDEXID')->groupBy('INDEXID')->get()->toArray(); 

            $arrWaitlistedIndexPlacement[] = $waitlist_indexids_placement;
            $waitlist_indexids_placement_filtered = array_filter($waitlist_indexids_placement);

            // iterate to get the index id of staff who are waitlisted
            foreach($waitlist_indexids_placement_filtered as $item_placement) {
                foreach ($item_placement as $value_placement) {
                    $arrValuePlacement[] = $value_placement; // store the waitlisted placement INDEXID values in array
                }
            }
        }

        $arr_placement_forms_waitlisted = [];
        $placement_ingredients2 = []; 
        $countArrValuePlacement = count($arrValuePlacement);

        for ($h=0; $h < $countArrValuePlacement; $h++) {
            // collect priority 2 placement forms 
            $placement_forms_waitlisted = PlacementForm::whereNotNull('CodeIndexID')->where('Term', $request->Term)->where('INDEXID', $arrValuePlacement[$h])->orderBy('created_at', 'asc')->get();

            $arr_placement_forms_waitlisted[] = $placement_forms_waitlisted;

            // assigning of students to classes and saved in Preview TempSort table
            foreach ($placement_forms_waitlisted as $value_placement) {
                $placement_ingredients2[] = new  PreviewTempSort([
                'CodeIndexID' => $value_placement->CodeIndexID,
                'Code' => $value_placement->Code,
                'schedule_id' => $value_placement->schedule_id,
                'L' => $value_placement->L,
                'profile' => $value_placement->profile,
                'Te_Code' => $value_placement->Te_Code,
                'Term' => $value_placement->Term,
                'INDEXID' => $value_placement->INDEXID,
                "created_at" =>  $value_placement->created_at,
                "UpdatedOn" =>  $value_placement->UpdatedOn,
                'mgr_email' =>  $value_placement->mgr_email,
                'mgr_lname' => $value_placement->mgr_lname,
                'mgr_fname' => $value_placement->mgr_fname,
                'continue_bool' => $value_placement->continue_bool,
                'DEPT' => $value_placement->DEPT, 
                'eform_submit_count' => $value_placement->eform_submit_count,              
                'form_counter' => $value_placement->form_counter,  
                'agreementBtn' => $value_placement->agreementBtn,
                'flexibleBtn' => $value_placement->flexibleBtn,
                'PS' => 2,
                ]); 
                    foreach ($placement_ingredients2 as $placement_data2) {
                        $placement_data2->save();
                    }     
            }   
        }

        $arrValue1_2 = [];
        $arrValue1_2 = array_merge($arrValue, $arrValue2);

        /**
         * Priority 3
         * [$arrPriority3 description]
         * @var array
         */
        $arrPriority3 = [];
        $ingredients3 = [];
        // get the INDEXID's which are not existing in priority 1 & 2
        $priority3_not_reset = array_diff($arrINDEXID,$arrValue1_2);
        $priority3 = array_values($priority3_not_reset) ;
        $countPriority3 = count($priority3);

        for ($i=0; $i < $countPriority3; $i++) {
            // collect priority 3 enrolment forms 
            $enrolment_forms_priority3 = Preenrolment::where('Term', $request->Term)->where('INDEXID', $priority3[$i])->orderBy('created_at', 'asc')->get();
            $arrPriority3[] = $enrolment_forms_priority3 ;

            foreach ($enrolment_forms_priority3 as $value) {
                $ingredients3[] = new  PreviewTempSort([
                'CodeIndexID' => $value->CodeIndexID,
                'Code' => $value->Code,
                'schedule_id' => $value->schedule_id,
                'L' => $value->L,
                'profile' => $value->profile,
                'Te_Code' => $value->Te_Code,
                'Term' => $value->Term,
                'INDEXID' => $value->INDEXID,
                "created_at" =>  $value->created_at,
                "UpdatedOn" =>  $value->UpdatedOn,
                'mgr_email' =>  $value->mgr_email,
                'mgr_lname' => $value->mgr_lname,
                'mgr_fname' => $value->mgr_fname,
                'continue_bool' => $value->continue_bool,
                'DEPT' => $value->DEPT, 
                'eform_submit_count' => $value->eform_submit_count,              
                'form_counter' => $value->form_counter,  
                'agreementBtn' => $value->agreementBtn,
                'flexibleBtn' => $value->flexibleBtn,
                'PS' => 3,
                ]); 
                    foreach ($ingredients3 as $data) {
                        $data->save();
                    }     
            }  
        }
        
        /*
        Priority 4 new students, no PASHQTcur records and comes from Placement Test table and its results
         */
        $priority4_not_reset = array_diff($arrINDEXIDPlacement,$arrValuePlacement); // get the difference of INDEXID's between placement waitlisted and other placement forms
        $priority4 = array_values($priority4_not_reset) ;
        $countPriority4 = count($priority4); 
        $ingredients4 =[];
  
        for ($d=0; $d < $countPriority4; $d++) {
            // collect leftover priority 4 enrolment forms 
            $placement_forms_priority4 = PlacementForm::whereNotNull('CodeIndexID')->where('Term', $request->Term)->where('INDEXID', $priority4[$d])->orderBy('created_at', 'asc')->get();

            foreach ($placement_forms_priority4 as $value4) {
                $ingredients4[] = new  PreviewTempSort([
                'CodeIndexID' => $value4->CodeIndexID,
                'Code' => $value4->Code,
                'schedule_id' => $value4->schedule_id,
                'L' => $value4->L,
                'profile' => $value4->profile,
                'Te_Code' => $value4->Te_Code,
                'Term' => $value4->Term,
                'INDEXID' => $value4->INDEXID,
                "created_at" =>  $value4->created_at,
                "UpdatedOn" =>  $value4->UpdatedOn,
                'mgr_email' =>  $value4->mgr_email,
                'mgr_lname' => $value4->mgr_lname,
                'mgr_fname' => $value4->mgr_fname,
                'continue_bool' => $value4->continue_bool,
                'DEPT' => $value4->DEPT, 
                'eform_submit_count' => $value4->eform_submit_count,              
                'form_counter' => $value4->form_counter,  
                'agreementBtn' => $value4->agreementBtn,
                'flexibleBtn' => $value4->flexibleBtn,
                'PS' => 4,
                ]); 
                    foreach ($ingredients4 as $data4) {
                        $data4->save();
                    }     
            }
        }

        /**
         * Order Codes by count per code
         */
        DB::table('tblLTP_preview_TempOrder')->truncate();
        DB::table('tblLTP_preview')->truncate();

        // collect the courses offered for the term entered
        $te_code_collection = CourseSchedule::where('Te_Term', $request->Term)->select('Te_Code_New')->groupBy('Te_Code_New')->get('Te_Code_New');
    
        // insert Codes in Preview TempOrder Table
        foreach ($te_code_collection as $te_code) {
            $codeSortByCountIndexID = PreviewTempSort::select('Code', 'Te_Code', 'Term', DB::raw('count(*) as CountIndexID'))->where('Te_Code', $te_code->Te_Code_New)->groupBy('Code', 'Te_Code', 'Term')->orderBy(\DB::raw('count(INDEXID)'), 'ASC')->get();
            foreach ($codeSortByCountIndexID as $value) {
            DB::table('tblLTP_preview_TempOrder')->insert(
                ['Term' => $value->Term, 'Code' => $value->Code, 'Te_Code' => $value->Te_Code, 'CountIndexID' => $value->CountIndexID]
            );
            }
        }

        foreach ($te_code_collection as $te_code) {
            $getCode = PreviewTempSort::select('Code')->where('Te_Code', $te_code->Te_Code_New)->groupBy('Code')->get()->toArray();

            $arrCodeCount = [];
            $arrPerCode = [];
            $arrPerTerm = [];
            $arrPerTeCode = [];
            $ingredients = [];
            // get the count for each Code
            $j = count($getCode);
            for ($i=0; $i < $j; $i++) { 
                $perCode = PreviewTempSort::where('Code', $getCode[$i])->value('Code');
                $perTerm = PreviewTempSort::where('Code', $getCode[$i])->value('Term');
                $perTeCode = PreviewTempSort::where('Code', $getCode[$i])->value('Te_Code');
                $countPerCode = PreviewTempSort::where('Code', $getCode[$i])->get()->count();

                $arrPerCode[] = $perCode;
                $arrPerTerm[] = $perTerm;
                $arrPerTeCode[] = $perTeCode;
                $arrCodeCount[] = $countPerCode;
            }

            if (!empty($arrCodeCount)) {
                
                //  get the min of the counts for each Code
                $minValue = min($arrCodeCount);       
                $arr = [];
                $arrSaveToPash = [];

                // use min to determine the first course-schedule assignment
                for ($i=0; $i < count($arrPerCode); $i++) { 

                    if ($minValue >= $arrCodeCount[$i]) {
                        // $arr = $arrPerCode[$i]; 
                        
                        // if there are 2 or more codes with equal count
                        // run query with leftJoin() to remove duplicates
                        $queryEnrolForms = DB::table('tblLTP_preview_TempSort')
                            ->select('tblLTP_preview_TempSort.*')
                            ->where('tblLTP_preview_TempSort.Term', "=",$arrPerTerm[$i])
                            ->where('tblLTP_preview_TempSort.Code', "=",$arrPerCode[$i])
                            // leftjoin sql statement with subquery using raw statement
                            ->leftJoin(DB::raw("(SELECT 
                                  tblLTP_preview.INDEXID FROM tblLTP_preview
                                  WHERE tblLTP_preview.Term = '$arrPerTerm[$i]' AND tblLTP_preview.Te_Code = '$arrPerTeCode[$i]') as items"),function($q){
                                    $q->on("tblLTP_preview_TempSort.INDEXID","=","items.INDEXID")
                                    ;
                              })
                            ->whereNull('items.INDEXID')
                            ->orderBy('PS', 'asc')        
                            ->orderBy('created_at', 'asc')        
                            ->get();

                        // $queryEnrolForms = PreviewTempSort::where('Code', $arrPerCode[$i])->get();
                        // assign course-schedule to student and save in PASHQTcur
                        foreach ($queryEnrolForms as $value) {
                            $arrSaveToPash[] = new  Preview([
                            'CodeIndexID' => $value->CodeIndexID,
                            'Code' => $value->Code,
                            'schedule_id' => $value->schedule_id,
                            'L' => $value->L,
                            'profile' => $value->profile,
                            'Te_Code' => $value->Te_Code,
                            'Term' => $value->Term,
                            'INDEXID' => $value->INDEXID,
                            "created_at" =>  $value->created_at,
                            "UpdatedOn" =>  $value->UpdatedOn,
                            'mgr_email' =>  $value->mgr_email,
                            'mgr_lname' => $value->mgr_lname,
                            'mgr_fname' => $value->mgr_fname,
                            'continue_bool' => $value->continue_bool,
                            'DEPT' => $value->DEPT, 
                            'eform_submit_count' => $value->eform_submit_count,              
                            'form_counter' => $value->form_counter,  
                            'agreementBtn' => $value->agreementBtn,
                            'flexibleBtn' => $value->flexibleBtn,
                            'PS' => $value->PS,
                            ]); 
                            foreach ($arrSaveToPash as $data) {
                                $data->save();
                            }     
                        }   
                    } 
                }
            } // end of if statement
        } // end of foreach statement

        $checkCodeIfExisting = DB::table('tblLTP_preview_TempOrder')->select('Code', 'Te_Code', 'Term')->orderBy('id')->get()->toArray();
        $arr = [];
        $arrStd = [];
        foreach ($checkCodeIfExisting as $value) {
            $queryPashForCodesArr = Preview::where('Code', $value->Code)->get()->toArray();
            $arr[] = $queryPashForCodesArr;
            $queryPashForCodes = Preview::where('Code', $value->Code)->get();
            
            if (empty($queryPashForCodesArr)) {
                echo 'none exists';
                echo '<br>';
                // check INDEXID of students if existing in Preview table
                $students = DB::table('tblLTP_preview_TempSort')
                    ->select('tblLTP_preview_TempSort.*')
                    ->where('tblLTP_preview_TempSort.Term', "=",$value->Term)
                    ->where('tblLTP_preview_TempSort.Code', "=",$value->Code)
                    // leftjoin sql statement with subquery using raw statement
                    ->leftJoin(DB::raw("(SELECT 
                          tblLTP_preview.INDEXID FROM tblLTP_preview
                          WHERE tblLTP_preview.Term = '$value->Term' AND tblLTP_preview.Te_Code = '$value->Te_Code') as items"),function($q){
                            $q->on("tblLTP_preview_TempSort.INDEXID","=","items.INDEXID")
                            ;
                      })
                    ->whereNull('items.INDEXID')   
                    ->orderBy('PS', 'asc')   
                    ->orderBy('created_at', 'asc')    
                    ->get();
                // $arrStd[] = $students;
                // save the queried students above to Preview table 
                foreach ($students as $value) {
                    $arrStd[] = new  Preview([
                    'CodeIndexID' => $value->CodeIndexID,
                    'Code' => $value->Code,
                    'schedule_id' => $value->schedule_id,
                    'L' => $value->L,
                    'profile' => $value->profile,
                    'Te_Code' => $value->Te_Code,
                    'Term' => $value->Term,
                    'INDEXID' => $value->INDEXID,
                    "created_at" =>  $value->created_at,
                    "UpdatedOn" =>  $value->UpdatedOn,
                    'mgr_email' =>  $value->mgr_email,
                    'mgr_lname' => $value->mgr_lname,
                    'mgr_fname' => $value->mgr_fname,
                    'continue_bool' => $value->continue_bool,
                    'DEPT' => $value->DEPT, 
                    'eform_submit_count' => $value->eform_submit_count,              
                    'form_counter' => $value->form_counter,  
                    'agreementBtn' => $value->agreementBtn,
                    'flexibleBtn' => $value->flexibleBtn,
                    'PS' => $value->PS,
                    ]); 
                    foreach ($arrStd as $data) {
                        $data->save();
                    }     
                } 
            }
        }

    
        /*
         Start process of creating classes based on number of students assigned per course-schedule 
         */
        $getCodeForSectionNo = DB::table('tblLTP_preview_TempOrder')->select('Code')->orderBy('id')->get();

        $arrCountStdPerCode = [];
        foreach ($getCodeForSectionNo as $value) {
            // query student count who are not yet assigned to a class section (null)
            $countStdPerCode = Preview::where('Code', $value->Code)->where('CodeIndexIDClass', null)->get()->count();
            $arrCountStdPerCode[] = $countStdPerCode;
        }
        
        // calculate sum per code and divide by 14 or 15 for number of classes
        $num_classes =[];
        for ($i=0; $i < count($arrCountStdPerCode); $i++) { 
            $num_classes[] = intval(ceil($arrCountStdPerCode[$i]/14));
        }
        
        $getCode = DB::table('tblLTP_preview_TempOrder')->select('Code')->orderBy('id')->get()->toArray();
        $arrGetCode = [];
        $arrGetDetails = [];
        
        foreach ($getCode as $valueCode) {
            $arrGetCode[] = $valueCode->Code;
            
            // update record in CourseSchedule table to indicate that classroom has been created for this cs_unique 
            // $updateCourseSchedule = CourseSchedule::where('cs_unique', $valueCode->Code)->update(['Code' => 'Y']);

            $getDetails = CourseSchedule::where('cs_unique', $valueCode->Code)->get();
            foreach ($getDetails as $valueDetails) {
                $arrGetDetails[] = $valueDetails;
            }
        }

        // $num_classes=[5,2];
        $ingredients = [];
        $k = count($num_classes);
        $arrExistingSection = [];
        $arr = [];
        
        for ($i=0; $i < count($num_classes); $i++) { 
            // check existing section(s) first
            // value of section is 1, if $existingSection is empty
            $counter = $num_classes[$i];
            $existingSection = Classroom::where('cs_unique', $arrGetCode[$i])->orderBy('sectionNo', 'desc')->get()->toArray();
            $arrExistingSection[] = $existingSection;
            $countExistingSection = count($existingSection);
            // if not, get existing value of sectionNo
            // if (!empty($existingSection)) {
            if (count($existingSection) < $counter) {
                $sectionNo = $existingSection[0]['sectionNo'] + 1;
                $sectionNo2 = $existingSection[0]['sectionNo'] + 1;
                $arr[] = $sectionNo;
                // var_dump($sectionNo);

                for ($i2=$countExistingSection; $i2 < $counter; $i2++) { 
                    $ingredients[] = new  Classroom([
                        'Code' => $arrGetCode[$i].'-'.$sectionNo++,
                        'Te_Term' => $arrGetDetails[$i]->Te_Term,
                        'cs_unique' => $arrGetDetails[$i]->cs_unique,
                        'L' => $arrGetDetails[$i]->L, 
                        'Te_Code_New' => $arrGetDetails[$i]->Te_Code_New, 
                        'schedule_id' => $arrGetDetails[$i]->schedule_id,
                        'sectionNo' => $sectionNo2++,
                        'Te_Mon' => 2,
                        'Te_Mon_Room' => $existingSection[0]['Te_Mon_Room'],
                        'Te_Mon_BTime' => $existingSection[0]['Te_Mon_BTime'],
                        'Te_Mon_ETime' => $existingSection[0]['Te_Mon_ETime'],
                        'Te_Tue' => 3,
                        'Te_Tue_Room' => $existingSection[0]['Te_Tue_Room'],
                        'Te_Tue_BTime' => $existingSection[0]['Te_Tue_BTime'],
                        'Te_Tue_ETime' => $existingSection[0]['Te_Tue_ETime'],
                        'Te_Wed' => 4,
                        'Te_Wed_Room' => $existingSection[0]['Te_Wed_Room'],
                        'Te_Wed_BTime' => $existingSection[0]['Te_Wed_BTime'],
                        'Te_Wed_ETime' => $existingSection[0]['Te_Wed_ETime'],
                        'Te_Thu' => 5,
                        'Te_Thu_Room' => $existingSection[0]['Te_Thu_Room'],
                        'Te_Thu_BTime' => $existingSection[0]['Te_Thu_BTime'],
                        'Te_Thu_ETime' => $existingSection[0]['Te_Thu_ETime'],
                        'Te_Fri' => 6,
                        'Te_Fri_Room' => $existingSection[0]['Te_Fri_Room'],
                        'Te_Fri_BTime' => $existingSection[0]['Te_Fri_BTime'],
                        'Te_Fri_ETime' => $existingSection[0]['Te_Fri_ETime'],
                        ]);
                    foreach ($ingredients as $data) {
                                $data->save();
                    }
                }
            } 
            /**
             * debug and refactor statement below so that it gets the attributes from schedules table
             */
            if(empty($existingSection)) {
                $sectionNo = 1;
                $sectionNo2 = 1;
                for ($i2=0; $i2 < $counter; $i2++) { 
                    $ingredients[] = new  Classroom([
                        'Code' => $arrGetCode[$i].'-'.$sectionNo++,
                        'Te_Term' => $arrGetDetails[$i]->Te_Term,
                        'cs_unique' => $arrGetDetails[$i]->cs_unique,
                        'L' => $arrGetDetails[$i]->L, 
                        'Te_Code_New' => $arrGetDetails[$i]->Te_Code_New, 
                        'schedule_id' => $arrGetDetails[$i]->schedule_id,
                        'sectionNo' => $sectionNo2++,
                        ]);
                    foreach ($ingredients as $data) {
                                $data->save();
                    }
                }
            }
                // var_dump('section value starts at: '.$sectionNo);
        }
        $this->assignAndAnalyze($getCode);
        $this->getOrphans($getCode);
        
        // dd($approved_1,$approved_2,$approved_3);
        // PreviewTempSort::truncate();
        $request->session()->flash('success', 'Preview done!');
        return redirect()->route('preview-vsa-page-2');
        // dd('Count '.count($approved_collections),$arrPriority3, $arrValue,$ingredients, $ingredients3);
    }

    public function getApprovedPlacementForms(Request $request)
    {
        
    }

	public function orderCodes(Request $request)
    {
        
    }

    public function sortEnrolmentForms($te_code)
    {
        
    }

    public function checkCodeIfExistsInPash()
    {
        
    }

    public function assignAndAnalyze($getCode)
    {
    	// query PASHQTcur and take 14 students to assign classroom created in TEVENTcur
		$arrGetClassRoomDetails = [];
		$arrCountCodeClass = [];
        $arrGetOrphanStudents =[];
        $arrNotCompleteClasses = [];
        $arrNotCompleteCount = [];
        $arrjNotCompleteCount = [];
        $arrNotCompleteCode = [];
        $arrGetOrphanIndexID = [];
        $arrNotCompleteScheduleID = []; 

		foreach ($getCode as $valueCode2) {
			// code from PreviewTempSort, put in array
			$arrGetCode[] = $valueCode2->Code; 
			
			$getClassRoomDetails = Classroom::where('cs_unique', $valueCode2->Code)->get();
			foreach ($getClassRoomDetails as $valueClassRoomDetails) {
				$arrGetClassRoomDetails[] = $valueClassRoomDetails;
				
				// query student count who are not yet assigned to a class section (null) and order by priority
				$getPashStudents = Preview::where('Code', $valueCode2->Code)
                    ->where('CodeIndexIDClass', null)
                    ->orderBy('id', 'asc')
                    ->orderBy('PS', 'asc')
                    ->get()
                    ->take(14);
				foreach ($getPashStudents as $valuePashStudents) {
					$pashUpdate = Preview::where('INDEXID', $valuePashStudents->INDEXID)->where('Code', $valueClassRoomDetails->cs_unique);
					// update record with classroom assigned
					$pashUpdate->update([
                        'CodeClass' => $valueClassRoomDetails->Code, 
                        'CodeIndexIDClass' => $valueClassRoomDetails->Code.'-'.$valuePashStudents->INDEXID
                    ]);
                }

                // query PASH entries to get CodeClass count
                $checkCountCodeClass = Preview::select('Te_Code', 'Code','CodeClass', 'schedule_id', 'L', DB::raw('count(*) as CountCodeClass'))->where('Code', $valueClassRoomDetails->cs_unique)->where('CodeClass', $valueClassRoomDetails->Code)->groupBy('Te_Code','Code','CodeClass','schedule_id','L')->orderBy('CountCodeClass', 'asc')->get();
                $checkCountCodeClass->sortBy('CountCodeClass');

                // query count of CodeClass which did not meet the minimum number of students
                foreach ($checkCountCodeClass as $valueCountCodeClass) {
                        $arrCountCodeClass[] = $valueCountCodeClass->CountCodeClass;
                    
                    // if the count is less than 6 where L = Ar,Ch,Ru 
        			$language_group_1 = ['A','C','R'];
        			if (in_array($valueCountCodeClass->L, $language_group_1) && $valueCountCodeClass->CountCodeClass < 6) {
                        $getOrphanStudents = Preview::where('CodeClass', $valueCountCodeClass->CodeClass)->where('Te_Code', $valueCountCodeClass->Te_Code)->where('L', $valueCountCodeClass->L)->get();
                        
                        foreach ($getOrphanStudents as $valueOrphanStudents) {
                            $arrGetOrphanStudents[] = $valueOrphanStudents->id;
                            $arrGetOrphanIndexID[] = $valueOrphanStudents->INDEXID;
                        }
                    }
        			// if the count is less than 8 where L = Fr,En,Sp
                    $language_group_2 = ['E','F','S'];
                    if (in_array($valueCountCodeClass->L, $language_group_2) && $valueCountCodeClass->CountCodeClass < 8) {
                        $getOrphanStudents = Preview::where('CodeClass', $valueCountCodeClass->CodeClass)->where('Te_Code', $valueCountCodeClass->Te_Code)->where('L', $valueCountCodeClass->L)->get();
                        
                        foreach ($getOrphanStudents as $valueOrphanStudents) {
                            $arrGetOrphanStudents[] = $valueOrphanStudents->id;
                            $arrGetOrphanIndexID[] = $valueOrphanStudents->INDEXID;
                            // $setNullToOrphans = Preview::where('id', $valueOrphanStudents->id)->update(['CodeIndexIDClass' => null]);
                        }
                        
                        // $pashUpdate->update(['CodeClass' => $valueClassRoomDetails->Code, 'CodeIndexIDClass' => $valueClassRoomDetails->Code.'-'.$valuePashStudents->INDEXID]);
                    }

                    if ($valueCountCodeClass->CountCodeClass > 8 && $valueCountCodeClass->CountCodeClass < 14) {
                        $arrNotCompleteClasses[] = $valueCountCodeClass->CodeClass;
                        $arrNotCompleteCode[] = $valueCountCodeClass->Code;
                        $arrNotCompleteCount[] = $valueCountCodeClass->CountCodeClass;
                        $arrNotCompleteScheduleID[] = $valueCountCodeClass->schedule_id;
                    } 
                }
            }
        }

        // then change CodeClass and assign to same Te_Code with a Code count which is less than 14
        // assign orphaned students with classrooms which are not at max capacity
        $c = count($arrNotCompleteClasses);
        if ($c != 0) {
        	for ($iCount=0; $iCount < $c; $iCount++) {
            // $arrjNotCompleteCount[] = $arrNotCompleteCount[$iCount]; 
            $jNotCompleteCount = intVal(14 - $arrNotCompleteCount[$iCount]);
            $arrjNotCompleteCount[] = $jNotCompleteCount;

	            for ($iCounter2=0; $iCounter2 < $jNotCompleteCount; $iCounter2++) { 
	            	if (!empty($arrGetOrphanStudents[$iCounter2])) {          		
	            		$setClassToOrphans = Preview::where('id', $arrGetOrphanStudents[$iCounter2])
                            ->where('Code', $arrNotCompleteCode[$iCounter2])
                            ->update([
                                'CodeClass' => $arrNotCompleteClasses[$iCount], 
                                'CodeIndexIDClass' => $arrNotCompleteClasses[$iCount].'-'.$arrGetOrphanIndexID[$iCounter2], 
                                'CodeIndexID' => $arrNotCompleteCode[$iCount].'-'.$arrGetOrphanIndexID[$iCounter2], 
                                'Code' => $arrNotCompleteCode[$iCount], 
                                'schedule_id' => $arrNotCompleteScheduleID[$iCount]]);
	            	} 
	            }
        	}
        }
        // else statement if necessary
        // dd($arrCountCodeClass,$arrGetOrphanStudents, $arrNotCompleteClasses, $arrNotCompleteCount, $arrjNotCompleteCount, $c);
    }

    public function getOrphans($getCode)
    {
        // query PASHQTcur and take 14 students to assign classroom created in TEVENTcur
        $arrGetClassRoomDetails = [];
        $arrCountCodeClass = [];
        $arrGetOrphanStudents =[];
        $arrNotCompleteClasses = [];
        $arrNotCompleteCount = [];
        $arrjNotCompleteCount = [];
        $arrNotCompleteCode = [];
        $arrGetOrphanIndexID = [];
        $arrNotCompleteScheduleID = []; 

        foreach ($getCode as $valueCode2) {
            // code from PreviewTempSort, put in array
            $arrGetCode[] = $valueCode2->Code; 
            
            $getClassRoomDetails = Classroom::where('cs_unique', $valueCode2->Code)->get();
            foreach ($getClassRoomDetails as $valueClassRoomDetails) {
                $arrGetClassRoomDetails[] = $valueClassRoomDetails;
                

                // query PASH entries to get CodeClass count
                $checkCountCodeClass = Preview::select('Te_Code', 'Code','CodeClass', 'schedule_id', 'L', DB::raw('count(*) as CountCodeClass'))->where('Code', $valueClassRoomDetails->cs_unique)->where('CodeClass', $valueClassRoomDetails->Code)->groupBy('Te_Code','Code','CodeClass','schedule_id','L')->orderBy('CountCodeClass', 'asc')->get();
                $checkCountCodeClass->sortBy('CountCodeClass');

                // query count of CodeClass which did not meet the minimum number of students
                foreach ($checkCountCodeClass as $valueCountCodeClass) {
                        $arrCountCodeClass[] = $valueCountCodeClass->CountCodeClass;
                    
                    // if the count is less than 6 where L = Ar,Ch,Ru 
                    $language_group_1 = ['A','C','R'];
                    if (in_array($valueCountCodeClass->L, $language_group_1) && $valueCountCodeClass->CountCodeClass < 3) {
                        $getOrphanStudents = Preview::where('CodeClass', $valueCountCodeClass->CodeClass)->where('Te_Code', $valueCountCodeClass->Te_Code)->where('L', $valueCountCodeClass->L)->get();
                        
                        foreach ($getOrphanStudents as $valueOrphanStudents) {
                            $arrGetOrphanStudents[] = $valueOrphanStudents->id;
                            $arrGetOrphanIndexID[] = $valueOrphanStudents->INDEXID;
                        }
                    }
                    // if the count is less than 8 where L = Fr,En,Sp
                    $language_group_2 = ['E','F','S'];
                    if (in_array($valueCountCodeClass->L, $language_group_2) && $valueCountCodeClass->CountCodeClass < 5) {
                        $getOrphanStudents = Preview::where('CodeClass', $valueCountCodeClass->CodeClass)->where('Te_Code', $valueCountCodeClass->Te_Code)->where('L', $valueCountCodeClass->L)->get();
                        
                        foreach ($getOrphanStudents as $valueOrphanStudents) {
                            $arrGetOrphanStudents[] = $valueOrphanStudents->id;
                            $arrGetOrphanIndexID[] = $valueOrphanStudents->INDEXID;
                        }
                    }
                }
            }
        }
        // get the students who are orphans and 
        // put in waitlist table
        // update field with 'WL'
        foreach ($arrGetOrphanStudents as $id) {
            $update_as_waitlist = Preview::where('id', $id)->first();
            // $update_as_waitlist->Comments = 'WL';
            // $update_as_waitlist->save();
        }
    }

}
