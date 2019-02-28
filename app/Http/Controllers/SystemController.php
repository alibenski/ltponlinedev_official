<?php

namespace App\Http\Controllers;

use App\Mail\sendBroadcastEnrolmentIsOpen;
use App\Preenrolment;
use App\Repo;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SystemController extends Controller
{
	public function systemIndex()
	{
		return view('system.system-index');
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

        $arr0 = array_unique($arr0); // remove dupes
        $arr1 = array_unique($arr1); // remove dupes
        
        $diff = array_diff($arr0, $arr1); // get difference
        $diff = array_unique($diff); // remove dupes

        $arr2 = [];
        foreach ($diff as $key3 => $value3) {

            $query_email_addresses = User::where('indexno', $value3)->get(['email']);
            foreach ($query_email_addresses as $key4 => $value4) {
               Mail::to($value4->email)
                    ->subject("Reminder - Language Training Programme: Enrolment Period Open / Rappel - Programme de formation linguistique : Période d'inscription Ouverte")
                    ->send(new sendBroadcastEnrolmentIsOpen($value4->email);
            }
        }
        dd($arr2, $diff, $arr0, $arr1);


    }
}
