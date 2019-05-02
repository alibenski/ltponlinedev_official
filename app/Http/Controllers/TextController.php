<?php

namespace App\Http\Controllers;

use App\Text;
use Illuminate\Http\Request;

class TextController extends Controller
{
	public function viewEnrolmentIsOpenText()
	{
		return view('emails.sendBroadcastEnrolmentIsOpen');
	}

    public function editEnrolmentIsOpenText()
    {
    	$text = Text::find(1);
    	return view('texts.edit-enrolment-is-open-text', compact('text'));
    }

    public function storeEnrolmentIsOpenText(Request $request)
    {
    	dd($request);
    }
}
