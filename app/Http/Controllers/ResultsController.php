<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repo;

class ResultsController extends Controller
{
    public function pashqtcur(Request $request)
    {
    	return view('results.passFail');
    }
}
