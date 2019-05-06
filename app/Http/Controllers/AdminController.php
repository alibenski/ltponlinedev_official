<?php

namespace App\Http\Controllers;

use App\Classroom;
use App\NewUser;
use App\PlacementForm;
use App\Preenrolment;
use App\Preview;
use App\Repo;
use App\Services\User\ExistingUserImport;
use App\Services\User\UserImport;
use App\Term;
use App\Torgan;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Session;


class AdminController extends Controller
{
    /**
     * Copy Preview table to PASH table
     */
    public function moveToPash()
    {
        $results = \DB::select( "INSERT into LTP_PASHQTcur (INDEXID,CodeIndexIDClass,CodeClass,CodeIndexID,Code,schedule_id,Te_Code,L,flexibleBtn,convocation_email_sent,form_counter,Term,DEPT,PS,created_at,UpdatedOn,deleted_at,EMAIL,Comments,std_comments, hr_comments, teacher_comments,  admin_eform_comment, admin_plform_comment, course_preference_comment) SELECT INDEXID,CodeIndexIDClass,CodeClass,CodeIndexID,Code,schedule_id,Te_Code,L,flexibleBtn,convocation_email_sent,form_counter,Term,DEPT,PS,created_at,UpdatedOn,deleted_at,EMAIL,Comments,std_comments, hr_comments, teacher_comments, admin_eform_comment, admin_plform_comment, course_preference_comment FROM tblLTP_preview" );
    }

    public function setSessionTerm(Request $request)
    {
        $new_user_count = NewUser::where('approved_account', 0)->count();
        $terms = Term::orderBy('Term_Code', 'desc')->get();
        
        $request->session()->put('Term', $request->Term);

        // return view('admin.index',compact('terms'))->withNew_user_count($new_user_count);   
        return redirect()->back();   
    }

    public function adminViewClassrooms(Request $request)
    {
        $assigned_classes = Classroom::where('Code', $request->Code)
            ->where('Te_Term', Session::get('Term'))
            ->get();

        return view('admin.admin-view-classrooms', compact('assigned_classes'));
    }

    public function adminIndex()
    {
        $terms = Term::orderBy('Term_Code', 'desc')->get();

        $new_user_count = NewUser::where('approved_account', 0)->count();
        $cancelled_convocations = Repo::onlyTrashed()->where('Term', Session::get('Term'))->count();
        $enrolment_forms = Preenrolment::select( 'selfpay_approval', 'INDEXID','Term', 'DEPT', 'L','Te_Code','attachment_id', 'attachment_pay', 'created_at')
            ->groupBy('selfpay_approval', 'INDEXID','Term', 'DEPT', 'L','Te_Code','attachment_id', 'attachment_pay', 'created_at')
            ->where('Term', Session::get('Term'))
            ->get()->count();
        $selfpay_enrolment_forms = Preenrolment::select( 'selfpay_approval', 'INDEXID','Term', 'DEPT', 'L','Te_Code','attachment_id', 'attachment_pay', 'created_at')
            ->groupBy('selfpay_approval', 'INDEXID','Term', 'DEPT', 'L','Te_Code','attachment_id', 'attachment_pay', 'created_at')
            ->where('Term', Session::get('Term'))
            ->where('is_self_pay_form', '1')
            ->get()->count();

        $selfpay_enrolment_forms_validated = Preenrolment::select( 'selfpay_approval', 'INDEXID','Term', 'DEPT', 'L','Te_Code','attachment_id', 'attachment_pay', 'created_at')
            ->groupBy('selfpay_approval', 'INDEXID','Term', 'DEPT', 'L','Te_Code','attachment_id', 'attachment_pay', 'created_at')
            ->where('Term', Session::get('Term'))
            ->where('is_self_pay_form', '1')
            ->where('selfpay_approval', '1')
            ->get()->count();
        $selfpay_enrolment_forms_pending = Preenrolment::select( 'selfpay_approval', 'INDEXID','Term', 'DEPT', 'L','Te_Code','attachment_id', 'attachment_pay', 'created_at')
            ->groupBy('selfpay_approval', 'INDEXID','Term', 'DEPT', 'L','Te_Code','attachment_id', 'attachment_pay', 'created_at')
            ->where('Term', Session::get('Term'))
            ->where('is_self_pay_form', '1')
            ->where('selfpay_approval', '2')
            ->get()->count();
        $selfpay_enrolment_forms_disapproved = Preenrolment::select( 'selfpay_approval', 'INDEXID','Term', 'DEPT', 'L','Te_Code','attachment_id', 'attachment_pay', 'created_at')
            ->groupBy('selfpay_approval', 'INDEXID','Term', 'DEPT', 'L','Te_Code','attachment_id', 'attachment_pay', 'created_at')
            ->where('Term', Session::get('Term'))
            ->where('is_self_pay_form', '1')
            ->where('selfpay_approval', '0')
            ->get()->count();
        $selfpay_enrolment_forms_waiting = Preenrolment::select( 'selfpay_approval', 'INDEXID','Term', 'DEPT', 'L','Te_Code','attachment_id', 'attachment_pay', 'created_at')
            ->groupBy('selfpay_approval', 'INDEXID','Term', 'DEPT', 'L','Te_Code','attachment_id', 'attachment_pay', 'created_at')
            ->where('Term', Session::get('Term'))
            ->where('is_self_pay_form', '1')
            ->where('selfpay_approval', null)
            ->get()->count();


        $placement_forms = PlacementForm::select( 'selfpay_approval', 'INDEXID','Term', 'DEPT', 'L','Te_Code','attachment_id', 'attachment_pay', 'created_at')
            ->groupBy('selfpay_approval', 'INDEXID','Term', 'DEPT','L','Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
            ->where('Term', Session::get('Term'))
            ->get()->count();
        $selfpay_placement_forms = PlacementForm::select( 'selfpay_approval', 'INDEXID','Term', 'DEPT', 'L','Te_Code','attachment_id', 'attachment_pay', 'created_at')
            ->groupBy('selfpay_approval', 'INDEXID','Term', 'DEPT','L','Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
            ->where('Term', Session::get('Term'))
            ->where('is_self_pay_form', '1')
            ->get()->count();
        $selfpay_placement_forms_validated = PlacementForm::select( 'selfpay_approval', 'INDEXID','Term', 'DEPT', 'L','Te_Code','attachment_id', 'attachment_pay', 'created_at')
            ->groupBy('selfpay_approval', 'INDEXID','Term', 'DEPT','L','Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
            ->where('Term', Session::get('Term'))
            ->where('is_self_pay_form', '1')
            ->where('selfpay_approval', '1')
            ->get()->count();
        $selfpay_placement_forms_pending = PlacementForm::select( 'selfpay_approval', 'INDEXID','Term', 'DEPT', 'L','Te_Code','attachment_id', 'attachment_pay', 'created_at')
            ->groupBy('selfpay_approval', 'INDEXID','Term', 'DEPT','L','Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
            ->where('Term', Session::get('Term'))
            ->where('is_self_pay_form', '1')
            ->where('selfpay_approval', '2')
            ->get()->count();
        $selfpay_placement_forms_disapproved = PlacementForm::select( 'selfpay_approval', 'INDEXID','Term', 'DEPT', 'L','Te_Code','attachment_id', 'attachment_pay', 'created_at')
            ->groupBy('selfpay_approval', 'INDEXID','Term', 'DEPT','L','Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
            ->where('Term', Session::get('Term'))
            ->where('is_self_pay_form', '1')
            ->where('selfpay_approval', '0')
            ->get()->count();
        $selfpay_placement_forms_waiting = PlacementForm::select( 'selfpay_approval', 'INDEXID','Term', 'DEPT', 'L','Te_Code','attachment_id', 'attachment_pay', 'created_at')
            ->groupBy('selfpay_approval', 'INDEXID','Term', 'DEPT','L','Te_Code', 'attachment_id', 'attachment_pay', 'created_at')
            ->where('Term', Session::get('Term'))
            ->where('is_self_pay_form', '1')
            ->where('selfpay_approval', null)
            ->get()->count();

        if (Session::has('Term')) {
            $term = Session::get('Term');
            $prev_term = Term::where('Term_Code', $term)->first()->Term_Prev;

            $students_in_class = Repo::where('Term', $prev_term)->whereHas('classrooms', function ($query) {
                $query->whereNotNull('Tch_ID')
                        ->where('Tch_ID', '!=', 'TBD')
                        ;
                })
                ->get();
            $arr1 = [];
            foreach ($students_in_class as $key1 => $value1) {
                $arr1[] = $value1->INDEXID;
            }
            $arr1 = array_unique($arr1);
            // echo "Total Number of Students in Class for ".$prev_term.": ".count($arr1);
            // echo "<br>";

            $enrolment_forms_2 = Preenrolment::select( 'selfpay_approval', 'INDEXID','Term', 'DEPT', 'L','Te_Code','attachment_id', 'attachment_pay', 'created_at')
                ->groupBy('selfpay_approval', 'INDEXID','Term', 'DEPT', 'L','Te_Code','attachment_id', 'attachment_pay', 'created_at')
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
        $all_unassigned_enrolment_form = Preenrolment::select( 'selfpay_approval', 'INDEXID','Term', 'DEPT', 'L','Te_Code','attachment_id', 'attachment_pay', 'created_at','eform_submit_count')
            ->groupBy('selfpay_approval', 'INDEXID','Term', 'DEPT', 'L','Te_Code','attachment_id', 'attachment_pay', 'created_at','eform_submit_count')
            ->where('Term', Session::get('Term'))
            ->where('overall_approval', 1)
            ->whereNull('updated_by_admin')
            ->get()->count();

        return view('admin.index',compact('all_unassigned_enrolment_form','countNonAssignedPlacement','arr3_count','terms','cancelled_convocations','new_user_count', 'enrolment_forms', 'placement_forms', 'selfpay_enrolment_forms', 'selfpay_placement_forms', 'selfpay_enrolment_forms_validated', 'selfpay_enrolment_forms_pending', 'selfpay_enrolment_forms_disapproved', 'selfpay_enrolment_forms_waiting', 'selfpay_placement_forms_validated', 'selfpay_placement_forms_pending', 'selfpay_placement_forms_disapproved', 'selfpay_placement_forms_waiting'));   
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
                Session::flash('interdire-msg','Error in data. Correct and re-upload');
                return redirect()->back();
            }   

            $userImport->createUsers($header, $rows);
            
            session()->flash('success','Users imported');
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
                Session::flash('interdire-msg','Error in data. Correct and re-upload');
                return redirect()->back();
            }   

            $userImport->createUsers($header, $rows);
            
            session()->flash('success','Existing Users imported');
            return redirect()->back(); 
        } else
        return 'no file';
    }
}
