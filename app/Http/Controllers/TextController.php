<?php

namespace App\Http\Controllers;

use App\Text;
use Illuminate\Http\Request;

class TextController extends Controller
{
	public function viewEnrolmentIsOpenText()
	{
		$text = Text::find(1);
		return view('texts.view-enrolment-is-open-text', compact('text'));
	}

    public function editEnrolmentIsOpenText()
    {
    	$text = Text::find(1);
    	return view('texts.edit-enrolment-is-open-text', compact('text'));
    }

    public function storeEnrolmentIsOpenText(Request $request)
    {
    	$text = Text::find(1);
    	$text->text = $request->textValue;
    	$text->save();

    	return redirect(route('view-enrolment-is-open-text'));
    }
}
