<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExamResultController extends Controller
{
    public function examResultSave(Request $request)
    {
    	if ($request->ajax()) {


    		
    		$data = $request->all();
    		return response()->json($data);
    	}
    }
}
