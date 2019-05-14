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
	            $data = null;
	            return response()->json(['data' => $data]);
	        }

	        $records = new Repo;
	        $queries = [];

	        $columns = [
	            'DEPT', 
	        ];

	        
	        // foreach ($columns as $column) {
	        //     if (\Request::has($column)) {
	        //         $records = $records->where($column, \Request::input($column) );
	        //         // $records = $records->where($column, \Request::input($column) );
	        //         $queries[$column] = \Request::input($column);
	        //     }

	        // } 
	            if (Session::has('Term')) {
	                    $records = $records->where('Term', Session::get('Term') );
	                    $queries['Term'] = Session::get('Term');
	            }


	        // $records = $records->withTrashed()->paginate(20)->appends($queries);

	        $term = Session::get('Term');

	        $records_1 = $records->with('users')
	        	->where('DEPT','WIPO')
	        	->with('courses')
	        	->with('languages')
	        	->with(['courseschedules' => function ($q2) {
					    $q2->with('prices');
					}])
	        	->with('classrooms')
	        	->whereHas('classrooms', function ($query2) {
                    $query2->whereNotNull('Tch_ID')
                            ->where('Tch_ID', '!=', 'TBD')
                            ;
                    })
	        	->with('enrolments')
	        	->whereHas('enrolments', function ($query3) use ($term) {
                    $query3->where('Term', $term)->whereNull('is_self_pay_form')
                            ;
                    })
	        	->get()
	        		;

	        $pashFromPlacement = new Repo;
	        if (Session::has('Term')) {
	                    $pashFromPlacement = $pashFromPlacement->where('Term', Session::get('Term') );
	                    $queries['Term'] = Session::get('Term');
	            }

	        $records_0 = $pashFromPlacement->with('users')
	        	->where('DEPT','WIPO')
	        	->with('courses')
	        	->with('languages')
	        	->with(['courseschedules' => function ($q) {
					    $q->with('prices');
					}])
	        	->with('classrooms')
	        	->whereHas('classrooms', function ($query) {
                    $query->whereNotNull('Tch_ID')
                            ->where('Tch_ID', '!=', 'TBD')
                            ;
                    })
	        	->with('placements')
	        	->whereHas('placements', function ($query1) use ($term) {
                    $query1->where('Term', $term)->whereNull('is_self_pay_form')
                            ;
                    })
	        	->get()
	        		;


	        // MUST INCLUDE QUERY WHERE deleted_at > cancellation deadline
	        
	        $records_merged = $records_1->merge($records_0);

	        $data = $records_merged;
	        
        	return response()->json(['data' => $data]);
    	}
    }
}
