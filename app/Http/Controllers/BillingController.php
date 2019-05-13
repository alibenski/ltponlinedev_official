<?php

namespace App\Http\Controllers;

use App\Repo;
use App\Torgan;
use Illuminate\Http\Request;
use Session;

class BillingController extends Controller
{
    public function billingIndex()
    {
    	$org = Torgan::orderBy('Org Name', 'asc')->get(['Org Name','Org Full Name']);     

    	return view('billing.index', compact('org'));
    }

    public function ajaxBillingTable(Request $request)
    {
    	if ($request->ajax()) {

    		
    		if (!Session::has('Term')) {
	            $records = null;
	            return view('preenrolment.index', compact('org', 'records'));
	        }

	        $records = new Repo;
	        // $currentQueries = \Request::query();
	        $queries = [];

	        $columns = [
	            'DEPT', 
	        ];

	        
	        foreach ($columns as $column) {
	            if (\Request::has($column)) {
	                $records = $records->where($column, \Request::input($column) );
	                $queries[$column] = \Request::input($column);
	            }

	        } 
	            if (Session::has('Term')) {
	                    $records = $records->where('Term', Session::get('Term') );
	                    $queries['Term'] = Session::get('Term');
	            }


	        // $records = $records->withTrashed()->paginate(20)->appends($queries);
	        // $records = $records->withTrashed()->get();
	        $records = $records->withTrashed()->get()->toJson();

	        // $records = json_decode($records);
	        // dd($records);

	        
	        // $data = view('billing.billing_table', compact('records'))->render();
	        $data = $records;
	        
        	return response()->json($data);
    	}
    }
}
