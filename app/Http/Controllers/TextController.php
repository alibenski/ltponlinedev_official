<?php

namespace App\Http\Controllers;

use App\Text;
use Illuminate\Http\Request;

class TextController extends Controller
{
	public function viewEnrolmentIsOpenText($id)
	{
		$text = Text::find($id);
		return view('texts.view-enrolment-is-open-text', compact('text'));
	}

    public function editEnrolmentIsOpenText($id)
    {
    	$text = Text::find($id);
    	return view('texts.edit-enrolment-is-open-text', compact('text'));
    }

    public function storeEnrolmentIsOpenText(Request $request, $id)
    {
    	$text = Text::find($id);
        
        if (!is_null($request->subject)) {
        	$text->subject = $request->subject;
        }
        if (!is_null($request->textValue)) {
            $text->text = $request->textValue;
        }
        
    	$text->save();

    	return redirect(route('view-enrolment-is-open-text', ['id' => $id]));
    }

    public function index()
    {
        # code...
    }
}
