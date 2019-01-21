<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TextController extends Controller
{
    public function editText()
    {
    	return view('texts.edit');
    }
}
