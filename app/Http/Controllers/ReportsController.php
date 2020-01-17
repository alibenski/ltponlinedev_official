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
            $columns = [
                'DEPT', 'L'
            ];
    		
    		if ($request->Term) {
	            $term = $request->Term;
	            $recordsMerged = $this->queryRecordsMerged($term,$columns,$request);
	            $data = $recordsMerged;
    		}

    		if ($request->year) {
    			$arrayCollection = $this->queryByYear($request, $columns);
    			$data = $arrayCollection;
    		}

    		return response()->json(['data' => $data]); 
    	}
    }

    public function queryRecordsMerged($term,$columns,$request)
    {
    	$records = new Repo;
        foreach ($columns as $column) {
            if ($request->has($column)) {
                $records = $records->where($column, $request->input($column) )
                	->where('Term', $term)
                    ->with('languages')
                    ->with('courses')
                    ->with('coursesOld')
                    ->with('users');
            }  
        }

        $recordsCancelled = new Repo;
        $termCancelDeadline = Term::where('Term_Code', $term)->first()->Cancel_Date_Limit;
        foreach ($columns as $column) {
            if ($request->has($column)) {
                if (is_null($termCancelDeadline)) {
                	$recordsCancelled = $recordsCancelled->onlyTrashed();
                } else {
                	$recordsCancelled = $recordsCancelled->onlyTrashed()->where('deleted_at','>', $termCancelDeadline);
                }
                	$recordsCancelled = $recordsCancelled->where($column, $request->input($column) )
	                	->where('Term', $term)
	                    ->with('languages')
	                    ->with('courses')
	                    ->with('coursesOld')
	                    ->with('users');

            }  
        }

        $records = $records->get();
        $recordsCancelled = $recordsCancelled->get();
        $recordsMerged = $records->merge($recordsCancelled);

        return  $recordsMerged;
    }

    public function queryByYear($request, $columns)
    {
    	$terms = Term::orderBy('Term_Code', 'asc')
                ->select('Term_Code', 'Term_Begin')
                ->get();

        $termCode = [];
        foreach ($terms as $key => $value) {
            $parseYear = Carbon::parse($value->Term_Begin)->year;
            if ($parseYear == $request->year) {
                $termCode[] = $value->Term_Code;
            }
        }

        $arrayCollection = [];
        foreach ($termCode as $term) {
        	// 
	        $recordsMerged = $this->queryRecordsMerged($term,$columns,$request);
	        // 
	        $arrayCollection[] = $recordsMerged;
        }

        $result = [];
        foreach ($arrayCollection as $k => $v) {
        	foreach ($v as $a => $b) {
        		$result[] = $b;
        	}
        }

        return $result;

    }
}
