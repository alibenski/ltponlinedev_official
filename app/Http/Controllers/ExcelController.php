<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Torgan;
use App\PlacementForm;

class ExcelController extends Controller
{
    public function __construct()
    {

    }

    public function getBladeExcel()
    {
    	$languages = DB::table('languages')->pluck("name","code")->all();
        $org = Torgan::orderBy('Org name', 'asc')->get(['Org name','Org Full Name']);
                $placement_forms = new PlacementForm;
        // $currentQueries = \Request::query();
        $queries = [];

        $columns = [
            'L', 'DEPT',
        ];

        foreach ($columns as $column) {
            if (\Request::has($column)) {
                $placement_forms = $placement_forms->where($column, \Request::input($column) );
                $queries[$column] = \Request::input($column);
            }

        } 

            if (\Request::has('sort')) {
                $placement_forms = $placement_forms->orderBy('created_at', \Request::input('sort') );
                $queries['sort'] = \Request::input('sort');
            }

        // $allQueries = array_merge($queries, $currentQueries);
        $placement_forms = $placement_forms->paginate(10)->appends($queries);


        \Excel::create('excelfiles', function($excel) use ($languages, $org, $placement_forms) {
            $excel->sheet('excelfiles', function($sheet) use ($languages, $org, $placement_forms) {
                // $sheet->loadView('excelfiles.bladeExcel');
                $sheet->loadView('placement_forms.index', compact('languages', 'org', 'placement_forms'));
            });
        })->download('xlsx');

        //return view('thecodingstuff.bladexcel');

    }
}
