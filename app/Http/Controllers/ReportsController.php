<?php

namespace App\Http\Controllers;

use App\Classroom;
use App\FocalPoints;
use App\Mail\EmailReportByOrg;
use App\Repo;
use App\Term;
use App\Torgan;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use League\CommonMark\Util\ArrayCollection;

class ReportsController extends Controller
{
    public function reportsCustomBillingView()
    {
        $terms = Term::orderBy('Term_Code', 'desc')->get();
        $queryTerm = Term::orderBy('Term_Code', 'desc')->get(['Term_Code', 'Term_Begin']);
        $languages = DB::table('languages')->pluck("name", "code")->all();

        $years = array_unique($this->getYears($queryTerm));

        return view('reports.reportsCustomBillingView', compact('languages', 'terms', 'years'));
    }

    public function reportsCustomBilling(Request $request)
    {
        if ($request->ajax()) {
            $columns = [
                'L'
            ];
            $arr = [];
            if ($request->Term) {
                $term = $request->Term;
                $recordsMerged = $this->queryCustomBillingStudentsMerged($term, $columns, $request);
                foreach ($recordsMerged as $v) {
                    foreach ($v as $value) {
                        $arr[] = $value;
                    }
                }
                $data = $arr;
            }

            if ($request->year) {
                $arrayCollection = $this->queryCustomBillingStudentsByYear($request, $columns);
                foreach ($arrayCollection as $dataValue) {
                    foreach ($dataValue as $v) {
                        foreach ($v as $value) {
                            $arr[] = $value;
                        }
                    }
                }
                $data = $arr;
            }

            return response()->json(['data' => $data]);
        }
    }

    public function queryCustomBillingStudentsMerged($term, $columns, $request)
    {
        $records = new Repo;
        foreach ($columns as $column) {
            if ($request->filled($column)) {
                $records = $records->where($column, $request->input($column));
            }
            $records = $records
                ->where('Term', $term)
                ->select('id', 'Term', 'DEPT', 'is_self_pay_form', 'Result', 'cancelled_but_not_billed', 'exclude_from_billing', 'deleted_at', 'INDEXID', 'INDEXID_old', 'Te_Code', 'Te_Code_old', 'Code', 'CodeClass', 'CodeIndexID', 'CodeIndexIDClass', 'CodeIndexID_old', 'L',)
                ->where(function ($queryCancelledNotBilled) {
                    $queryCancelledNotBilled->where('cancelled_but_not_billed', '!=', 1)->orWhereNull('cancelled_but_not_billed');
                })
                ->where(function ($queryExcluded) {
                    $queryExcluded->where('exclude_from_billing', '!=', 1)->orWhereNull('exclude_from_billing');
                })
                ->with(['users' => function ($quser1) {
                    $quser1->with(['sddextr' => function ($qsdd11) {
                        $qsdd11->select('INDEXNO', 'SEX');
                    }])
                        ->select('indexno', 'id', 'name');
                }])
                // ->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])
                ->with(['courses' => function ($qcourse1) {
                    $qcourse1->select('Te_Code_New', 'Description');
                }])
                ->with(['languages' => function ($qlang1) {
                    $qlang1->select('code', 'name', 'name_fr');
                }])
                ->with(['courseschedules' => function ($q1) {
                    $q1->with('prices')->with('courseduration');
                }])
                ->with('classrooms')
                ->whereHas('classrooms', function ($query1) {
                    $query1->whereNotNull('Tch_ID')
                        ->where('Tch_ID', '!=', 'TBD');
                })
                ->with(['enrolments' => function ($q11) use ($term) {
                    $q11->where('Term', $term)->whereNotNull('CodeIndexID')->select('INDEXID', 'id', 'profile');
                }])
                // ->with('enrolments')
                ->whereHas('enrolments', function ($query11) use ($term) {
                    $query11->where('Term', $term)
                        // ->where('is_self_pay_form', '1')
                        ->whereNotNull('CodeIndexID');
                })
                ->with('classrooms.teachers')
                ->with('organizations')
                // ->with('classrooms.courseSchedule.courseduration')
                // ->with('classrooms.courseSchedule.prices')
            ;
        }

        $records = $records->get();

        $pashFromPlacement = new Repo;
        foreach ($columns as $column) {
            if ($request->filled($column)) {
                $pashFromPlacement = $pashFromPlacement->where($column, $request->input($column));
            }
            $pashFromPlacement = $pashFromPlacement
                ->where('Term', $term)
                ->select('id', 'Term', 'DEPT', 'is_self_pay_form', 'Result', 'cancelled_but_not_billed', 'exclude_from_billing', 'deleted_at', 'INDEXID', 'INDEXID_old', 'Te_Code', 'Te_Code_old', 'Code', 'CodeClass', 'CodeIndexID', 'CodeIndexIDClass', 'CodeIndexID_old', 'L',)
                ->where(function ($queryCancelledNotBilled) {
                    $queryCancelledNotBilled->where('cancelled_but_not_billed', '!=', 1)->orWhereNull('cancelled_but_not_billed');
                })
                ->where(function ($queryExcluded) {
                    $queryExcluded->where('exclude_from_billing', '!=', 1)->orWhereNull('exclude_from_billing');
                })
                ->with(['users' => function ($quser0) {
                    $quser0->with(['sddextr' => function ($qsdd00) {
                        $qsdd00->select('INDEXNO', 'SEX');
                    }])
                        ->select('indexno', 'id', 'name');
                }])
                // ->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])
                ->with(['courses' => function ($qcourse0) {
                    $qcourse0->select('Te_Code_New', 'Description');
                }])
                ->with(['languages' => function ($qlang0) {
                    $qlang0->select('code', 'name', 'name_fr');
                }])
                ->with(['courseschedules' => function ($q0) {
                    $q0->with('prices')->with('courseduration');
                }])
                ->with('classrooms')
                ->whereHas('classrooms', function ($query0) {
                    $query0->whereNotNull('Tch_ID')
                        ->where('Tch_ID', '!=', 'TBD');
                })
                // ->with('placements')
                ->with(['placements' => function ($q00) use ($term) {
                    $q00->where('Term', $term)->whereNotNull('CodeIndexID')->select('INDEXID', 'id', 'profile');
                }])
                ->whereHas('placements', function ($query00) use ($term) {
                    $query00->where('Term', $term)
                        // ->where('is_self_pay_form', '1')
                        ->whereNotNull('CodeIndexID');
                })
                ->with('classrooms.teachers')
                ->with('organizations')
                // ->with('classrooms.courseSchedule.courseduration')
                // ->with('classrooms.courseSchedule.prices')
            ;
        }

        $pashFromPlacement = $pashFromPlacement->get();


        // MUST INCLUDE QUERY WHERE deleted_at > cancellation deadline
        $termCancelDeadline = Term::where('Term_Code', $term)->first()->Cancel_Date_Limit;
        $cancelledEnrolmentRecords = new Repo;
        foreach ($columns as $column) {
            if ($request->filled($column)) {
                $cancelledEnrolmentRecords = $cancelledEnrolmentRecords->where($column, $request->input($column));
            }
            $cancelledEnrolmentRecords = $cancelledEnrolmentRecords
                ->onlyTrashed()
                ->with(['users' => function ($quser2) {
                    $quser2->with(['sddextr' => function ($qsdd22) {
                        $qsdd22->select('INDEXNO', 'SEX');
                    }])
                        ->select('indexno', 'id', 'name');
                }])
                ->where('Term', $term)
                ->select('id', 'Term', 'DEPT', 'is_self_pay_form', 'Result', 'cancelled_but_not_billed', 'exclude_from_billing', 'deleted_at', 'INDEXID', 'INDEXID_old', 'Te_Code', 'Te_Code_old', 'Code', 'CodeClass', 'CodeIndexID', 'CodeIndexIDClass', 'CodeIndexID_old', 'L',)
                ->where(function ($queryCancelledNotBilled) {
                    $queryCancelledNotBilled->where('cancelled_but_not_billed', '!=', 1)->orWhereNull('cancelled_but_not_billed');
                })
                ->where(function ($queryExcluded) {
                    $queryExcluded->where('exclude_from_billing', '!=', 1)->orWhereNull('exclude_from_billing');
                })
                // ->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])
                ->where('deleted_at', '>', $termCancelDeadline)
                // ->whereNull('cancelled_but_not_billed')
                ->with(['courses' => function ($qcourse2) {
                    $qcourse2->select('Te_Code_New', 'Description');
                }])
                ->with(['languages' => function ($qlang2) {
                    $qlang2->select('code', 'name', 'name_fr');
                }])
                ->with(['courseschedules' => function ($q2) {
                    $q2->with('prices')->with('courseduration');
                }])
                ->with('classrooms')
                ->whereHas('classrooms', function ($query2) {
                    $query2->whereNotNull('Tch_ID')
                        ->where('Tch_ID', '!=', 'TBD');
                })
                // ->with('enrolments')
                ->with(['enrolments' => function ($q22) use ($term) {
                    $q22->where('Term', $term)->whereNotNull('CodeIndexID')->select('INDEXID', 'id', 'profile');
                }])
                ->whereHas('enrolments', function ($query22) use ($term) {
                    $query22->where('Term', $term)
                        // ->where('is_self_pay_form', '1')
                        ->whereNotNull('CodeIndexID');
                })
                ->with('classrooms.teachers')
                ->with('organizations')
                // ->with('classrooms.courseSchedule.courseduration')
                // ->with('classrooms.courseSchedule.prices')
            ;
        }

        $cancelledEnrolmentRecords = $cancelledEnrolmentRecords->get();

        $cancelledPlacementRecords = new Repo;
        foreach ($columns as $column) {
            if ($request->filled($column)) {
                $cancelledPlacementRecords = $cancelledPlacementRecords->where($column, $request->input($column));
            }
            $cancelledPlacementRecords = $cancelledPlacementRecords
                ->onlyTrashed()
                ->with(['users' => function ($quser3) {
                    $quser3->with(['sddextr' => function ($qsdd33) {
                        $qsdd33->select('INDEXNO', 'SEX');
                    }])
                        ->select('indexno', 'id', 'name');
                }])
                ->where('Term', $term)
                ->select('id', 'Term', 'DEPT', 'is_self_pay_form', 'Result', 'cancelled_but_not_billed', 'exclude_from_billing', 'deleted_at', 'INDEXID', 'INDEXID_old', 'Te_Code', 'Te_Code_old', 'Code', 'CodeClass', 'CodeIndexID', 'CodeIndexIDClass', 'CodeIndexID_old', 'L',)
                ->where(function ($queryCancelledNotBilled) {
                    $queryCancelledNotBilled->where('cancelled_but_not_billed', '!=', 1)->orWhereNull('cancelled_but_not_billed');
                })
                ->where(function ($queryExcluded) {
                    $queryExcluded->where('exclude_from_billing', '!=', 1)->orWhereNull('exclude_from_billing');
                })
                // ->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])
                ->where('deleted_at', '>', $termCancelDeadline)
                // ->whereNull('cancelled_but_not_billed')
                ->with(['courses' => function ($qcourse3) {
                    $qcourse3->select('Te_Code_New', 'Description');
                }])
                ->with(['languages' => function ($qlang3) {
                    $qlang3->select('code', 'name', 'name_fr');
                }])
                ->with(['courseschedules' => function ($q3) {
                    $q3->with('prices')->with('courseduration');
                }])
                ->with('classrooms')
                ->whereHas('classrooms', function ($query3) {
                    $query3->whereNotNull('Tch_ID')
                        ->where('Tch_ID', '!=', 'TBD');
                })
                // ->with('placements')
                ->with(['placements' => function ($query33) use ($term) {
                    $query33->where('Term', $term)->whereNotNull('CodeIndexID')->select('INDEXID', 'id', 'profile');
                }])
                ->whereHas('placements', function ($query33) use ($term) {
                    $query33->where('Term', $term)
                        // ->where('is_self_pay_form', '1')
                        ->whereNotNull('CodeIndexID');
                })
                ->with('classrooms.teachers')
                ->with('organizations')
                // ->with('classrooms.courseSchedule.courseduration')
                // ->with('classrooms.courseSchedule.prices')
            ;
        }

        $cancelledPlacementRecords = $cancelledPlacementRecords->get();

        $recordsMerged = $records->merge($pashFromPlacement)->merge($pashFromPlacement)->merge($cancelledEnrolmentRecords)->merge($cancelledPlacementRecords);

        yield $recordsMerged;
    }

    public function queryCustomBillingStudentsByYear($request, $columns)
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

        $arrayCollection = new \AppendIterator();
        foreach ($termCode as $term) {
            // 
            $recordsMerged = $this->queryAllStudentsMerged($term, $columns, $request);
            // 
            $arrayCollection->append($recordsMerged);
        }

        yield $arrayCollection;
    }

    public function reportAllStudentsPerYearOrTermView()
    {
        $terms = Term::orderBy('Term_Code', 'desc')->get();
        $queryTerm = Term::orderBy('Term_Code', 'desc')->get(['Term_Code', 'Term_Begin']);
        $languages = DB::table('languages')->pluck("name", "code")->all();

        $years = array_unique($this->getYears($queryTerm));

        return view('reports.reportAllStudentsPerYearOrTermView', compact('languages', 'terms', 'years'));
    }

    public function queryAllStudentsByYear($request, $columns)
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

        $arrayCollection = new \AppendIterator();
        foreach ($termCode as $term) {
            // 
            $recordsMerged = $this->queryAllStudentsMerged($term, $columns, $request);
            // 
            $arrayCollection->append($recordsMerged);
        }

        yield $arrayCollection;
    }

    public function queryAllStudentsMerged($term, $columns, $request)
    {
        $records = new Repo;
        foreach ($columns as $column) {
            if ($request->filled($column)) {
                $records = $records->where($column, $request->input($column));
            }
            $records = $records
                ->where('Term', $term)
                ->select('id', 'Term', 'DEPT', 'is_self_pay_form', 'Result', 'Written', 'Oral', 'Overall_Grade', 'cancelled_but_not_billed', 'exclude_from_billing', 'deleted_at', 'INDEXID', 'INDEXID_old', 'Te_Code', 'Te_Code_old', 'Code', 'CodeClass', 'CodeIndexID', 'CodeIndexIDClass', 'CodeIndexID_old', 'L',)
                ->with(['users' => function ($quser1) {
                    $quser1->with(['sddextr' => function ($qsdd11) {
                        $qsdd11->select('INDEXNO', 'SEX');
                    }])
                        ->select('indexno', 'id', 'name');
                }])
                // ->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])
                ->with(['courses' => function ($qcourse1) {
                    $qcourse1->select('Te_Code_New', 'Description');
                }])
                ->with(['languages' => function ($qlang1) {
                    $qlang1->select('code', 'name', 'name_fr');
                }])
                ->with(['courseschedules' => function ($q1) {
                    $q1->with('prices')->with('courseduration');
                }])
                ->with('classrooms')
                ->whereHas('classrooms', function ($query1) {
                    $query1->whereNotNull('Tch_ID')
                        ->where('Tch_ID', '!=', 'TBD');
                })
                ->with(['enrolments' => function ($q11) use ($term) {
                    $q11->where('Term', $term)->whereNotNull('CodeIndexID')->select('INDEXID', 'id', 'profile');
                }])
                // ->with('enrolments')
                ->whereHas('enrolments', function ($query11) use ($term) {
                    $query11->where('Term', $term)
                        // ->where('is_self_pay_form', '1')
                        ->whereNotNull('CodeIndexID');
                })
                ->with('classrooms.teachers')
                ->with('organizations')
                // ->with('classrooms.courseSchedule.courseduration')
                // ->with('classrooms.courseSchedule.prices')
            ;
        }

        $records = $records->get();

        $pashFromPlacement = new Repo;
        foreach ($columns as $column) {
            if ($request->filled($column)) {
                $pashFromPlacement = $pashFromPlacement->where($column, $request->input($column));
            }
            $pashFromPlacement = $pashFromPlacement
                ->where('Term', $term)
                ->select('id', 'Term', 'DEPT', 'is_self_pay_form', 'Result', 'cancelled_but_not_billed', 'exclude_from_billing', 'deleted_at', 'INDEXID', 'INDEXID_old', 'Te_Code', 'Te_Code_old', 'Code', 'CodeClass', 'CodeIndexID', 'CodeIndexIDClass', 'CodeIndexID_old', 'L',)
                ->with(['users' => function ($quser0) {
                    $quser0->with(['sddextr' => function ($qsdd00) {
                        $qsdd00->select('INDEXNO', 'SEX');
                    }])
                        ->select('indexno', 'id', 'name');
                }])
                // ->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])
                ->with(['courses' => function ($qcourse0) {
                    $qcourse0->select('Te_Code_New', 'Description');
                }])
                ->with(['languages' => function ($qlang0) {
                    $qlang0->select('code', 'name', 'name_fr');
                }])
                ->with(['courseschedules' => function ($q0) {
                    $q0->with('prices')->with('courseduration');
                }])
                ->with('classrooms')
                ->whereHas('classrooms', function ($query0) {
                    $query0->whereNotNull('Tch_ID')
                        ->where('Tch_ID', '!=', 'TBD');
                })
                // ->with('placements')
                ->with(['placements' => function ($q00) use ($term) {
                    $q00->where('Term', $term)->whereNotNull('CodeIndexID')->select('INDEXID', 'id', 'profile');
                }])
                ->whereHas('placements', function ($query00) use ($term) {
                    $query00->where('Term', $term)
                        // ->where('is_self_pay_form', '1')
                        ->whereNotNull('CodeIndexID');
                })
                ->with('classrooms.teachers')
                ->with('organizations')
                // ->with('classrooms.courseSchedule.courseduration')
                // ->with('classrooms.courseSchedule.prices')
            ;
        }

        $pashFromPlacement = $pashFromPlacement->get();


        // MUST INCLUDE QUERY WHERE deleted_at > cancellation deadline
        $termCancelDeadline = Term::where('Term_Code', $term)->first()->Cancel_Date_Limit;
        $cancelledEnrolmentRecords = new Repo;
        foreach ($columns as $column) {
            if ($request->filled($column)) {
                $cancelledEnrolmentRecords = $cancelledEnrolmentRecords->where($column, $request->input($column));
            }
            $cancelledEnrolmentRecords = $cancelledEnrolmentRecords
                ->onlyTrashed()
                ->with(['users' => function ($quser2) {
                    $quser2->with(['sddextr' => function ($qsdd22) {
                        $qsdd22->select('INDEXNO', 'SEX');
                    }])
                        ->select('indexno', 'id', 'name');
                }])
                ->where('Term', $term)
                ->select('id', 'Term', 'DEPT', 'is_self_pay_form', 'Result', 'cancelled_but_not_billed', 'exclude_from_billing', 'deleted_at', 'INDEXID', 'INDEXID_old', 'Te_Code', 'Te_Code_old', 'Code', 'CodeClass', 'CodeIndexID', 'CodeIndexIDClass', 'CodeIndexID_old', 'L',)
                // ->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])
                ->where('deleted_at', '>', $termCancelDeadline)
                // ->whereNull('cancelled_but_not_billed')
                ->with(['courses' => function ($qcourse2) {
                    $qcourse2->select('Te_Code_New', 'Description');
                }])
                ->with(['languages' => function ($qlang2) {
                    $qlang2->select('code', 'name', 'name_fr');
                }])
                ->with(['courseschedules' => function ($q2) {
                    $q2->with('prices')->with('courseduration');
                }])
                ->with('classrooms')
                ->whereHas('classrooms', function ($query2) {
                    $query2->whereNotNull('Tch_ID')
                        ->where('Tch_ID', '!=', 'TBD');
                })
                // ->with('enrolments')
                ->with(['enrolments' => function ($q22) use ($term) {
                    $q22->where('Term', $term)->whereNotNull('CodeIndexID')->select('INDEXID', 'id', 'profile');
                }])
                ->whereHas('enrolments', function ($query22) use ($term) {
                    $query22->where('Term', $term)
                        // ->where('is_self_pay_form', '1')
                        ->whereNotNull('CodeIndexID');
                })
                ->with('classrooms.teachers')
                ->with('organizations')
                // ->with('classrooms.courseSchedule.courseduration')
                // ->with('classrooms.courseSchedule.prices')
            ;
        }

        $cancelledEnrolmentRecords = $cancelledEnrolmentRecords->get();

        $cancelledPlacementRecords = new Repo;
        foreach ($columns as $column) {
            if ($request->filled($column)) {
                $cancelledPlacementRecords = $cancelledPlacementRecords->where($column, $request->input($column));
            }
            $cancelledPlacementRecords = $cancelledPlacementRecords
                ->onlyTrashed()
                ->with(['users' => function ($quser3) {
                    $quser3->with(['sddextr' => function ($qsdd33) {
                        $qsdd33->select('INDEXNO', 'SEX');
                    }])
                        ->select('indexno', 'id', 'name');
                }])
                ->where('Term', $term)
                ->select('id', 'Term', 'DEPT', 'is_self_pay_form', 'Result', 'cancelled_but_not_billed', 'exclude_from_billing', 'deleted_at', 'INDEXID', 'INDEXID_old', 'Te_Code', 'Te_Code_old', 'Code', 'CodeClass', 'CodeIndexID', 'CodeIndexIDClass', 'CodeIndexID_old', 'L',)
                // ->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])
                ->where('deleted_at', '>', $termCancelDeadline)
                // ->whereNull('cancelled_but_not_billed')
                ->with(['courses' => function ($qcourse3) {
                    $qcourse3->select('Te_Code_New', 'Description');
                }])
                ->with(['languages' => function ($qlang3) {
                    $qlang3->select('code', 'name', 'name_fr');
                }])
                ->with(['courseschedules' => function ($q3) {
                    $q3->with('prices')->with('courseduration');
                }])
                ->with('classrooms')
                ->whereHas('classrooms', function ($query3) {
                    $query3->whereNotNull('Tch_ID')
                        ->where('Tch_ID', '!=', 'TBD');
                })
                // ->with('placements')
                ->with(['placements' => function ($query33) use ($term) {
                    $query33->where('Term', $term)->whereNotNull('CodeIndexID')->select('INDEXID', 'id', 'profile');
                }])
                ->whereHas('placements', function ($query33) use ($term) {
                    $query33->where('Term', $term)
                        // ->where('is_self_pay_form', '1')
                        ->whereNotNull('CodeIndexID');
                })
                ->with('classrooms.teachers')
                ->with('organizations')
                // ->with('classrooms.courseSchedule.courseduration')
                // ->with('classrooms.courseSchedule.prices')
            ;
        }

        $cancelledPlacementRecords = $cancelledPlacementRecords->get();

        $recordsMerged = $records->merge($pashFromPlacement)->merge($pashFromPlacement)->merge($cancelledEnrolmentRecords)->merge($cancelledPlacementRecords);

        yield $recordsMerged;
    }

    public function reportAllStudentsPerYearOrTerm(Request $request)
    {
        if ($request->ajax()) {
            $columns = [
                'L'
            ];
            $arr = [];
            if ($request->Term) {
                $term = $request->Term;
                $recordsMerged = $this->queryAllStudentsMerged($term, $columns, $request);
                foreach ($recordsMerged as $v) {
                    foreach ($v as $value) {
                        $arr[] = $value;
                    }
                }
                $data = $arr;
            }

            if ($request->year) {
                $arrayCollection = $this->queryAllStudentsByYear($request, $columns);
                foreach ($arrayCollection as $dataValue) {
                    foreach ($dataValue as $v) {
                        foreach ($v as $value) {
                            $arr[] = $value;
                        }
                    }
                }
                $data = $arr;
            }

            return response()->json(['data' => $data]);
        }
    }

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

        $obj = [];
        foreach ($termsArray as $term) {
            if ($term < 190) {
                $qryStudentsBefore2019 = Repo::where('Term', $term)->get()->count();
                $obj[] = (object) [
                    'term' => $term,
                    'count' => $qryStudentsBefore2019,
                ];
            } else {
                $qryStudents = Repo::where('Term', $term)
                    ->whereHas('classrooms', function ($q) {
                        // query all students enrolled to current term excluding waitlisted
                        $q->whereNotNull('Tch_ID')->where('Tch_ID', '!=', 'TBD');
                    })
                    ->get()->count();
                $obj[] = (object) [
                    'term' => $term,
                    'count' => $qryStudents,
                ];
            }
        }

        return response()->json($obj);
    }

    public function getYears($queryTerm)
    {
        $years = [];
        foreach ($queryTerm as $key => $value) {
            $years[] = Carbon::parse($value->Term_Begin)->year;
        }

        return $years;
    }

    public function baseView()
    {
        $orgs = Torgan::orderBy('Org name', 'asc')->get(['Org name', 'Org Full Name', 'OrgCode']);
        $languages = DB::table('languages')->pluck("name", "code")->all();
        $terms = Term::orderBy('Term_Code', 'desc')->get(['Term_Code', 'Term_Name', 'Comments']);
        $queryTerm = Term::orderBy('Term_Code', 'desc')->get(['Term_Code', 'Term_Begin']);

        $years = array_unique($this->getYears($queryTerm));

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
                    ->with('users.sddextr')
                    ->with('classrooms.teachers')
                    ->with('classrooms.courseSchedule.courseduration')
                    ->with('classrooms.courseSchedule.prices');
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
                    ->with('users.sddextr')
                    ->with('classrooms.teachers')
                    ->with('classrooms.courseSchedule.courseduration')
                    ->with('classrooms.courseSchedule.prices');
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

    /**
     * Number of cancellations term/language 
     * Outside of cancellation deadline
     */
    public function cancelledTermLanguage()
    {
        $container = [];
        $langArray = ['A', 'C', 'E', 'F', 'R', 'S'];
        $terms = Term::where('Term_Code', '>=', '191')->get();
        $termContainer = [];
        $combineContainer = [];
        foreach ($terms as $term) {
            foreach ($langArray as $lang) {
                $recordsCancelled = new Repo;
                $termCancelDeadline = Term::where('Term_Code', $term->Term_Code)->first()->Cancel_Date_Limit;
                $recordsCancelled = $recordsCancelled->withTrashed()
                    ->where('deleted_at', '>', $termCancelDeadline)
                    ->where('Term', $term->Term_Code)
                    ->where('L', $lang);
                $container[] = $recordsCancelled->count();
            }
            $combine = array_combine($langArray, $container);
            $combineContainer[] = $combine;
            $termContainer[] = [$term->Term_Code => $combine];
            $container = [];
        }

        $data = $termContainer;
        return response()->json(['data' => $data]);
    }

    public function coursesTermLanguage()
    {
        $termsGte2019 = Term::select('Term_Code')->where('Term_Code', '>=', '191')->get()->unique();
        $container = [];
        foreach ($termsGte2019 as $key => $term) {
            $class2019 = Classroom::where('Te_Term', $term->Term_Code)->whereNotNull('Tch_ID');
            $counter = $class2019->count();
            $container[] = [
                $term->Term_Code => $counter

            ];
        }

        $termsLt2019 = Term::select('Term_Code')->where('Term_Code', '<', '191')->get()->unique();


        dd($termsLt2019, $container);
        return 'courses';
    }

    public function classesTermLanguage()
    {
        $termsGte2019 = Term::select('Term_Code')->where('Term_Code', '>=', '191')->get()->unique();
        $termsLt2019 = Term::select('Term_Code')->where('Term_Code', '<', '191')->get()->unique();

        $containerTerms = [];
        foreach ($termsGte2019 as $valueTermCode) {
            $containerTerms[] = $valueTermCode->Term_Code;
        }

        $languagesCollection = DB::table('languages')->select('id', 'name', 'code')->orderBy('id', 'asc')->get();
        $languages = $languagesCollection->pluck(['name']);

        $container = [];
        foreach ($termsGte2019 as $term) {
            $class2019 = Classroom::where('Te_Term', $term->Term_Code)->whereNotNull('Tch_ID')->where('Tch_ID', '!=', 'TBD');
            $counter = $class2019->count();
            $container[] = [
                $term->Term_Code => $counter
            ];
        }

        $containerArab = [];
        foreach ($termsGte2019 as $term) {
            $class2019 = Classroom::where('Te_Term', $term->Term_Code)->whereNotNull('Tch_ID')->where('Tch_ID', '!=', 'TBD')->where('L', 'A');
            $counterArab = $class2019->count();
            $containerArab[] = $counterArab;
        }

        $containerChinese = [];
        foreach ($termsGte2019 as $term) {
            $class2019 = Classroom::where('Te_Term', $term->Term_Code)->whereNotNull('Tch_ID')->where('Tch_ID', '!=', 'TBD')->where('L', 'C');
            $counterChinese = $class2019->count();
            $containerChinese[] = $counterChinese;
        }

        $containerEnglish = [];
        foreach ($termsGte2019 as $term) {
            $class2019 = Classroom::where('Te_Term', $term->Term_Code)->whereNotNull('Tch_ID')->where('Tch_ID', '!=', 'TBD')->where('L', 'E');
            $counterEnglish = $class2019->count();
            $containerEnglish[] = $counterEnglish;
        }

        $containerFrench = [];
        foreach ($termsGte2019 as $term) {
            $class2019 = Classroom::where('Te_Term', $term->Term_Code)->whereNotNull('Tch_ID')->where('Tch_ID', '!=', 'TBD')->where('L', 'F');
            $counterFrench = $class2019->count();
            $containerFrench[] = $counterFrench;
        }

        $containerRussian = [];
        foreach ($termsGte2019 as $term) {
            $class2019 = Classroom::where('Te_Term', $term->Term_Code)->whereNotNull('Tch_ID')->where('Tch_ID', '!=', 'TBD')->where('L', 'R');
            $counterRussian = $class2019->count();
            $containerRussian[] = $counterRussian;
        }

        $containerSpanish = [];
        foreach ($termsGte2019 as $term) {
            $class2019 = Classroom::where('Te_Term', $term->Term_Code)->whereNotNull('Tch_ID')->where('Tch_ID', '!=', 'TBD')->where('L', 'S');
            $counterSpanish = $class2019->count();
            $containerSpanish[] = $counterSpanish;
        }

        $records = array_map(null, $containerArab, $containerChinese, $containerEnglish, $containerFrench, $containerRussian, $containerSpanish);

        $obj = (object) [
            'title' => 'Number of Classrooms Created/Generated per Language per Term',
            'labelTerms' => $containerTerms,
            'xAxis' => $languages,
            'classesPerTerm' => $records,
        ];

        $data = $obj;
        return response()->json(['data' => $data]);
    }

    public function totalClassesPerTerm()
    {
        $termsGte2019 = Term::select('Term_Code')->where('Term_Code', '>=', '191')->get()->unique();

        $containerTerms = [];
        foreach ($termsGte2019 as $valueTermCode) {
            $containerTerms[] = $valueTermCode->Term_Code;
        }

        $container = [];
        foreach ($termsGte2019 as $term) {
            $class2019 = Classroom::where('Te_Term', $term->Term_Code)->whereNotNull('Tch_ID')->where('Tch_ID', '!=', 'TBD');
            $counter = $class2019->count();
            $container[] = $counter;
        }

        $mergedArrayRegistrations = $container;

        $obj = (object) [
            'title' => 'Total Number of Classrooms Created per Term',
            'labelYears' => $containerTerms,
            'regSum' => $mergedArrayRegistrations
        ];


        $data = $obj;

        return response()->json(['data' => $data]);
    }

    public function reportByOrgAdminView()
    {
        $terms = Term::orderBy('Term_Code', 'desc')->get();
        $queryTerm = Term::orderBy('Term_Code', 'desc')->get(['Term_Code', 'Term_Begin']);
        $years = array_unique($this->getYears($queryTerm));
        $orgs = Torgan::orderBy('Org name', 'asc')->get(['Org name', 'Org Full Name', 'OrgCode']);

        return view('reports.reportByOrgAdminView', compact('terms', 'years', 'orgs'));
    }

    public function reportByOrgAdmin(Request $request)
    {
        if ($request->ajax()) {
            $columns = [
                'DEPT'
            ];
            $arr = [];
            if ($request->Term) {
                $term = $request->Term;
                $recordsMerged = $this->queryStudentsByOrgByTermMerged($term, $columns, $request);
                foreach ($recordsMerged as $v) {
                    foreach ($v as $value) {
                        $arr[] = $value;
                    }
                }
                $data = $arr;
            }

            if ($request->year) {
                $arrayCollection = $this->queryStudentsByOrgByYear($request, $columns);
                foreach ($arrayCollection as $dataValue) {
                    foreach ($dataValue as $v) {
                        foreach ($v as $value) {
                            $arr[] = $value;
                        }
                    }
                }
                $data = $arr;
            }

            return response()->json(['data' => $data]);
        }
    }

    public function queryStudentsByOrgByYear($request, $columns)
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

        $arrayCollection = new \AppendIterator();
        foreach ($termCode as $term) {
            // 
            $recordsMerged = $this->queryStudentsByOrgByTermMerged($term, $columns, $request);
            // 
            $arrayCollection->append($recordsMerged);
        }

        yield $arrayCollection;
    }

    public function queryStudentsByOrgByTermMerged($term, $columns, $request)
    {
        $records = new Repo;
        foreach ($columns as $column) {
            if ($request->filled($column)) {
                $records = $records->where($column, $request->input($column));
            }
            $records = $records
                ->where('Term', $term)
                ->select('id', 'Term', 'DEPT', 'is_self_pay_form', 'Result', 'Written', 'Oral', 'Overall_Grade', 'cancelled_but_not_billed', 'exclude_from_billing', 'deleted_at', 'INDEXID', 'INDEXID_old', 'Te_Code', 'Te_Code_old', 'Code', 'CodeClass', 'CodeIndexID', 'CodeIndexIDClass', 'CodeIndexID_old', 'L',)
                ->with(['users' => function ($quser1) {
                    $quser1->with(['sddextr' => function ($qsdd11) {
                        $qsdd11->select('INDEXNO', 'SEX');
                    }])
                        ->select('indexno', 'id', 'name', 'nameLast', 'nameFirst');
                }])
                // ->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])
                ->whereNull('exclude_from_billing')
                ->whereNull('is_self_pay_form')
                ->with(['courses' => function ($qcourse1) {
                    $qcourse1->select('Te_Code_New', 'Description');
                }])
                ->with(['languages' => function ($qlang1) {
                    $qlang1->select('code', 'name', 'name_fr');
                }])
                ->with(['courseschedules' => function ($q1) {
                    $q1->with('prices')->with('courseduration');
                }])
                ->with('classrooms')
                ->whereHas('classrooms', function ($query1) {
                    $query1->whereNotNull('Tch_ID')
                        ->where('Tch_ID', '!=', 'TBD');
                })
                ->with(['enrolments' => function ($q11) use ($term) {
                    $q11->where('Term', $term)->whereNotNull('CodeIndexID')->select('INDEXID', 'id', 'profile');
                }])
                // ->with('enrolments')
                ->whereHas('enrolments', function ($query11) use ($term) {
                    $query11->where('Term', $term)
                        ->whereNull('is_self_pay_form')
                        ->whereNotNull('CodeIndexID');
                })
                ->with('classrooms.teachers')
                ->with('organizations')
                ->with('attendances')
                // ->with('classrooms.courseSchedule.courseduration')
                // ->with('classrooms.courseSchedule.prices')
            ;
        }

        $records = $records->get();

        $pashFromPlacement = new Repo;
        foreach ($columns as $column) {
            if ($request->filled($column)) {
                $pashFromPlacement = $pashFromPlacement->where($column, $request->input($column));
            }
            $pashFromPlacement = $pashFromPlacement
                ->where('Term', $term)
                ->select('id', 'Term', 'DEPT', 'is_self_pay_form', 'Result', 'cancelled_but_not_billed', 'exclude_from_billing', 'deleted_at', 'INDEXID', 'INDEXID_old', 'Te_Code', 'Te_Code_old', 'Code', 'CodeClass', 'CodeIndexID', 'CodeIndexIDClass', 'CodeIndexID_old', 'L',)
                ->with(['users' => function ($quser0) {
                    $quser0->with(['sddextr' => function ($qsdd00) {
                        $qsdd00->select('INDEXNO', 'SEX');
                    }])
                        ->select('indexno', 'id', 'name', 'nameLast', 'nameFirst');
                }])
                // ->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])
                ->whereNull('exclude_from_billing')
                ->whereNull('is_self_pay_form')
                ->with(['courses' => function ($qcourse0) {
                    $qcourse0->select('Te_Code_New', 'Description');
                }])
                ->with(['languages' => function ($qlang0) {
                    $qlang0->select('code', 'name', 'name_fr');
                }])
                ->with(['courseschedules' => function ($q0) {
                    $q0->with('prices')->with('courseduration');
                }])
                ->with('classrooms')
                ->whereHas('classrooms', function ($query0) {
                    $query0->whereNotNull('Tch_ID')
                        ->where('Tch_ID', '!=', 'TBD');
                })
                // ->with('placements')
                ->with(['placements' => function ($q00) use ($term) {
                    $q00->where('Term', $term)->whereNotNull('CodeIndexID')->select('INDEXID', 'id', 'profile');
                }])
                ->whereHas('placements', function ($query00) use ($term) {
                    $query00->where('Term', $term)
                        ->whereNull('is_self_pay_form')
                        ->whereNotNull('CodeIndexID');
                })
                ->with('classrooms.teachers')
                ->with('organizations')
                ->with('attendances')
                // ->with('classrooms.courseSchedule.courseduration')
                // ->with('classrooms.courseSchedule.prices')
            ;
        }

        $pashFromPlacement = $pashFromPlacement->get();


        // MUST INCLUDE QUERY WHERE deleted_at > cancellation deadline
        $termCancelDeadline = Term::where('Term_Code', $term)->first()->Cancel_Date_Limit;
        $cancelledEnrolmentRecords = new Repo;
        foreach ($columns as $column) {
            if ($request->filled($column)) {
                $cancelledEnrolmentRecords = $cancelledEnrolmentRecords->where($column, $request->input($column));
            }
            $cancelledEnrolmentRecords = $cancelledEnrolmentRecords
                ->onlyTrashed()
                ->with(['users' => function ($quser2) {
                    $quser2->with(['sddextr' => function ($qsdd22) {
                        $qsdd22->select('INDEXNO', 'SEX');
                    }])
                        ->select('indexno', 'id', 'name', 'nameLast', 'nameFirst');
                }])
                ->where('Term', $term)
                ->select('id', 'Term', 'DEPT', 'is_self_pay_form', 'Result', 'cancelled_but_not_billed', 'exclude_from_billing', 'deleted_at', 'INDEXID', 'INDEXID_old', 'Te_Code', 'Te_Code_old', 'Code', 'CodeClass', 'CodeIndexID', 'CodeIndexIDClass', 'CodeIndexID_old', 'L',)
                // ->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])
                ->where('deleted_at', '>', $termCancelDeadline)
                // ->whereNull('cancelled_but_not_billed')
                ->whereNull('exclude_from_billing')
                ->whereNull('cancelled_but_not_billed')
                ->whereNull('is_self_pay_form')
                ->with(['courses' => function ($qcourse2) {
                    $qcourse2->select('Te_Code_New', 'Description');
                }])
                ->with(['languages' => function ($qlang2) {
                    $qlang2->select('code', 'name', 'name_fr');
                }])
                ->with(['courseschedules' => function ($q2) {
                    $q2->with('prices')->with('courseduration');
                }])
                ->with('classrooms')
                ->whereHas('classrooms', function ($query2) {
                    $query2->whereNotNull('Tch_ID')
                        ->where('Tch_ID', '!=', 'TBD');
                })
                // ->with('enrolments')
                ->with(['enrolments' => function ($q22) use ($term) {
                    $q22->where('Term', $term)->whereNotNull('CodeIndexID')->select('INDEXID', 'id', 'profile');
                }])
                ->whereHas('enrolments', function ($query22) use ($term) {
                    $query22->where('Term', $term)
                        ->whereNull('is_self_pay_form')
                        ->whereNotNull('CodeIndexID');
                })
                ->with('classrooms.teachers')
                ->with('organizations')
                // ->with('classrooms.courseSchedule.courseduration')
                // ->with('classrooms.courseSchedule.prices')
            ;
        }

        $cancelledEnrolmentRecords = $cancelledEnrolmentRecords->get();

        $cancelledPlacementRecords = new Repo;
        foreach ($columns as $column) {
            if ($request->filled($column)) {
                $cancelledPlacementRecords = $cancelledPlacementRecords->where($column, $request->input($column));
            }
            $cancelledPlacementRecords = $cancelledPlacementRecords
                ->onlyTrashed()
                ->with(['users' => function ($quser3) {
                    $quser3->with(['sddextr' => function ($qsdd33) {
                        $qsdd33->select('INDEXNO', 'SEX');
                    }])
                        ->select('indexno', 'id', 'name', 'nameLast', 'nameFirst');
                }])
                ->where('Term', $term)
                ->select('id', 'Term', 'DEPT', 'is_self_pay_form', 'Result', 'cancelled_but_not_billed', 'exclude_from_billing', 'deleted_at', 'INDEXID', 'INDEXID_old', 'Te_Code', 'Te_Code_old', 'Code', 'CodeClass', 'CodeIndexID', 'CodeIndexIDClass', 'CodeIndexID_old', 'L',)
                // ->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])
                ->where('deleted_at', '>', $termCancelDeadline)
                // ->whereNull('cancelled_but_not_billed')
                ->whereNull('exclude_from_billing')
                ->whereNull('cancelled_but_not_billed')
                ->whereNull('is_self_pay_form')
                ->with(['courses' => function ($qcourse3) {
                    $qcourse3->select('Te_Code_New', 'Description');
                }])
                ->with(['languages' => function ($qlang3) {
                    $qlang3->select('code', 'name', 'name_fr');
                }])
                ->with(['courseschedules' => function ($q3) {
                    $q3->with('prices')->with('courseduration');
                }])
                ->with('classrooms')
                ->whereHas('classrooms', function ($query3) {
                    $query3->whereNotNull('Tch_ID')
                        ->where('Tch_ID', '!=', 'TBD');
                })
                // ->with('placements')
                ->with(['placements' => function ($query33) use ($term) {
                    $query33->where('Term', $term)->whereNotNull('CodeIndexID')->select('INDEXID', 'id', 'profile');
                }])
                ->whereHas('placements', function ($query33) use ($term) {
                    $query33->where('Term', $term)
                        ->whereNull('is_self_pay_form')
                        ->whereNotNull('CodeIndexID');
                })
                ->with('classrooms.teachers')
                ->with('organizations')
                // ->with('classrooms.courseSchedule.courseduration')
                // ->with('classrooms.courseSchedule.prices')
            ;
        }

        $cancelledPlacementRecords = $cancelledPlacementRecords->get();

        $recordsMerged = $records->merge($pashFromPlacement)->merge($pashFromPlacement)->merge($cancelledEnrolmentRecords)->merge($cancelledPlacementRecords);

        yield $recordsMerged;
    }

    public function sendEmailReportByOrg(Request $request)
    {
        $term = $request->Term;
        $year = $request->year;
        $org = $request->DEPT;
        $deadline = $request->deadline;

        // validate if organization has focal points
        $torgan = Torgan::where('Org name', $org)->first();
        // $learning_partner = $torgan->has_learning_partner;
        $learning_partner = FocalPoints::where('org_id', $torgan->OrgCode)->get(['email']);


        // if no, return error message to admin that there are no focal points
        if ($learning_partner->isEmpty()) {
            $data = 0;
            return response()->json(['data' => $data]);
        }
        // if yes, email the focal points from database table
        //use map function to iterate through the collection and store value of email to var $org_email
        //subjects each value to a callback function
        $org_email = $learning_partner->map(function ($val, $key) {
            return $val->email;
        });
        //make collection to array
        $org_email_arr = $org_email->toArray();

        //send email to array of email addresses $org_email_arr
        // link expires after 2 years? confirm with secretariat
        // control if year or term will be passed to the emailer class
        if ($request->year) {
            // $term_extrapolated =
            //     $term_qry_first = Term::where('Term_Code', $term_extrapolated)->first();

            $term_name_string = $term_qry_first->Comments;


            $queryCancelDateLimit = $term_qry_first->Cancel_Date_Limit;
            $cancel_date_limit = new Carbon($queryCancelDateLimit);
            $cancel_date_limit->subDay();

            // cancel limit date convert to string
            $cancel_date_limit_string = date('d F Y', strtotime($cancel_date_limit));

            $queryTermBegin = $term_qry_first->Term_Begin;
            $term_begin_date = new Carbon($queryTermBegin);
            $term_begin_date->subDay();

            $term_year_string = date('Y', strtotime($term_begin_date));

            $param = 'year';
            Mail::to($org_email_arr)
                ->send(new EmailReportByOrg($url, $param, $org, $term, $year, $term_name_string, $term_year_string, $cancel_date_limit_string, $deadline));
        } else {
            $term_qry_first = Term::where('Term_Code', $term)->first();

            $term_name_string = $term_qry_first->Comments;

            $queryCancelDateLimit = $term_qry_first->Cancel_Date_Limit;
            $cancel_date_limit = new Carbon($queryCancelDateLimit);
            $cancel_date_limit->subDay();

            // cancel limit date convert to string
            $cancel_date_limit_string = date('d F Y', strtotime($cancel_date_limit));

            $queryTermBegin = $term_qry_first->Term_Begin;
            $term_begin_date = new Carbon($queryTermBegin);
            $term_begin_date->subDay();

            $term_year_string = date('Y', strtotime($term_begin_date));

            $param = 'Term';

            $url = URL::temporarySignedRoute('report-by-org', now()->addYears(2), [Crypt::encrypt($param), Crypt::encrypt($org), Crypt::encrypt($term), Crypt::encrypt($year)]);
            // $url = route('report-by-org', [Crypt::encrypt($param), Crypt::encrypt($org), Crypt::encrypt($term), Crypt::encrypt($year)]);

            Mail::to($org_email_arr)
                ->send(new EmailReportByOrg($url, $param, $org, $term, $year, $term_name_string, $term_year_string, $cancel_date_limit_string, $deadline));
        }

        $data = 1;

        return response()->json(['data' => $data]);
    }

    public function reportByOrgEmailAjax(Request $request)
    {
        $term = $request->Term;
        $year = $request->year;
        $org = $request->DEPT;
        $deadline = $request->deadline;

        // validate if organization has focal points
        $torgan = Torgan::where('Org name', $org)->first();
        // $learning_partner = $torgan->has_learning_partner;
        $learning_partner = FocalPoints::where('org_id', $torgan->OrgCode)->get(['email']);
        // if no, return error message to admin that there are no focal points
        if ($learning_partner->isEmpty()) {
            $data = 0;
            return response()->json(['data' => $data]);
        }

        $url = route('reports/report-by-org-email-view', ['DEPT' => $org, 'Term' => $term, 'year' => $year, 'deadline' => $deadline]);

        $data = $url;

        return response()->json(['data' => $data]);
    }

    public function reportByOrgEmailView(Request $request)
    {
        $term = $request->Term;
        $year = $request->year;
        $org = $request->DEPT;
        $deadline = $request->deadline;

        $term_qry_first = Term::where('Term_Code', $term)->first();

        $term_name_string = $term_qry_first->Comments;

        $queryCancelDateLimit = $term_qry_first->Cancel_Date_Limit;
        $cancel_date_limit = new Carbon($queryCancelDateLimit);
        $cancel_date_limit->subDay();

        // cancel limit date convert to string
        $cancel_date_limit_string = date('d F Y', strtotime($cancel_date_limit));

        $queryTermBegin = $term_qry_first->Term_Begin;
        $term_begin_date = new Carbon($queryTermBegin);
        $term_begin_date->subDay();

        $term_year_string = date('Y', strtotime($term_begin_date));

        $param = 'Term';

        $url = URL::temporarySignedRoute('report-by-org', now()->addYears(2), [Crypt::encrypt($param), Crypt::encrypt($org), Crypt::encrypt($term), Crypt::encrypt($year)]);
        // $url = route('report-by-org', [Crypt::encrypt($param), Crypt::encrypt($org), Crypt::encrypt($term), Crypt::encrypt($year)]);

        return view('emails.emailReportByOrgByTerm', compact('url', 'param', 'org', 'term', 'year', 'term_name_string', 'term_year_string', 'cancel_date_limit_string', 'deadline'));
    }

    public function reportByOrg(Request $request, $param, $org, $term, $year)
    {
        try {
            if (!$request->hasValidSignature()) {
                return "The link has expired! There is no valid signature in the link.";
            }
            $param = Crypt::decrypt($param);
            $org = Crypt::decrypt($org);
            $term = Crypt::decrypt($term);
            $year = Crypt::decrypt($year);

            return view('reports.reportByOrg', compact('param', 'org', 'term', 'year'));
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
}
