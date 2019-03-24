<?php

namespace App\Http\Controllers;

use App\Classroom;
use App\Course;
use App\Mail\sendBroadcastEnrolmentIsOpen;
use App\Mail\sendConvocation;
use App\Mail\sendReminderToCurrentStudents;
use App\PlacementForm;
use App\Preenrolment;
use App\Repo;
use App\Teachers;
use App\Term;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Session;

class SystemController extends Controller
{
	public function systemIndex()
	{
        $term = Term::where('Term_Code', Session::get('Term'))->first();
		return view('system.system-index', compact('term'));
	}

    public function sendConvocation()
    {
        $convocation_all = Repo::where('Term', Session::get('Term'))->get();
        // with('classrooms')->get()->pluck('classrooms.Code', 'CodeIndexIDClass');
        
        // query students who will be put in waitlist
        $convocation_waitlist = Repo::where('Term', Session::get('Term'))->whereHas('classrooms', function ($query) {
                    $query->whereNull('Tch_ID')
                            ->orWhere('Tch_ID', '=', 'TBD')
                            ;
                    })
                    ->get();

        // query students who will receive convocation
        $convocation = Repo::where('Term', Session::get('Term'))->whereHas('classrooms', function ($query) {
                    $query->whereNotNull('Tch_ID')
                            ->where('Tch_ID', '!=', 'TBD')
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


            $teacher_id = $value->classrooms->Tch_ID;
            $teacher = Teachers::where('Tch_ID', $teacher_id)->first()->Tch_Name;
            $teacher_email = Teachers::where('Tch_ID', $teacher_id)->first()->email;

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
            
            Mail::to($staff_email)->send(new sendConvocation($staff_name, $course_name_en, $course_name_fr, $classrooms, $teacher, $teacher_email, $term_en, $term_fr, $schedule, $term_season_en, $term_season_fr, $term_year));

            $convocation_email_sent = Repo::where('CodeIndexIDClass', $value->CodeIndexIDClass)->update([
                        'convocation_email_sent' => 1,
                        ]);
        }
        
        return count($convocation_diff3);
    }

    public function sendBroadcastEnrolmentIsOpen(Request $request)
    {
    	// query students who have logged in
    	$query_email_addresses = User::where('must_change_password', 0)
    		->select('email')
    		->groupBy('email')
    		->get()
    		->pluck('email');

    	$term = \App\Helpers\GlobalFunction::instance()->currentTermObject();
    	if (count($term) < 1) {
    		$request->session()->flash('warning', 'No emails sent! Create a valid term.');
    		return redirect()->back();
    	}

    	$query_students_current_year = Repo::where('Term', $term->Term_Code )
    		->select('INDEXID')
    		->groupBy('INDEXID')
    		->with('users')
    		->get()
    		->pluck('users.email');

    	$merge = $query_email_addresses->merge($query_students_current_year);
    	$unique_email_address = $merge->unique();

    	// dd($merge->unique());
    	
    	// $sddextr_email_address = 'allyson.frias@gmail.com';
        foreach ($unique_email_address as $sddextr_email_address) {
        	Mail::to($sddextr_email_address)->send(new sendBroadcastEnrolmentIsOpen($sddextr_email_address));
        }

        $request->session()->flash('success', 'Broadcast email sent!');
    	return redirect()->back();
    }

    public function sendReminderToCurrentStudents(Request $request)
    {
        $term = \App\Helpers\GlobalFunction::instance()->currentTermObject();
        // query all students enrolled to current term
        $query_students_current_term = Repo::where('Term', $term->Term_Code)->get();
        
        $arr1 = [];
        $arr0 = [];
        foreach ($query_students_current_term as $key => $value) {
            $arr0[] = $value->INDEXID;

            $query_not_enrolled_stds = Preenrolment::where('INDEXID', $value->INDEXID)->where('Term', $term->Term_Next)->get();
            foreach ($query_not_enrolled_stds as $key2 => $value2) {
                $arr1[] = $value2->INDEXID;
                
            }
        }

        $arr3 = [];
        foreach ($query_students_current_term as $key5 => $value5) {
            $query_not_enrolled_stds_pl = PlacementForm::where('INDEXID', $value5->INDEXID)->where('Term', $term->Term_Next)->get();
            foreach ($query_not_enrolled_stds_pl as $key6 => $value6) {
                $arr3[] = $value6->INDEXID;
            }
        }

        $arr0 = array_unique($arr0); // remove dupes
        $arr1 = array_unique($arr1); // remove dupes
        $arr3 = array_unique($arr3); // remove dupes
        
        $difference = array_diff($arr0, $arr1); // get difference
        $difference = array_unique($difference); // remove dupes

        $diff = array_diff($difference, $arr3);
        $diff = array_unique($diff); 

        $difftest = array_diff($arr1, $arr3);
        $difftest = array_unique($difftest);

        $arr2 = [];
        foreach ($diff as $key3 => $value3) {

            $query_email_addresses = User::where('indexno', $value3)->get(['email']);
            foreach ($query_email_addresses as $key4 => $value4) {
                $sddextr_email_address = $value4->email;
                $arr2[] = $sddextr_email_address;
                Mail::to($sddextr_email_address)->send(new sendReminderToCurrentStudents($sddextr_email_address));    
            }
        }

        $request->session()->flash('success', 'Reminder email sent to '.count($arr2).' students!');
        return redirect()->back();
    }
}
