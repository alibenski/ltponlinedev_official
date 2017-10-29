<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Data;
use App\Course;

class DataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = Course::all(); 
        return view('db')->withDatas($datas);
    }

}
