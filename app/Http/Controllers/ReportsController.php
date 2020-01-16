<?php

namespace App\Http\Controllers;

use App\Repo;
use App\Term;
use App\Torgan;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function baseView()
    {
    	$orgs = Torgan::orderBy('Org Name', 'asc')->get(['Org Name','Org Full Name', 'OrgCode']);
    	$languages = DB::table('languages')->pluck("name","code")->all();
    	$terms = Term::orderBy('Term_Code', 'desc')->get(['Term_Code', 'Term_Name', 'Comments']);
    	$queryTerm = Term::orderBy('Term_Code', 'desc')->get(['Term_Code', 'Term_Begin']);

        $years = [];
        foreach ($queryTerm as $key => $value) {
            $years[] = Carbon::parse($value->Term_Begin)->year ;   
        }

        $years = array_unique($years);

    	return view('reports.baseView',  compact('orgs', 'languages', 'terms', 'years'));
    }

    public function getReportsTable(Request $request)
    {
    	if ($request->ajax()) {
            // insert validation
            
    		$records = new Repo;
            $queries = [];

            $columns = [
                'DEPT', 'L', 'Term'
            ];

            foreach ($columns as $column) {
                if (\Request::has($column)) {
                    $records = $records->where($column, \Request::input($column) )
                        ->with('languages')
                        ->with('users');
                    $queries[$column] = \Request::input($column);
                }  
            } 

            $data = $records->get();

    		return response()->json(['data' => $data]); 
    	}
    }
}
