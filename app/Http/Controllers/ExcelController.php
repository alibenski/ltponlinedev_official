<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Torgan;

class ExcelController extends Controller
{
    public function __construct()
    {

    }

    public function getBladeExcel()
    {
    	$languages = DB::table('languages')->pluck("name","code")->all();
        $org = Torgan::orderBy('Org Name', 'asc')->get(['Org Name','Org Full Name']);

        \Excel::create('excelfiles', function($excel) {
            $excel->sheet('excelfiles', function($sheet) {
                $sheet->loadView('excelfiles.bladeExcel');
                // $sheet->loadView('placement_forms.index');
            });
        })->download('xls');

        //return view('thecodingstuff.bladexcel');

    }
}
