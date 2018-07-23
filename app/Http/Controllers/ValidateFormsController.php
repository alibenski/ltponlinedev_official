<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use Carbon\Carbon;
use DB;
use Session;
use App\Mail\MailtoApprover;
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
use App\PlacementForm;
use App\Mail\MailPlacementTesttoApprover;
use App\Day;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;



class ValidateFormsController extends Controller
{
	public function getApprovedEnrolmentForms(Request $request)
    {
        // sort enrolment forms by date of submission
        $approved_1 = Preenrolment::select('INDEXID')->whereIn('DEPT', ['UNOG','JIU','DDA','OIOS'])->where('L','F')->where('Term', '188')->where('approval','1')->orderBy('created_at', 'asc')->get();
        // apply unique() method to remove dupes 
        // apply values() method to reset key series of the array 
        $approved_1 = $approved_1->unique('INDEXID')->values()->all(); // becomes an array

        $approved_2 = Preenrolment::select('INDEXID')->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS'])->where('approval','1')->where('approval_hr', '1')->groupBy('INDEXID')->get();
        $approved_3 = Preenrolment::select('INDEXID')->whereNotNull('is_self_pay_form')->groupBy('INDEXID')->get();
        
        // $placement_forms = $approved_1->merge($approved_2)->merge($approved_3);
        // $arr = [];
        // foreach ($approved_2 as $value) {
        // 	$arr[] = $value->INDEXID;
        // }
        
        // logic to get previous Term of current/existing Term
        // 9 is 4, 1 is 9, 4 is 1
        // if last digit value is 9, subtract 5 from selectedTerm value
        $selectedTerm = 188; // No need of type casting
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
        $arrStudentReEnrolled = [];
        $arrValue = [];
        
        for ($i=0; $i < count($approved_1); $i++) { 
            $arrINDEXID[] = $approved_1[$i]['INDEXID'];
            // echo $i. " - " .$arrINDEXID[$i] ;
            // echo "<br>";
        
            // priority 1: check each index id if they are already in re-enroling students from previous term via PASHQTcur table
            $student_reenrolled = Repo::select('INDEXID')->where('Term', $prev_term)->where('L', 'F')->where('INDEXID', $arrINDEXID[$i])->groupBy('INDEXID')->get()->toArray();
            $arrStudentReEnrolled[] = $student_reenrolled;
            $student_reenrolled_filtered = array_filter($student_reenrolled);
            
            // iterate to get the index id of staff who are re-enroling
            foreach($student_reenrolled_filtered as $item) {
                // to know what's in $item
                // echo '<pre>'; var_dump($item);
                foreach ($item as $value) {
                    $arrValue[] = $value; // store the INDEXID values in array
                    // echo $value['INDEXID'];
                    // echo "<br>";
                    // echo '<pre>'; var_dump($value['INDEXID']);
                }
            }
        }

        $arr_enrolment_forms_reenrolled = [];
        $ingredients = []; 

        for ($i=0; $i < count($arrValue); $i++) {
        	// collect priority 1 enrolment forms 
            $enrolment_forms_reenrolled = Preenrolment::orderBy('created_at', 'asc')->where('INDEXID', $arrValue[$i])->get();
            $enrolment_forms_reenrolled = $enrolment_forms_reenrolled->unique('INDEXID')->values()->all();
            $arr_enrolment_forms_reenrolled[] = $enrolment_forms_reenrolled;

            // assigning of students to classes and saved in PASHQTcur table
            foreach ($enrolment_forms_reenrolled as $value) {
                $ingredients[] = new  Repo([
                'id' => $value->id,
                'INDEXID' => $value->INDEXID,
                'schedule_id' => $value->schedule_id,
                'Code' => $value->Code,
                'Term' => $value->Term,
                'created_at' => $value->created_at,
                ]); 
                    foreach ($ingredients as $data) {
                        // $data->save();
                    }     
            }   
        }
        
        // dd($approved_1,$approved_2,$approved_3);
        dd($approved_1, $arr_enrolment_forms_reenrolled, $ingredients, count($ingredients));
    }

    public function getApprovedPlacementForms(Request $request)
    {
        $approved_1 = PlacementForm::select('INDEXID')->whereIn('DEPT', ['UNOG','JIU','DDA','OIOS'])->where('approval','1')->get();
        $approved_2 = PlacementForm::select('INDEXID')->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS'])->where('approval','1')->where('approval_hr', '1')->get();
        $approved_3 = PlacementForm::select('INDEXID')->whereNotNull('is_self_pay_form')->get();
        
        $placement_forms = $approved_1->merge($approved_2)->merge($approved_3);
        $arr = [];
        foreach ($approved_2 as $value) {
        	$arr[] = $value->INDEXID;
        }
        
        dd($approved_1,$approved_2,$approved_3, $arr);
    }

    public function priorityFactor()
    {
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

        // enrolment forms non-UNOG approved by manager & HR
        $enrolment_forms_2 = Preenrolment::select('INDEXID')->where('L', 'F')->where('approval', '1')->where('approval_hr', '1')->where('Term', $selectedTerm)->groupBy('INDEXID')->get()->toArray();

        $arrINDEXID = [];
        $arrStudentReEnrolled = [];
        $arrValue = [];
        
        for ($i=0; $i < count($enrolment_forms_2); $i++) { 
            $arrINDEXID[] = $enrolment_forms_2[$i]['INDEXID'];
            // echo $i. " - " .$arrINDEXID[$i] ;
            // echo "<br>";
        
            // check each index id if they are already in re-enroling students from previous term
            $student_reenrolled = Repo::select('INDEXID')->where('Term', $prev_term)->where('L', 'F')->where('INDEXID', $arrINDEXID[$i])->groupBy('INDEXID')->get()->toArray();
            $arrStudentReEnrolled[] = $student_reenrolled;
            $student_reenrolled_filtered = array_filter($student_reenrolled);
            
            // iterate to get the index id of staff who are re-enroling
            foreach($student_reenrolled_filtered as $item) {
                // to know what's in $item
                // echo '<pre>'; var_dump($item);
                foreach ($item as $value) {
                    $arrValue[] = $value;
                    // echo $value['INDEXID'];
                    // echo "<br>";
                    // echo '<pre>'; var_dump($value['INDEXID']);
                }
            }
        }

        $arr_enrolment_forms_reenrolled = [];
        $ingredients = []; 

        for ($i=0; $i < 14; $i++) { 
            $enrolment_forms_reenrolled = Preenrolment::orderBy('id', 'asc')->where('INDEXID', $arrValue[$i])->get();
            
            $arr_enrolment_forms_reenrolled[] = $enrolment_forms_reenrolled;

            foreach ($enrolment_forms_reenrolled as $value) {
                $ingredients[] = new  Repo([
                'INDEXID' => $value->INDEXID,
                'Code' => $value->Code,
                'Term' => $value->Term,
                ]); 
                    foreach ($ingredients as $data) {
                        // $data->save();
                    }     
            }   
        }

        dd(count($arrValue), $arr_enrolment_forms_reenrolled, $ingredients);
    }
}
