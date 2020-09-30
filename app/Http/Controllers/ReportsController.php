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
    public function viewStudentsPerTerm()
    {
        return view('reports.viewStudentsPerTerm');
    }

    public function statsStudentsPerTerm()
    {
        $qryTerms = Repo::select('Term')->groupBy('Term')->get()->toArray();
        $termsArray = [];
        foreach ($qryTerms as $value) {
            $termsArray[] = $value['Term'];
        }

        $qryStudentsArray = [];
        $obj = [];
        foreach ($termsArray as $term) {
            $qryStudents = Repo::where('Term', $term)->get()->count();
            $obj[] = (object) [
                'term' => $term,
                'count' => $qryStudents,
            ];
        }

        return response()->json($obj);
    }

    public function baseView()
    {
        $orgs = Torgan::orderBy('Org name', 'asc')->get(['Org name', 'Org Full Name', 'OrgCode']);
        $languages = DB::table('languages')->pluck("name", "code")->all();
        $terms = Term::orderBy('Term_Code', 'desc')->get(['Term_Code', 'Term_Name', 'Comments']);
        $queryTerm = Term::orderBy('Term_Code', 'desc')->get(['Term_Code', 'Term_Begin']);

        $years = [];
        foreach ($queryTerm as $key => $value) {
            $years[] = Carbon::parse($value->Term_Begin)->year;
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
                $recordsMerged = $this->queryRecordsMerged($term, $columns, $request);
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
        return view('reports.ltpStatsGraphView');
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

        $fixedArrayYears = [2012, 2013, 2014, 2015, 2016, 2017, 2018];
        $yearArrayUnique = array_unique($years);
        $mergedArrayYears = array_merge($fixedArrayYears, $yearArrayUnique);

        $fixedArrayRegistrations = [2785,    2730,    2602,    2680,    2764,    3127,    3218];
        $registrations = [];
        foreach ($termsCollection as $k => $v) {
            $registrations[] = [
                $years[$k] => Repo::select('INDEXID', 'Term', 'CodeClass', 'Code', 'Te_Code', 'L')
                    ->where('Term', $v->Term_Code)
                    ->whereHas('classrooms', function ($q) {
                        // query all students enrolled to current term excluding waitlisted
                        $q->select('CodeClass', 'Code', 'Tch_ID')->whereNotNull('Tch_ID')->where('Tch_ID', '!=', 'TBD');
                    })
                    ->count()
            ];
        }

        $sums = [];
        foreach ($years as $year) {
            $sums[$year] = array_sum(array_column($registrations, $year));
        }

        $mergedArrayRegistrations = array_merge($fixedArrayRegistrations, $sums);

        $obj = (object) [
            'title' => 'Evolution of Registrations in Language Courses',
            'labelYears' => $mergedArrayYears,
            'regSum' => $mergedArrayRegistrations
        ];


        $data = $obj;

        return response()->json(['data' => $data]);
    }

    public function ltpStatsGraphViewByLanguage()
    {
        return view('reports.ltpStatsGraphViewByLanguage');
    }

    public function getLtpStatsGraphViewByLanguage()
    {
        $languagesCollection = DB::table('languages')->select('id', 'name', 'code')->orderBy('id', 'asc')->get();
        $languages = $languagesCollection->pluck(['name']);

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

        $fixedArrayYears = [2012, 2013, 2014, 2015, 2016, 2017, 2018];
        $yearArrayUnique = array_unique($years);
        $mergedArrayYears = array_merge($fixedArrayYears, $yearArrayUnique);

        $registrations = [];
        foreach ($termsCollection as $k => $v) {
            foreach ($languagesCollection as $language) {
                $registrations[] = [
                    $language->name . $years[$k] => Repo::select('INDEXID', 'Term', 'CodeClass', 'Code', 'Te_Code', 'L')
                        ->where('L', $language->code)
                        ->where('Term', $v->Term_Code)
                        ->whereHas('classrooms', function ($q) {
                            $q->select('CodeClass', 'Code', 'Tch_ID')->whereNotNull('Tch_ID')->where('Tch_ID', '!=', 'TBD');
                        })
                        ->count(),
                ];
            }
        }

        $keys = [];
        foreach ($registrations as $subarr) {
            $keys[] = key($subarr);
        }

        $keysUnique = array_unique($keys);

        $sums = [];
        foreach ($keysUnique as $key) {
            $sums[$key] = array_sum(array_column($registrations, $key));
        }

        $arrChunk = array_chunk($sums, count($languagesCollection));
        // $resetKeysOfSum = array_values($sums);
        $fixedArrayRegistrations = [
            0 => [210, 187, 565, 1138, 184, 501],
            1 => [177, 176, 528, 1270, 166, 413],
            2 => [217, 181, 505, 1182, 136, 381],
            3 => [228, 199, 482, 1181, 126, 464],
            4 => [225, 203, 464, 1284, 157, 431],
            5 => [240, 152, 510, 1495, 239, 491],
            6 => [239, 186, 474, 1558, 243, 518]
        ];

        $mergedArrayRegistrations = array_merge($fixedArrayRegistrations, $arrChunk);

        $obj = (object) [
            'title' => 'Number of Registrations in Language Courses',
            'xAxis' => $languages,
            'years' => $mergedArrayYears,
            'registrationsPerYearPerLanguage' => $mergedArrayRegistrations,

        ];

        $data = $obj;

        return response()->json(['data' => $data]);
    }

    public function queryRecordsMerged($term, $columns, $request)
    {
        $records = new Repo;
        foreach ($columns as $column) {
            if ($request->filled($column)) {
                $records = $records->where($column, $request->input($column))
                    ->where('Term', $term)
                    ->whereHas('classrooms', function ($q) {
                        $q->select('CodeClass', 'Code', 'Tch_ID')->whereNotNull('Tch_ID')->where('Tch_ID', '!=', 'TBD');
                    })
                    ->with('languages')
                    ->with('courses')
                    ->with('coursesOld')
                    ->with('users')
                    ->with('classrooms.courseSchedule.courseduration');
            }
        }

        $recordsCancelled = new Repo;
        $termCancelDeadline = Term::where('Term_Code', $term)->first()->Cancel_Date_Limit;
        foreach ($columns as $column) {
            if ($request->filled($column)) {
                if (is_null($termCancelDeadline)) {
                    $recordsCancelled = $recordsCancelled->onlyTrashed();
                } else {
                    $recordsCancelled = $recordsCancelled->onlyTrashed()->where('deleted_at', '>', $termCancelDeadline);
                }
                $recordsCancelled = $recordsCancelled->where($column, $request->input($column))
                    ->where('Term', $term)
                    ->with('languages')
                    ->with('courses')
                    ->with('coursesOld')
                    ->with('users')
                    ->with('classrooms.courseSchedule.courseduration');
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
            $recordsMerged = $this->queryRecordsMerged($term, $columns, $request);
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
