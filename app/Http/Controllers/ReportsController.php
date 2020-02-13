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
    	$orgs = Torgan::orderBy('Org name', 'asc')->get(['Org name','Org Full Name', 'OrgCode']);
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
    
    public function ltpStatsGraphView()
    {
        $terms = Term::orderBy('Term_Code', 'desc')->get(['Term_Code', 'Term_Name', 'Comments']);
        $queryTerm = Term::orderBy('Term_Code', 'desc')->get(['Term_Code', 'Term_Begin']);

        $years = [];
        foreach ($queryTerm as $key => $value) {
            $years[] = Carbon::parse($value->Term_Begin)->year ;   
        }

        $years = array_unique($years);

        return view('reports.ltpStatsGraphView',  compact('years'));
    }

    public function getLtpStatsGraphView()
    {
        $terms = new Term;
        $termsCollection = $terms->select('Term_Begin', 'Term_Code')
            ->where('Term_Code', '>=', '191')
            ->orderBy('Term_Code', 'asc')
            ->get();

        $years = [];
        $termCodes = [];
        foreach ($termsCollection as $value) {
            $parseYear = Carbon::parse($value->Term_Begin)->year;
            $years[] = $parseYear;
            $termCodes[] = $value->Term_Code;
        }

        $fixedArrayYears = [2012,2013,2014,2015,2016,2017,2018];
        $yearArrayUnique = array_unique($years);
        $mergedArrayYears = array_merge($fixedArrayYears, $yearArrayUnique);

        $fixedArrayRegistrations = [2785,    2730,    2602,    2680,    2764,    3127,    3218];
        $registrations = [];
        foreach ($termsCollection as $k => $v) {            
            $registrations[] = [
                $years[$k] => Repo::where('Term', $v->Term_Code)->count()
            ];
        }

        $sums = [];
        foreach($years as $year){
            $sums[$year] = array_sum(array_column($registrations,$year));    
        }

        $mergedArrayRegistrations = array_merge($fixedArrayRegistrations, $sums);

        $obj = (object) [
            'title' => 'Evolution Of Registrations In Language Courses',
            'labelYears' => $mergedArrayYears,
            'regSum' => $mergedArrayRegistrations
        ];


        $data = $obj;

        return response()->json(['data' => $data]);
    }

    public function getLtpStatsGraphViewByTerm(Request $request)
    {

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
