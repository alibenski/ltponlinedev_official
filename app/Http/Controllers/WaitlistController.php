<?php

namespace App\Http\Controllers;

use App\Classroom;
use App\Course;
use App\CourseSchedule;
use App\FocalPoints;
use App\Jobs\SendEmailJob;
use App\Language;
use App\Mail\MailtoApprover;
use App\Mail\SendAuthMail;
use App\Mail\SendMailable;
use App\Mail\SendMailableReminderPlacement;
use App\Mail\SendReminderEmailHR;
use App\Mail\SendReminderEmailPlacementHR;
use App\PlacementForm;
use App\Preenrolment;
use App\Preview;
use App\PreviewTempSort;
use App\Repo;
use App\SDDEXTR;
use App\Schedule;
use App\Term;
use App\Torgan;
use App\User;
use App\Waitlist;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Session;

class WaitlistController extends Controller
{
    public function testQuery2()
    {
        $term='194';
        $q = Preenrolment::where('Term', $term)->where('overall_approval','1')->orderBy('created_at', 'asc')->get();
        $q2 = PlacementForm::where('Term', $term)->whereNotNull('Te_Code')->where('overall_approval','1')->orderBy('created_at', 'asc')->get();
        $merge = collect($q)->merge($q2);
        return view('test-query-2', compact('merge'));
        dd($q, $q2, $merge);
        // sort enrolment forms by date of submission
        $approved_0_1_collect = Preenrolment::whereIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])->where('Term', $term)->where('approval','1')->orderBy('created_at', 'asc')->get();

        $approved_0_1 = Preenrolment::select('INDEXID')->whereIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])->where('Term', $term)->where('approval','1')->orderBy('created_at', 'asc')->get();
        // apply unique() method to remove dupes 
        // apply values() method to reset key series of the array 
        $approved_1 = $approved_0_1->unique('INDEXID')->values()->all(); // becomes an array

        $approved_0_2_collect = Preenrolment::whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])->where('Term', $term)->where('approval','1')->where('approval_hr', '1')->orderBy('created_at', 'asc')->get();
        
        $approved_0_2 = Preenrolment::select('INDEXID')->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])->where('Term', $term)->where('approval','1')->where('approval_hr', '1')->orderBy('created_at', 'asc')->get();
        $approved_2 = $approved_0_2->unique('INDEXID')->values()->all();

        // !!!!!! add where selfpay_approval == 1 !!!!!!
        $approved_0_3_collect = Preenrolment::where('selfpay_approval','1')->whereNotNull('is_self_pay_form')->where('Term', $term)->orderBy('created_at', 'asc')->get();
        
        $approved_0_3 = Preenrolment::select('INDEXID')->where('selfpay_approval','1')->whereNotNull('is_self_pay_form')->where('Term', $term)->orderBy('created_at', 'asc')->get();
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
        //  // $arrCollect[] = $value->INDEXID;
        //  $arrCollect[] = $value->L;
        // }
        
        // logic to get previous Term of current/existing Term
        // 9 is 4, 1 is 9, 4 is 1
        // if last digit value is 9, subtract 5 from selectedTerm value
        $selectedTerm = $term; // No need of type casting
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
            $enrolment_forms_reenrolled = Preenrolment::where('Term', $term)->where('INDEXID', $arrValue[$i])->orderBy('created_at', 'asc')->get();
            // $enrolment_forms_reenrolled = $enrolment_forms_reenrolled->unique('INDEXID')->values()->all();
            $arr_enrolment_forms_reenrolled[] = $enrolment_forms_reenrolled;
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
            $enrolment_forms_waitlisted = Preenrolment::where('Term', $term)->where('INDEXID', $arrValue2[$z])->orderBy('created_at', 'asc')->get();

            $arr_enrolment_forms_waitlisted[] = $enrolment_forms_waitlisted;
        }

        dd($arr_enrolment_forms_reenrolled);
    }

    public function insertRecordToPreview()
    {   
        $request = (object) [
            'Term' => '191',
            'INDEXID' => 'L21199',
            'L' => 'F',
            ];

        // sort enrolment forms by date of submission
        $approved_0_1_collect = Preenrolment::where('INDEXID', $request->INDEXID)->whereIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])->where('Term', $request->Term)->where('L',$request->L)->orderBy('created_at', 'asc')->get();
        
        $approved_0_2_collect = Preenrolment::where('INDEXID', $request->INDEXID)->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])->where('Term', $request->Term)->where('L',$request->L)->orderBy('created_at', 'asc')->get();
        

        $approved_0_3_collect = Preenrolment::where('INDEXID', $request->INDEXID)->where('L',$request->L)->whereNotNull('is_self_pay_form')->where('Term', $request->Term)->orderBy('created_at', 'asc')->get();
        

        $approved_collections = collect($approved_0_1_collect)->merge($approved_0_2_collect)->merge($approved_0_3_collect)->sortBy('created_at'); // merge collections with sorting by submission date and time
        $approved_collections = $approved_collections->unique('INDEXID')->values()->all(); 

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

        if ($countArrValue > 0) {
            for ($i=0; $i < $countArrValue; $i++) {
                // collect priority 1 enrolment forms 
                $enrolment_forms_reenrolled = Preenrolment::where('Term', $request->Term)->where('INDEXID', $arrValue[$i])->orderBy('created_at', 'asc')->get();
                // $enrolment_forms_reenrolled = $enrolment_forms_reenrolled->unique('INDEXID')->values()->all();
                $arr_enrolment_forms_reenrolled[] = $enrolment_forms_reenrolled;

                // assigning of students to classes and saved in Preview TempSort table
                foreach ($enrolment_forms_reenrolled as $value) {
                    $ingredients[] = new  Preview([
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

        if ($countArrValue2 > 0) {
            for ($z=0; $z < $countArrValue2; $z++) {
                // collect priority 2 enrolment forms 
                $enrolment_forms_waitlisted = Preenrolment::where('Term', $request->Term)->where('INDEXID', $arrValue2[$z])->orderBy('created_at', 'asc')->get();

                $arr_enrolment_forms_waitlisted[] = $enrolment_forms_waitlisted;

                // assigning of students to classes and saved in Preview TempSort table
                foreach ($enrolment_forms_waitlisted as $value) {
                    $ingredients2[] = new  Preview([
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
        }

        /**
         * Get all approved/ validated placement forms 
         * and compare to waitlist table to set priority 2
         */
        // sort enrolment forms by date of submission
        $approved_0_1_collect_placement = PlacementForm::whereNotNull('CodeIndexID')->whereIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])->where('INDEXID', $request->INDEXID)->where('Term', $request->Term)->where('L',$request->L)->orderBy('created_at', 'asc')->get();

        $approved_0_2_collect_placement = PlacementForm::whereNotNull('CodeIndexID')->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])->where('INDEXID', $request->INDEXID)->where('Term', $request->Term)->where('L',$request->L)->orderBy('created_at', 'asc')->get();

        $approved_0_3_collect_placement = PlacementForm::whereNotNull('CodeIndexID')->where('L',$request->L)->whereNotNull('is_self_pay_form')->where('INDEXID', $request->INDEXID)->where('Term', $request->Term)->orderBy('created_at', 'asc')->get();
        

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

        if ($countArrValuePlacement > 0) {
            for ($h=0; $h < $countArrValuePlacement; $h++) {
                // collect priority 2 placement forms 
                $placement_forms_waitlisted = PlacementForm::whereNotNull('CodeIndexID')->where('Term', $request->Term)->where('INDEXID', $arrValuePlacement[$h])->orderBy('created_at', 'asc')->get();

                $arr_placement_forms_waitlisted[] = $placement_forms_waitlisted;

                // assigning of students to classes and saved in Preview TempSort table
                foreach ($placement_forms_waitlisted as $value_placement) {
                    $placement_ingredients2[] = new  Preview([
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

        if ($countPriority3 > 0) {
            for ($i=0; $i < $countPriority3; $i++) {
                // collect priority 3 enrolment forms 
                $enrolment_forms_priority3 = Preenrolment::where('Term', $request->Term)->where('INDEXID', $priority3[$i])->orderBy('created_at', 'asc')->get();
                $arrPriority3[] = $enrolment_forms_priority3 ;

                foreach ($enrolment_forms_priority3 as $value) {
                    $ingredients3[] = new  Preview([
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
        }
        
        /*
        Priority 4 new students, no PASHQTcur records and comes from Placement Test table and its results
         */
        $priority4_not_reset = array_diff($arrINDEXIDPlacement,$arrValuePlacement); // get the difference of INDEXID's between placement waitlisted and other placement forms
        $priority4 = array_values($priority4_not_reset) ;
        $countPriority4 = count($priority4); 
        $ingredients4 =[];
  
        if ($countPriority4 > 0) {
            for ($d=0; $d < $countPriority4; $d++) {
                // collect leftover priority 4 enrolment forms 
                $placement_forms_priority4 = PlacementForm::whereNotNull('CodeIndexID')->where('Term', $request->Term)->where('INDEXID', $priority4[$d])->orderBy('created_at', 'asc')->get();

                foreach ($placement_forms_priority4 as $value4) {
                    $ingredients4[] = new  Preview([
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
        }



        $getCode = Preview::select('Code')->where('INDEXID', $request->INDEXID)->where('L', $request->L)->orderBy('id')->get();

        $arrGetClassRoomDetails = [];
        $arrCountCodeClass = [];
        $arrGetOrphanStudents =[];
        $arrNotCompleteClasses = [];
        $arrNotCompleteCount = [];
        $arrjNotCompleteCount = [];
        $arrNotCompleteCode = [];
        $arrGetOrphanIndexID = [];
        $arrNotCompleteScheduleID = []; 

        $arrayCheck = [];
        foreach ($getCode as $valueCode2) {
            
            $arrGetCode[] = $valueCode2->Code; 
            
            $getClassRoomDetails = Classroom::where('cs_unique', $valueCode2->Code)->get();

            foreach ($getClassRoomDetails as $valueClassRoomDetails) {
                $arrGetClassRoomDetails[] = $valueClassRoomDetails;
                
                // query student count who are not yet assigned to a class section (null) and order by priority
                $getPashStudents = Preview::where('Code', $valueCode2->Code)
                    ->where('CodeIndexIDClass', null)
                    ->orderBy('id', 'asc')
                    ->orderBy('PS', 'asc')
                    ->get();

                // get the count of CodeClass
                $checkCountCodeClass = Preview::where('Code', $valueClassRoomDetails->cs_unique)
                    ->where('CodeClass', $valueClassRoomDetails->Code)
                    ->get();
                $countCodeClass = $checkCountCodeClass->count();

                // if less than 14, insert student
                if ($countCodeClass < 14) {
                    foreach ($getPashStudents as $valuePashStudents) {
                        $pashUpdate = Preview::where('INDEXID', $valuePashStudents->INDEXID)->where('Code', $valueClassRoomDetails->cs_unique);
                        // update record with classroom assigned
                        $pashUpdate->update([
                            'CodeClass' => $valueClassRoomDetails->Code, 
                            'CodeIndexIDClass' => $valueClassRoomDetails->Code.'-'.$valuePashStudents->INDEXID
                        ]);
                        $arrayCheck[] = $pashUpdate->get();
                    }

                    dd($arrayCheck);
                }

            }
            
            // if all are full and no orphan section, then create one
            $existingSection = Classroom::where('cs_unique', $valueCode2->Code)->orderBy('sectionNo', 'desc')->get()->toArray();  

            $sectionNo = $existingSection[0]['sectionNo'] + 1;
            $sectionNo2 = $existingSection[0]['sectionNo'] + 1;                  
            
            $ingredients = new  Classroom([
                'Code' => $existingSection[0]['cs_unique'].'-'.$sectionNo++,
                'Te_Term' => $existingSection[0]['Te_Term'],
                'cs_unique' => $existingSection[0]['cs_unique'],
                'L' => $existingSection[0]['L'], 
                'Te_Code_New' => $existingSection[0]['Te_Code_New'], 
                'schedule_id' => $existingSection[0]['schedule_id'],
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
            $ingredients->save();

            $getIndividualStudent = Preview::where('Code', $valueCode2->Code)
                    ->where('CodeIndexIDClass', null)
                    ->where('INDEXID', $request->INDEXID)
                    ->first();
            // then assign it to the student

            $insertStudentRecord = Preview::where('INDEXID', $getIndividualStudent->INDEXID)->where('Code', $valueCode2->Code);
            // update record with classroom assigned
            $insertStudentRecord->update([
                'CodeClass' => $ingredients->Code, 
                'CodeIndexIDClass' => $ingredients->Code.'-'.$getIndividualStudent->INDEXID
            ]);

            dd($ingredients, $insertStudentRecord->get());
        }

        dd($approved_collections, $approved_collections_placement, $arrayCheck);
    }




    public function testMethod()
    {
        $getCode = DB::table('tblLTP_preview_TempOrder')->select('Code')->orderBy('id')->get()->toArray();

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
                    ->take(15);
                foreach ($getPashStudents as $valuePashStudents) {
                    $pashUpdate = Preview::where('INDEXID', $valuePashStudents->INDEXID)->where('Code', $valueClassRoomDetails->cs_unique);
                    // update record with classroom assigned
                    $pashUpdate->update(['CodeClass' => $valueClassRoomDetails->Code, 'CodeIndexIDClass' => $valueClassRoomDetails->Code.'-'.$valuePashStudents->INDEXID]);
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

                    if ($valueCountCodeClass->CountCodeClass > 8 && $valueCountCodeClass->CountCodeClass < 15) {
                        $arrNotCompleteClasses[] = $valueCountCodeClass->CodeClass;
                        $arrNotCompleteCode[] = $valueCountCodeClass->Code;
                        $arrNotCompleteCount[] = $valueCountCodeClass->CountCodeClass;
                        $arrNotCompleteScheduleID[] = $valueCountCodeClass->schedule_id;
                    } 
                }
            }
        }

        $c = count($arrNotCompleteClasses);
        if ($c != 0) {
            for ($iCount=0; $iCount < $c; $iCount++) {
            // $arrjNotCompleteCount[] = $arrNotCompleteCount[$iCount]; 
            $jNotCompleteCount = intVal(15 - $arrNotCompleteCount[$iCount]);
            $arrjNotCompleteCount[] = $jNotCompleteCount;

                for ($iCounter2=0; $iCounter2 < $jNotCompleteCount; $iCounter2++) { 
                    if (!empty($arrGetOrphanStudents[$iCounter2])) {                
                        $setClassToOrphans = Preview::where('id', $arrGetOrphanStudents[$iCounter2])
                            ->where('Code', $arrNotCompleteCode[$iCounter2])
                            ->update([
                                'Comments' => 'WL',
                                'CodeClass' => $arrNotCompleteClasses[$iCount], 
                                'CodeIndexIDClass' => $arrNotCompleteClasses[$iCount].'-'.$arrGetOrphanIndexID[$iCounter2], 
                                'CodeIndexID' => $arrNotCompleteCode[$iCount].'-'.$arrGetOrphanIndexID[$iCounter2], 
                                'Code' => $arrNotCompleteCode[$iCount], 
                                'schedule_id' => $arrNotCompleteScheduleID[$iCount]]);
                    } 
                }
            }
        }
        dd('done',$arrGetClassRoomDetails, $arrGetOrphanStudents, $arrGetOrphanIndexID);
        /*
         Start process of creating classes based on number of students assigned per course-schedule 
         */
        $getCodeForSectionNo = DB::table('tblLTP_preview_TempOrder')->select('Code')->orderBy('id')->get();

        $arrCountStdPerCode = [];
        foreach ($getCodeForSectionNo as $value) {
            // query student count who are not yet assigned to a class section (null)
            $countStdPerCode = Preview::where('Code', 'E1R1')->where('CodeIndexIDClass', null)->get();
            $arrCountStdPerCode[] = $countStdPerCode;
        dd($arrCountStdPerCode);
        }

        // calculate sum per code and divide by 14 or 15 for number of classes
    $num_classes =[];

        for ($i=0; $i < count($arrCountStdPerCode); $i++) { 
            $num_classes[] = intval(ceil($arrCountStdPerCode[$i]/15));
        }
    dd($num_classes);
        // divide total number of students by $num_class of the Code
    $num_students_per_class = [];
        for ($q=0; $q < count($arrCountStdPerCode); $q++) { 
            $num_students_per_class[] = intval(ceil($arrCountStdPerCode[$q]/$num_classes[$q]));
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
            // if not, get existing value of sectionNo
            if (count($existingSection) < $counter) {
                $sectionNo = $existingSection[0]['sectionNo'] + 1;
                $sectionNo2 = $existingSection[0]['sectionNo'] + 1;
                $arr[] = $sectionNo;
                // var_dump($sectionNo);

                for ($i2=1; $i2 < $counter; $i2++) { 
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
            // if (!empty($existingSection)) {
            //     $sectionNo = $existingSection[0]['sectionNo'] + 1;
            //     $sectionNo2 = $existingSection[0]['sectionNo'] + 1;
            //     $arr[] = $sectionNo;
            //     // var_dump($sectionNo);

            //     for ($i2=1; $i2 < $counter; $i2++) { 
            //         $ingredients[] = new  Classroom([
            //             'Code' => $arrGetCode[$i].'-'.$sectionNo++,
            //             'Te_Term' => $arrGetDetails[$i]->Te_Term,
            //             'cs_unique' => $arrGetDetails[$i]->cs_unique,
            //             'L' => $arrGetDetails[$i]->L, 
            //             'Te_Code_New' => $arrGetDetails[$i]->Te_Code_New, 
            //             'schedule_id' => $arrGetDetails[$i]->schedule_id,
            //             'sectionNo' => $sectionNo2++,
            //             'Te_Mon' => 2,
            //             'Te_Mon_Room' => $existingSection[0]['Te_Mon_Room'],
            //             'Te_Mon_BTime' => $existingSection[0]['Te_Mon_BTime'],
            //             'Te_Mon_ETime' => $existingSection[0]['Te_Mon_ETime'],
            //             'Te_Tue' => 3,
            //             'Te_Tue_Room' => $existingSection[0]['Te_Tue_Room'],
            //             'Te_Tue_BTime' => $existingSection[0]['Te_Tue_BTime'],
            //             'Te_Tue_ETime' => $existingSection[0]['Te_Tue_ETime'],
            //             'Te_Wed' => 4,
            //             'Te_Wed_Room' => $existingSection[0]['Te_Wed_Room'],
            //             'Te_Wed_BTime' => $existingSection[0]['Te_Wed_BTime'],
            //             'Te_Wed_ETime' => $existingSection[0]['Te_Wed_ETime'],
            //             'Te_Thu' => 5,
            //             'Te_Thu_Room' => $existingSection[0]['Te_Thu_Room'],
            //             'Te_Thu_BTime' => $existingSection[0]['Te_Thu_BTime'],
            //             'Te_Thu_ETime' => $existingSection[0]['Te_Thu_ETime'],
            //             'Te_Fri' => 6,
            //             'Te_Fri_Room' => $existingSection[0]['Te_Fri_Room'],
            //             'Te_Fri_BTime' => $existingSection[0]['Te_Fri_BTime'],
            //             'Te_Fri_ETime' => $existingSection[0]['Te_Fri_ETime'],
            //             ]);
            //         foreach ($ingredients as $data) {
            //                     $data->save();
            //         }
            //     }
            // } 
            // /**
            //  * debug and refactor else state so that it gets the attributes from schedules table
            //  */
            // else {
            //     $sectionNo = 1;
            //     $sectionNo2 = 1;
            //     for ($i2=0; $i2 < $counter; $i2++) { 
            //         $ingredients[] = new  Classroom([
            //             'Code' => $arrGetCode[$i].'-'.$sectionNo++,
            //             'Te_Term' => $arrGetDetails[$i]->Te_Term,
            //             'cs_unique' => $arrGetDetails[$i]->cs_unique,
            //             'L' => $arrGetDetails[$i]->L, 
            //             'Te_Code_New' => $arrGetDetails[$i]->Te_Code_New, 
            //             'schedule_id' => $arrGetDetails[$i]->schedule_id,
            //             'sectionNo' => $sectionNo2++,
            //             ]);
            //         foreach ($ingredients as $data) {
            //                     $data->save();
            //         }
            //     }
            // }
                // var_dump('section value starts at: '.$sectionNo);
        }

dd($num_classes);
        // query PASHQTcur and take 15 students to assign classroom created in TEVENTcur
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
                
                
                // query student count who are not yet assigned to a class section (null)
                $getPashStudents = Preview::where('Code', $valueCode2->Code)->where('CodeIndexIDClass', null)->get()->take(15);

                foreach ($getPashStudents as $valuePashStudents) {
                    $pashUpdate = Preview::where('INDEXID', $valuePashStudents->INDEXID)->where('Code', $valueClassRoomDetails->cs_unique);
                    // update record with classroom assigned
                    $pashUpdate->update(['CodeClass' => $valueClassRoomDetails->Code, 'CodeIndexIDClass' => $valueClassRoomDetails->Code.'-'.$valuePashStudents->INDEXID]);
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

                    if ($valueCountCodeClass->CountCodeClass > 8 && $valueCountCodeClass->CountCodeClass < 15) {
                        $arrNotCompleteClasses[] = $valueCountCodeClass->CodeClass;
                        $arrNotCompleteCode[] = $valueCountCodeClass->Code;
                        $arrNotCompleteCount[] = $valueCountCodeClass->CountCodeClass;
                        $arrNotCompleteScheduleID[] = $valueCountCodeClass->schedule_id;
                    } 
                }
            }
        }

        // then change CodeClass and assign to same Te_Code with a Code count which is less than 15
        // assign orphaned students with classrooms which are not at max capacity
        $c = count($arrNotCompleteClasses);
        if ($c != 0) {
            for ($iCount=0; $iCount < $c; $iCount++) {
            // $arrjNotCompleteCount[] = $arrNotCompleteCount[$iCount]; 
            $jNotCompleteCount = intVal(15 - $arrNotCompleteCount[$iCount]);
            $arrjNotCompleteCount[] = $jNotCompleteCount;

                for ($iCounter2=0; $iCounter2 < $jNotCompleteCount; $iCounter2++) { 
                    if (!empty($arrGetOrphanStudents[$iCounter2])) {                
                        $setClassToOrphans = Preview::where('id', $arrGetOrphanStudents[$iCounter2])->update(['CodeClass' => $arrNotCompleteClasses[$iCount], 'CodeIndexIDClass' => $arrNotCompleteClasses[$iCount].'-'.$arrGetOrphanIndexID[$iCounter2], 'CodeIndexID' => $arrNotCompleteCode[$iCount].'-'.$arrGetOrphanIndexID[$iCounter2], 'Code' => $arrNotCompleteCode[$iCount], 'schedule_id' => $arrNotCompleteScheduleID[$iCount]]);
                    } 
                }
            }
        }
dd();        


        // $codeSortByCountIndexID = Preenrolment::select('Code', 'Term', DB::raw('count(*) as CountIndexID'))->where('Te_Code', 'F1R1')->where('INDEXID', 'L21264')->groupBy('Code', 'Term')->orderBy(\DB::raw('count(INDEXID)'), 'ASC')->get();
        
        // dd($codeSortByCountIndexID);

        // $current_term = \App\Helpers\GlobalFunction::instance()->currentTermObject();
        // $student_last_record = Repo::orderBy('Term', 'desc')->where('Term', $current_term->Term_Code)
        //         ->where('INDEXID', '17942')->first();

        // $select_courses = CourseSchedule::where('L', 'F')
        //     ->where('Te_Term', '191')
        //     ->orderBy('id', 'asc')
        //     ->with('course')
        //     // ->whereHas('course', function($q) {
        //     //                 return $q->where('id', '<', 11);
        //     //             })
        //     ->get();
        //     // ->pluck("course.Description","Te_Code_New");

        // dd($select_courses, $student_last_record->Result, $student_last_record->Te_Code_old, $current_term);
    }
    public function sddextr()
    {
        $sddextr = SDDEXTR::where('INDEXNO', '17942')->first();
        return $sddextr->users->name;
/*
        // method to re-send emails to manager for un-approved forms
        $arrRecipient = [];
        $enrolments_no_mgr_approval = Preenrolment::where('Term', '191')->whereNull('is_self_pay_form')->whereNull('approval')->select('INDEXID', 'Te_Code', 'form_counter', 'mgr_email','created_at')->groupBy('INDEXID', 'Te_Code', 'form_counter', 'mgr_email', 'created_at')->get();
        
        foreach ($enrolments_no_mgr_approval as  $valueMgrEmails) 
        {                 
            $arrRecipient[] = $valueMgrEmails->mgr_email; 
            $recipient = $valueMgrEmails->mgr_email;

            $staff = User::where('indexno', $valueMgrEmails->INDEXID)->first();
            $input_course = Preenrolment::orderBy('Term', 'desc')->orderBy('id', 'desc')->where('INDEXID', $valueMgrEmails->INDEXID)->where('Term', '191')->first();
            $input_schedules = Preenrolment::orderBy('Term', 'desc')
                                ->where('INDEXID', $valueMgrEmails->INDEXID)
                                ->where('Term', '191')
                                ->where('Te_Code', $valueMgrEmails->Te_Code)
                                ->where('form_counter', $valueMgrEmails->form_counter)
                                ->get();
            // Mail::to('allyson.frias@un.org')->send(new SendMailable($input_course, $input_schedules, $staff));
            
            echo 'email sent to: '.$recipient;
            echo '<br>';
            echo $input_course->courses->Description;
            echo '<br>';
            // echo $input_schedules;
            // echo '<br>';
            echo $staff->name;
            echo '<br>';
            echo '<br>';
        } // end of foreach loop
        dd($enrolments_no_mgr_approval);
*/
    }
    public function queryTerm()
    {
        $now_date = Carbon::now();
        $now_year = Carbon::now()->year; 
        $enrolment_term = Term::whereYear('Enrol_Date_Begin', $now_year)
                        ->orderBy('Term_Code', 'desc')
                        ->where('Enrol_Date_Begin', '<=', $now_date)
                        ->where('Approval_Date_Limit_HR', '>=', $now_date)
                        ->get()->min();
        dd($enrolment_term);
    }
    public function sendAuthEmailIndividual()
    {
        $sddextr_email_address = 'm_hallali@yahoo.com';
        // send credential email to user using email from sddextr 
        Mail::to($sddextr_email_address)->send(new SendAuthMail($sddextr_email_address));

        dd($sddextr_email_address);
    }

    public function testQuery()
    {
        $now_date = Carbon::now();
        $now_year = Carbon::now()->year; 
        $enrolment_term = Term::whereYear('Enrol_Date_Begin', $now_year)
                        ->orderBy('Term_Code', 'desc')
                        ->where('Approval_Date_Limit_HR', '>=', $now_date)
                        ->value('Term_Code');
        if (empty($enrolment_term)) {
            Log::info("Term is null. No Emails sent.");
            echo "Term is null. No Emails sent.";
            return exit();
        }

        $enrolment_term_object = Term::findOrFail($enrolment_term);

        $remind_mgr_param = Term::where('Term_Code', $enrolment_term)->value('Remind_Mgr_After'); // get int value after how many days reminder email should be sent

        $arrRecipient = [];
        $enrolments_no_mgr_approval = PlacementForm::where('Term', $enrolment_term)->whereNull('is_self_pay_form')->whereNull('approval')->select('INDEXID', 'L', 'eform_submit_count', 'mgr_email','created_at')->groupBy('INDEXID', 'L', 'eform_submit_count', 'mgr_email','created_at')->get();

        if ($enrolments_no_mgr_approval->isEmpty()) {
            Log::info("No email addresses to pick up. No Emails sent.");
            echo $enrolment_term;
            echo  $enrolments_no_mgr_approval;
            // return exit();
        }
        foreach ($enrolments_no_mgr_approval as  $valueMgrEmails) 
        {
            if ($valueMgrEmails->created_at < Carbon::parse($enrolment_term_object->Enrol_Date_End)->subDays($remind_mgr_param)) {
                if ($now_date >= Carbon::parse($valueMgrEmails->created_at)->addDays($remind_mgr_param)) {
                    $arrRecipient[] = $valueMgrEmails->mgr_email; 
                    $recipient = $valueMgrEmails->mgr_email;

                    $staff = User::where('indexno', $valueMgrEmails->INDEXID)->first();
                    $input_course = PlacementForm::orderBy('id', 'desc')->where('Term', $enrolment_term)->where('INDEXID', $valueMgrEmails->INDEXID)->where('L', $valueMgrEmails->L)->first();

                    Mail::to($recipient)->send(new SendMailableReminderPlacement($input_course, $staff));
                    echo $recipient;
                    echo '<br>';
                    echo '<br>';
                }
            }
            if ($now_date->toDateString() == Carbon::parse($enrolment_term_object->Approval_Date_Limit)->toDateString()) {
                $recipient = $valueMgrEmails->mgr_email;

                $staff = User::where('indexno', $valueMgrEmails->INDEXID)->first();
                $input_course = PlacementForm::orderBy('id', 'desc')->where('Term', $enrolment_term)->where('INDEXID', $valueMgrEmails->INDEXID)->where('L', $valueMgrEmails->L)->first();

                Mail::to($recipient)->send(new SendMailableReminderPlacement($input_course, $staff));
            }
        } // end of foreach loop

        $remind_hr_param = Term::where('Term_Code', $enrolment_term)->value('Remind_HR_After');

        $arrDept = [];
        $arrHrEmails = [];
        $arr=[];
        $enrolments_no_hr_approval = PlacementForm::where('Term', $enrolment_term)->whereNull('is_self_pay_form')->whereNull('approval_hr')->where('approval', '1')->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])->get();

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

                        $formItems = PlacementForm::orderBy('Term', 'desc')
                                        ->where('INDEXID', $valueDept->INDEXID)
                                        ->where('Term', $enrolment_term)
                                        ->where('L', $valueDept->L)
                                        ->where('eform_submit_count', $valueDept->eform_submit_count)
                                        ->get();
                        $formfirst = PlacementForm::orderBy('Term', 'desc')
                                        ->where('INDEXID', $valueDept->INDEXID)
                                        ->where('Term', $enrolment_term)
                                        ->where('L', $valueDept->L)
                                        ->where('eform_submit_count', $valueDept->eform_submit_count)
                                        ->first();   
                        // $staff_name = $formfirst->users->name;
                        $staff_name = $formfirst->users->name;
                        $arr[] = $staff_name;
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
                        Mail::to($fp_email_arr)->send(new SendReminderEmailPlacementHR($formItems, $input_course, $staff_name, $mgr_email,$term_en, $term_fr,$term_season_en, $term_season_fr,$term_year));
                    }
                }
            }

            if ($now_date->toDateString() == Carbon::parse($enrolment_term_object->Approval_Date_Limit_HR)->toDateString()) {
                $torgan = Torgan::where('Org name', $valueDept->DEPT)->first();
                $learning_partner = $torgan->has_learning_partner;

                if ($learning_partner == '1') {
                    $query_hr_email = FocalPoints::where('org_id', $torgan->OrgCode)->get(['email']); 
                    $fp_email = $query_hr_email->map(function ($val, $key) {
                        return $val->email;
                    });
                    $fp_email_arr = $fp_email->toArray();
                    $arrHrEmails[] = $fp_email_arr;

                    $formItems = PlacementForm::orderBy('Term', 'desc')
                                    ->where('INDEXID', $valueDept->INDEXID)
                                    ->where('Term', $enrolment_term)
                                    ->where('L', $valueDept->L)
                                    ->where('eform_submit_count', $valueDept->eform_submit_count)
                                    ->get();
                    $formfirst = PlacementForm::orderBy('Term', 'desc')
                                    ->where('INDEXID', $valueDept->INDEXID)
                                    ->where('Term', $enrolment_term)
                                    ->where('L', $valueDept->L)
                                    ->where('eform_submit_count', $valueDept->eform_submit_count)
                                    ->first();   
                    // $staff_name = $formfirst->users->name;
                    $staff_name = $formfirst->users->name;
                    $arr[] = $staff_name;
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
                    Mail::to($fp_email_arr)->send(new SendReminderEmailPlacementHR($formItems, $input_course, $staff_name, $mgr_email,$term_en, $term_fr,$term_season_en, $term_season_fr,$term_year));
                }
            }            
        } // end of foreach loop
        
        // dd($arrRecipient, $enrolments_no_mgr_approval, $arrHrEmails,$arr);
        return 'reminder placement emails sent';
    }
    
    // {
    //     //get current year and date
    //     $now_date = Carbon::now();
    //     $now_year = Carbon::now()->year; 
    //     // get the correct enrolment term code
    //     $enrolment_term = Term::whereYear('Enrol_Date_Begin', $now_year)
    //                     ->orderBy('Term_Code', 'desc')
    //                     ->where('Enrol_Date_Begin', '<=', $now_date)
    //                     ->where('Approval_Date_Limit_HR', '>=', $now_date)
    //                     ->min('Term_Code');

    //     if (empty($enrolment_term)) {
    //         Log::info("Auto-sending of reminder emails failed. Term is null. No Emails sent.");
    //         echo "Term is null. No Emails sent.";
    //         return exit();
    //     }

    //     $enrolment_term_object = Term::findOrFail($enrolment_term);

    //     $remind_mgr_param = Term::where('Term_Code', $enrolment_term)->value('Remind_Mgr_After'); // get int value after how many days reminder email should be sent

    //     // method to re-send emails to manager for un-approved forms
    //     $arrRecipient = [];
    //     $enrolments_no_mgr_approval = Preenrolment::where('INDEXID', '')->where('Term', '191')->whereNull('is_self_pay_form')->whereNull('approval')->select('INDEXID', 'Te_Code', 'form_counter', 'mgr_email','created_at')->groupBy('INDEXID', 'Te_Code', 'form_counter', 'mgr_email', 'created_at')->get();
        
    //     if ($enrolments_no_mgr_approval->isEmpty()) {
    //         Log::info("No email addresses to pick up. No Emails sent.");
    //         echo $enrolment_term;
    //         echo  $enrolments_no_mgr_approval;
    //         // return exit();
    //     }
    //     foreach ($enrolments_no_mgr_approval as  $valueMgrEmails) 
    //     { 
    //         // if submission date < (Enrol_Date_End minus x days) then send reminder emails after x days of submission
    //         if ($valueMgrEmails->created_at < Carbon::parse($enrolment_term_object->Enrol_Date_End)->subDays($remind_mgr_param)) {
    //             if ($now_date >= Carbon::parse($valueMgrEmails->created_at)->addDays($remind_mgr_param)) {
                
    //                 $arrRecipient[] = $valueMgrEmails->mgr_email; 
    //                 $recipient = $valueMgrEmails->mgr_email;

    //                 $staff = User::where('indexno', $valueMgrEmails->INDEXID)->first();
    //                 $input_course = Preenrolment::orderBy('Term', 'desc')->orderBy('id', 'desc')->where('INDEXID', $valueMgrEmails->INDEXID)->where('Term', '191')->first();
    //                 $input_schedules = Preenrolment::orderBy('Term', 'desc')
    //                                     ->where('INDEXID', $valueMgrEmails->INDEXID)
    //                                     ->where('Term', '191')
    //                                     ->where('Te_Code', $valueMgrEmails->Te_Code)
    //                                     ->where('form_counter', $valueMgrEmails->form_counter)
    //                                     ->get();
    //                 Mail::to($recipient)->send(new SendMailable($input_course, $input_schedules, $staff));
                    
    //                 echo 'email sent to: '.$recipient;
    //                 echo '<br>';
    //                 echo '<br>';
    //             }
    //         }
    //     } // end of foreach loop
        
    //     $remind_hr_param = Term::where('Term_Code', $enrolment_term)->value('Remind_HR_After');

    //     $arrDept = [];
    //     $arrHrEmails = [];
    //     $enrolments_no_hr_approval = Preenrolment::where('Term', $enrolment_term)->whereNull('is_self_pay_form')->whereNull('approval_hr')->where('approval', '1')->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])->select('INDEXID', 'Te_Code', 'form_counter', 'mgr_email', 'DEPT', 'UpdatedOn')->groupBy('INDEXID', 'Te_Code', 'form_counter', 'mgr_email', 'DEPT','UpdatedOn')->get();

    //     foreach ($enrolments_no_hr_approval as $valueDept) {
    //         if ($valueDept->UpdatedOn < Carbon::parse($enrolment_term_object->Enrol_Date_End)->subDays($remind_hr_param)) {
    //             if ($now_date >= Carbon::parse($valueDept->UpdatedOn)->addDays($remind_hr_param)) {
                    
    //                 $arrDept[] = $valueDept->DEPT;
    //                 $torgan = Torgan::where('Org name', $valueDept->DEPT)->first();
    //                 $learning_partner = $torgan->has_learning_partner;

    //                 if ($learning_partner == '1') {
    //                     $query_hr_email = FocalPoints::where('org_id', $torgan->OrgCode)->get(['email']); 
    //                     $fp_email = $query_hr_email->map(function ($val, $key) {
    //                         return $val->email;
    //                     });
    //                     $fp_email_arr = $fp_email->toArray();
    //                     $arrHrEmails[] = $fp_email_arr;

    //                     $formItems = Preenrolment::orderBy('Term', 'desc')
    //                                     ->where('INDEXID', $valueDept->INDEXID)
    //                                     ->where('Term', $enrolment_term)
    //                                     ->where('Te_Code', $valueDept->Te_Code)
    //                                     ->where('form_counter', $valueDept->form_counter)
    //                                     ->get();
    //                     $formfirst = Preenrolment::orderBy('Term', 'desc')
    //                                     ->where('INDEXID', $valueDept->INDEXID)
    //                                     ->where('Term', $enrolment_term)
    //                                     ->where('Te_Code', $valueDept->Te_Code)
    //                                     ->where('form_counter', $valueDept->form_counter)
    //                                     ->first();   
    //                     $staff_name = $formfirst->users->name;
    //                     $mgr_email = $formfirst->mgr_email; 

    //                     // get term values
    //                     $term = $enrolment_term;
    //                     // get term values and convert to strings
    //                     $term_en = Term::where('Term_Code', $term)->first()->Term_Name;
    //                     $term_fr = Term::where('Term_Code', $term)->first()->Term_Name_Fr;
                        
    //                     $term_season_en = Term::where('Term_Code', $term)->first()->Comments;
    //                     $term_season_fr = Term::where('Term_Code', $term)->first()->Comments_fr;

    //                     $term_date_time = Term::where('Term_Code', $term)->first()->Term_Begin;
    //                     $term_year = new Carbon($term_date_time);
    //                     $term_year = $term_year->year;

    //                     $input_course = $formfirst; 
    //                     // Mail::to($fp_email_arr);
    //                     Mail::to($fp_email_arr)->send(new SendReminderEmailHR($formItems, $input_course, $staff_name, $mgr_email,$term_en, $term_fr,$term_season_en, $term_season_fr,$term_year));
    //                 }
    //             }
    //         }

    //         if ($now_date->toDateString() == Carbon::parse($enrolment_term_object->Approval_Date_Limit_HR)->toDateString()) {
    //             echo "send to all HR Partners";
    //             $torgan = Torgan::where('Org name', $valueDept->DEPT)->first();
    //             $learning_partner = $torgan->has_learning_partner;

    //             if ($learning_partner == '1') {
    //                 $query_hr_email = FocalPoints::where('org_id', $torgan->OrgCode)->get(['email']); 
    //                 $fp_email = $query_hr_email->map(function ($val, $key) {
    //                     return $val->email;
    //                 });
    //                 $fp_email_arr = $fp_email->toArray();
    //                 $arrHrEmails[] = $fp_email_arr;

    //                 $formItems = Preenrolment::orderBy('Term', 'desc')
    //                                 ->where('INDEXID', $valueDept->INDEXID)
    //                                 ->where('Term', $enrolment_term)
    //                                 ->where('Te_Code', $valueDept->Te_Code)
    //                                 ->where('form_counter', $valueDept->form_counter)
    //                                 ->get();
    //                 $formfirst = Preenrolment::orderBy('Term', 'desc')
    //                                 ->where('INDEXID', $valueDept->INDEXID)
    //                                 ->where('Term', $enrolment_term)
    //                                 ->where('Te_Code', $valueDept->Te_Code)
    //                                 ->where('form_counter', $valueDept->form_counter)
    //                                 ->first();   
    //                 $staff_name = $formfirst->users->name;
    //                 $mgr_email = $formfirst->mgr_email;    
    //                 $input_course = $formfirst; 
    //                 // Mail::to($fp_email_arr);
    //                 Mail::to($fp_email_arr)->send(new SendReminderEmailHR($formItems, $input_course, $staff_name, $mgr_email));
    //             }
    //         }
    //     } // end of foreach loop

    //     // dd($arrRecipient, $enrolments_no_mgr_approval, $arrHrEmails,$formfirst);
    //     return 'reminder enrolment emails sent';
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    	$students = Waitlist::all();
        return view('waitlist.index')->withStudents($students);
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
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
    public function destroy($id)
    {
        //
    }
}
