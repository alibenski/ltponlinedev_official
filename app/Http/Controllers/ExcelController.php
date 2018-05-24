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
        $url = \$request->fullurl();
        $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $result = curl_exec($ch);
    curl_close($ch);

    	$languages = DB::table('languages')->pluck("name","code")->all();
        $org = Torgan::orderBy('Org Name', 'asc')->get(['Org Name','Org Full Name']);
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
                $sheet->loadView('placement_forms.index')->withLanguages($languages)->withOrg($org)->withPlacement_forms($placement_forms);
            });
        })->download('csv');

        //return view('thecodingstuff.bladexcel');

    }
}
