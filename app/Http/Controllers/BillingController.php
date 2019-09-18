<?php

namespace App\Http\Controllers;

use App\Repo;
use App\Term;
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
	        $termCancelDeadline = Term::where('Term_Code', $term)->first()->Cancel_Date_Limit;


	        $records_1 = $records->with('users')
	        	->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])
	        	->with('courses')
	        	->with('languages')
	        	->with(['courseschedules' => function ($q1) {
					    $q1->with('prices')->with('courseduration');
					}])
	        	->with('classrooms')
	        	->whereHas('classrooms', function ($query1) {
                    $query1->whereNotNull('Tch_ID')
                            ->where('Tch_ID', '!=', 'TBD')
                            ;
                    })
	        	->with('enrolments')
	        	->whereHas('enrolments', function ($query11) use ($term) {
                    $query11->where('Term', $term)->whereNull('is_self_pay_form')
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
	        	->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])
	        	->with('courses')
	        	->with('languages')
	        	->with(['courseschedules' => function ($q0) {
					    $q0->with('prices')->with('courseduration');
					}])
	        	->with('classrooms')
	        	->whereHas('classrooms', function ($query0) {
                    $query0->whereNotNull('Tch_ID')
                            ->where('Tch_ID', '!=', 'TBD')
                            ;
                    })
	        	->with('placements')
	        	->whereHas('placements', function ($query00) use ($term) {
                    $query00->where('Term', $term)->whereNull('is_self_pay_form')
                            ;
                    })
	        	->get()
	        		;


	        // MUST INCLUDE QUERY WHERE deleted_at > cancellation deadline
	        $cancelledEnrolmentRecords = new Repo;
	        if (Session::has('Term')) {
	                    $cancelledEnrolmentRecords = $cancelledEnrolmentRecords->where('Term', Session::get('Term') );
	                    $queries['Term'] = Session::get('Term');
	            }

	        $records_2 = $cancelledEnrolmentRecords->onlyTrashed()->with('users')
	        	->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])
	        	->where('deleted_at','>', $termCancelDeadline)
	        	->whereNull('cancelled_but_not_billed')
	        	->with('courses')
	        	->with('languages')
	        	->with(['courseschedules' => function ($q2) {
					    $q2->with('prices')->with('courseduration');
					}])
	        	->with('classrooms')
	        	->whereHas('classrooms', function ($query2) {
                    $query2->whereNotNull('Tch_ID')
                            ->where('Tch_ID', '!=', 'TBD')
                            ;
                    })
	        	->with('enrolments')
	        	->whereHas('enrolments', function ($query22) use ($term) {
                    $query22->where('Term', $term)->whereNull('is_self_pay_form')
                            ;
                    })
	        	->get()
	        		;
	        
	        $cancelledPlacementRecords = new Repo;
	        if (Session::has('Term')) {
	                    $cancelledPlacementRecords = $cancelledPlacementRecords->where('Term', Session::get('Term') );
	                    $queries['Term'] = Session::get('Term');
	            }

	        $records_3 = $cancelledPlacementRecords->onlyTrashed()->with('users')
	        	->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])
	        	->where('deleted_at','>', $termCancelDeadline)
	        	->whereNull('cancelled_but_not_billed')
	        	->with('courses')
	        	->with('languages')
	        	->with(['courseschedules' => function ($q3) {
					    $q3->with('prices')->with('courseduration');
					}])
	        	->with('classrooms')
	        	->whereHas('classrooms', function ($query3) {
                    $query3->whereNotNull('Tch_ID')
                            ->where('Tch_ID', '!=', 'TBD')
                            ;
                    })
	        	->with('placements')
	        	->whereHas('placements', function ($query33) use ($term) {
                    $query33->where('Term', $term)->whereNull('is_self_pay_form')
                            ;
                    })
	        	->get()
	        		;

	        $records_merged = $records_1->merge($records_0)->merge($records_2)->merge($records_3);

	        $data = $records_merged;
	        
        	return response()->json(['data' => $data]);
    	}
    }

    public function billingAdminSelfpayingStudentView()
    {
        return view('billing.billing-admin-selfpaying-student-view');
    }

    public function ajaxSelfpayingStudentTable(Request $request)
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

             
            if (Session::has('Term')) {
                    $records = $records->where('Term', Session::get('Term') );
                    $queries['Term'] = Session::get('Term');
            }


            $term = Session::get('Term');
            $termCancelDeadline = Term::where('Term_Code', $term)->first()->Cancel_Date_Limit;


            $records_1 = $records->with('users')
                // ->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])
                ->with('courses')
                ->with('languages')
                ->with(['courseschedules' => function ($q1) {
                        $q1->with('prices')->with('courseduration');
                    }])
                ->with('classrooms')
                ->whereHas('classrooms', function ($query1) {
                    $query1->whereNotNull('Tch_ID')
                            ->where('Tch_ID', '!=', 'TBD')
                            ;
                    })
                ->with('enrolments')
                ->whereHas('enrolments', function ($query11) use ($term) {
                    $query11->where('Term', $term)->where('is_self_pay_form','1')
                            ->whereNotNull('CodeIndexID');
                    })
                ->get()
                    ;

            $pashFromPlacement = new Repo;
            if (Session::has('Term')) {
                        $pashFromPlacement = $pashFromPlacement->where('Term', Session::get('Term') );
                        $queries['Term'] = Session::get('Term');
                }

            $records_0 = $pashFromPlacement->with('users')
                // ->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])
                ->with('courses')
                ->with('languages')
                ->with(['courseschedules' => function ($q0) {
                        $q0->with('prices')->with('courseduration');
                    }])
                ->with('classrooms')
                ->whereHas('classrooms', function ($query0) {
                    $query0->whereNotNull('Tch_ID')
                            ->where('Tch_ID', '!=', 'TBD')
                            ;
                    })
                ->with('placements')
                ->whereHas('placements', function ($query00) use ($term) {
                    $query00->where('Term', $term)->where('is_self_pay_form','1')
                            ->whereNotNull('CodeIndexID');
                    })
                ->get()
                    ;


            // MUST INCLUDE QUERY WHERE deleted_at > cancellation deadline
            $cancelledEnrolmentRecords = new Repo;
            if (Session::has('Term')) {
                        $cancelledEnrolmentRecords = $cancelledEnrolmentRecords->where('Term', Session::get('Term') );
                        $queries['Term'] = Session::get('Term');
                }

            $records_2 = $cancelledEnrolmentRecords->onlyTrashed()->with('users')
                // ->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])
                ->where('deleted_at','>', $termCancelDeadline)
                ->whereNull('cancelled_but_not_billed')
                ->with('courses')
                ->with('languages')
                ->with(['courseschedules' => function ($q2) {
                        $q2->with('prices')->with('courseduration');
                    }])
                ->with('classrooms')
                ->whereHas('classrooms', function ($query2) {
                    $query2->whereNotNull('Tch_ID')
                            ->where('Tch_ID', '!=', 'TBD')
                            ;
                    })
                ->with('enrolments')
                ->whereHas('enrolments', function ($query22) use ($term) {
                    $query22->where('Term', $term)->where('is_self_pay_form', '1')
                            ->whereNotNull('CodeIndexID');
                    })
                ->get()
                    ;
            
            $cancelledPlacementRecords = new Repo;
            if (Session::has('Term')) {
                        $cancelledPlacementRecords = $cancelledPlacementRecords->where('Term', Session::get('Term') );
                        $queries['Term'] = Session::get('Term');
                }

            $records_3 = $cancelledPlacementRecords->onlyTrashed()->with('users')
                // ->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])
                ->where('deleted_at','>', $termCancelDeadline)
                ->whereNull('cancelled_but_not_billed')
                ->with('courses')
                ->with('languages')
                ->with(['courseschedules' => function ($q3) {
                        $q3->with('prices')->with('courseduration');
                    }])
                ->with('classrooms')
                ->whereHas('classrooms', function ($query3) {
                    $query3->whereNotNull('Tch_ID')
                            ->where('Tch_ID', '!=', 'TBD')
                            ;
                    })
                ->with('placements')
                ->whereHas('placements', function ($query33) use ($term) {
                    $query33->where('Term', $term)->where('is_self_pay_form', '1')
                            ->whereNotNull('CodeIndexID');
                    })
                ->get()
                    ;

            $records_merged = $records_1->merge($records_0)->merge($records_2)->merge($records_3);

            $data = $records_merged;
            
            return response()->json(['data' => $data]);
        }
    }

}
