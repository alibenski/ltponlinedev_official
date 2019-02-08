<?php

namespace App\Http\Controllers;

use App\Mail\sendBroadcastEnrolmentIsOpen;
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
    	$unique_email_address = $merge->unique()->take(2);

    	// dd($merge->unique());
    	
    	// $sddextr_email_address = 'allyson.frias@gmail.com';
        foreach ($unique_email_address as $sddextr_email_address) {
        	Mail::to($sddextr_email_address)->send(new sendBroadcastEnrolmentIsOpen($sddextr_email_address));
        }

        $request->session()->flash('success', 'Broadcast email sent!');
    	return redirect()->back();
    }
}
