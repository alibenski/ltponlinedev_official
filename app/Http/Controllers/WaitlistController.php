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
    public function testMethod()
    {
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
        // method to re-send emails to manager for un-approved forms
        $arrRecipient = [];
        $enrolments_no_mgr_approval = Preenrolment::where('INDEXID', '')->where('Term', '191')->whereNull('is_self_pay_form')->whereNull('approval')->select('INDEXID', 'Te_Code', 'form_counter', 'mgr_email','created_at')->groupBy('INDEXID', 'Te_Code', 'form_counter', 'mgr_email', 'created_at')->get();
        
        // if ($enrolments_no_mgr_approval->isEmpty()) {
        //     Log::info("No email addresses to pick up. No Emails sent.");
        //     echo $enrolment_term;
        //     echo  $enrolments_no_mgr_approval;
        //     // return exit();
        // }
        foreach ($enrolments_no_mgr_approval as  $valueMgrEmails) 
        { 
            // if submission date < (Enrol_Date_End minus x days) then send reminder emails after x days of submission
            // if ($valueMgrEmails->created_at < Carbon::parse($enrolment_term_object->Enrol_Date_End)->subDays($remind_mgr_param)) {
                // if ($now_date >= Carbon::parse($valueMgrEmails->created_at)->addDays($remind_mgr_param)) {
                
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
                    Mail::to($recipient)->send(new SendMailable($input_course, $input_schedules, $staff));
                    
                    echo 'email sent to: '.$recipient;
                    echo '<br>';
                    echo '<br>';
                // }
            // }
        } // end of foreach loop
        dd($enrolments_no_mgr_approval);
    }
    // {
        // $arrDept = [];
        // $arrHrEmails = [];
        // $arr=[];
        // $enrolments_no_hr_approval = PlacementForm::where('Term', '191')->whereNull('is_self_pay_form')->whereNull('approval_hr')->where('approval', '1')->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])->get();

        // foreach ($enrolments_no_hr_approval as $valueDept) {
        //     // if ($valueDept->UpdatedOn < Carbon::parse($enrolment_term_object->Enrol_Date_End)->subDays($remind_hr_param)) {
        //         // if ($now_date >= Carbon::parse($valueDept->UpdatedOn)->addDays($remind_hr_param)) {
        //             $arrDept[] = $valueDept->DEPT;
        //             $torgan = Torgan::where('Org name', $valueDept->DEPT)->first();
        //             $learning_partner = $torgan->has_learning_partner;

        //             if ($learning_partner == '1') {
        //                 $query_hr_email = FocalPoints::where('org_id', $torgan->OrgCode)->get(['email']); 
        //                 $fp_email = $query_hr_email->map(function ($val, $key) {
        //                     return $val->email;
        //                 });
        //                 $fp_email_arr = $fp_email->toArray();
        //                 $arrHrEmails[] = $fp_email_arr;

        //                 $formItems = PlacementForm::orderBy('Term', 'desc')
        //                                 ->where('INDEXID', $valueDept->INDEXID)
        //                                 ->where('Term', '191')
        //                                 ->where('L', $valueDept->L)
        //                                 ->where('eform_submit_count', $valueDept->eform_submit_count)
        //                                 ->get();
        //                 $formfirst = PlacementForm::orderBy('Term', 'desc')
        //                                 ->where('INDEXID', $valueDept->INDEXID)
        //                                 ->where('Term', '191')
        //                                 ->where('L', $valueDept->L)
        //                                 ->where('eform_submit_count', $valueDept->eform_submit_count)
        //                                 ->first();   
        //                 // $staff_name = $formfirst->users->name;
        //                 $staff_name = $formfirst->users->name;
        //                 $arr[] = $staff_name;
        //                 $mgr_email = $formfirst->mgr_email;    
        //                 $input_course = $formfirst; 
        //                 // Mail::to($fp_email_arr);
        //                 Mail::to($fp_email_arr)->send(new SendReminderEmailPlacementHR($formItems, $input_course, $staff_name, $mgr_email));
        //             }
        //         // }
        //     // }          
        // } // end of foreach loop
        // dd($enrolments_no_hr_approval);
    // }
    // {
        
    //     $arrDept = [];
    //     $arrHrEmails = [];
    //     $enrolments_no_hr_approval = Preenrolment::where('Term', '191')->whereNull('is_self_pay_form')->whereNull('approval_hr')->where('approval', '1')->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])->select('INDEXID', 'Te_Code', 'form_counter', 'mgr_email', 'DEPT', 'UpdatedOn')->groupBy('INDEXID', 'Te_Code', 'form_counter', 'mgr_email', 'DEPT','UpdatedOn')->get();

    //     foreach ($enrolments_no_hr_approval as $valueDept) {
    //         // if ($valueDept->UpdatedOn < Carbon::parse($enrolment_term_object->Enrol_Date_End)->subDays($remind_hr_param)) {
    //             // if ($now_date >= Carbon::parse($valueDept->UpdatedOn)->addDays($remind_hr_param)) {
                    
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
    //                                     ->where('Term', '191')
    //                                     ->where('Te_Code', $valueDept->Te_Code)
    //                                     ->where('form_counter', $valueDept->form_counter)
    //                                     ->get();
    //                     $formfirst = Preenrolment::orderBy('Term', 'desc')
    //                                     ->where('INDEXID', $valueDept->INDEXID)
    //                                     ->where('Term', '191')
    //                                     ->where('Te_Code', $valueDept->Te_Code)
    //                                     ->where('form_counter', $valueDept->form_counter)
    //                                     ->first();   
    //                     $staff_name = $formfirst->users->name;
    //                     $mgr_email = $formfirst->mgr_email;    
    //                     $input_course = $formfirst; 
    //                     // Mail::to($fp_email_arr);
    //                     Mail::to($fp_email_arr)->send(new SendReminderEmailHR($formItems, $input_course, $staff_name, $mgr_email));
    //                 }
    //             // }
    //         // }
    //     }
    //     dd($enrolments_no_hr_approval);
    // }
    // {
    //     $never_logged = User::where('must_change_password', 1)->get();
    //     $input = ([ 
    //         'password' => Hash::make('Welcome2CLM'),
    //     ]);
    //     foreach ($never_logged as $user) {
    //         $user->fill($input)->save();
    //     }

    //     dd($never_logged);
    // }
    // {
    //     $enrolments_no_mgr_approval = PlacementForm::where('Term', '188')->whereNull('is_self_pay_form')->whereNull('approval')->select('INDEXID', 'L', 'eform_submit_count', 'mgr_email','created_at')->groupBy('INDEXID', 'L', 'eform_submit_count', 'mgr_email','created_at')->get()->take(1);
    //     foreach ($enrolments_no_mgr_approval as  $valueMgrEmails) 
    //     {           
    //             $arrRecipient[] = $valueMgrEmails->mgr_email; 
    //             $recipient = $valueMgrEmails->mgr_email;

    //             $staff = User::where('indexno', $valueMgrEmails->INDEXID)->first();
    //             $input_course = PlacementForm::orderBy('id', 'desc')->where('Term', '188')->where('INDEXID', $valueMgrEmails->INDEXID)->where('L', $valueMgrEmails->L)->first();

    //             Mail::to('allyson.frias@un.org')->send(new SendMailableReminderPlacement($input_course, $staff));
    //             echo $recipient;
    //             echo '<br>';
    //             echo '<br>';   
    //     }
    //     $arrDept = [];
    //     $arrHrEmails = [];
    //     $enrolments_no_hr_approval = PlacementForm::where('Term', '188')->whereNull('is_self_pay_form')->whereNull('approval_hr')->where('approval', '1')->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])->get()->take(1);

    //     foreach ($enrolments_no_hr_approval as $valueDept) {
                
    //             $arrDept[] = $valueDept->DEPT;
    //             $torgan = Torgan::where('Org name', $valueDept->DEPT)->first();
    //             $learning_partner = $torgan->has_learning_partner;

    //             if ($learning_partner == '1') {
    //                 $query_hr_email = FocalPoints::where('org_id', $torgan->OrgCode)->get(['email']); 
    //                 $fp_email = $query_hr_email->map(function ($val, $key) {
    //                     return $val->email;
    //                 });
    //                 $fp_email_arr = $fp_email->toArray();
    //                 $arrHrEmails[] = $fp_email_arr;

    //                 $formItems = PlacementForm::orderBy('Term', 'desc')
    //                                 ->where('INDEXID', $valueDept->INDEXID)
    //                                 ->where('Term', '188')
    //                                 ->where('L', $valueDept->L)
    //                                 ->where('eform_submit_count', $valueDept->eform_submit_count)
    //                                 ->get();
    //                 $formfirst = PlacementForm::orderBy('Term', 'desc')
    //                                 ->where('INDEXID', $valueDept->INDEXID)
    //                                 ->where('Term', '188')
    //                                 ->where('L', $valueDept->L)
    //                                 ->where('eform_submit_count', $valueDept->eform_submit_count)
    //                                 ->first();   
    //                 // $staff_name = $formfirst->users->name;
    //                 $staff_name = $formfirst->users->name;
    //                 $arr[] = $staff_name;
    //                 $mgr_email = $formfirst->mgr_email;    
    //                 $input_course = $formfirst; 
    //                 // Mail::to($fp_email_arr);
    //                 Mail::to('allyson.frias@un.org')->send(new SendReminderEmailPlacementHR($formItems, $input_course, $staff_name, $mgr_email));
    //             }
    //     }
    //     // DB::table('jobs')->truncate();
    //     // Log::info("Start sending email");
    //     // for ($i=0; $i < 2; $i++)  {
    //     //     $emailJob = (new SendEmailJob())->delay(Carbon::now()->addSeconds(10));
    //     //     dispatch($emailJob);
    //     // }
    //     //     echo 'email sent<br>';
    //     // Log::info("Finished sending email");

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
