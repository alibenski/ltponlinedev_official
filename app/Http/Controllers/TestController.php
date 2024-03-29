<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\PlacementForm;
use App\Preenrolment;
use Illuminate\Http\Request;
use App\User;
use App\Repo;
use App\Term;
use Session;

class TestController extends Controller
{
    public function captcha()
    {
        return view('captcha');
    }

    public function postCaptcha(Request $request)
    {
        $this->validate($request, array(
            'g-recaptcha-response' => 'required|captcha',
        ));

        return "success";
    }

    public function testQuery()
    {
        $qry = Attendance::where('pash_id', 130337)->with('pashRecord')->get();
        $qryFirst = Attendance::where('pash_id', 130337)->with('pashRecord')->first();

        // if no attendance has been entered yet, then 0 value
        if ($qry->isEmpty()) {

            $data = 0;
            return response()->json($data);
        }

        $array_attributes = [];
        foreach ($qry as $key => $value) {
            $arr = $value;
            $array_attributes[] = $arr->getAttributes();
        }

        $sumP = [];
        $sumE = [];
        $sumA = [];
        $info = [];
        $collector = [];
        if ($qryFirst->pashRecord->Term < 240) {
            foreach ($array_attributes as $x => $y) {
                $info['pash_id'] = $y['pash_id'];

                foreach ($y as $k => $v) {
                    if (substr($k, 0, 4) != "Wk1_") {
                        if ($v == 'P') {
                            $sumP[] = 'P';
                        }
                    }
                    if (substr($k, 0, 4) != "Wk1_") {
                        if ($v == 'E') {
                            $sumE[] = 'E';
                        }
                    }
                    if (substr($k, 0, 4) != "Wk1_") {
                        if ($v == 'A') {
                            $sumA[] = 'A';
                        }
                    }
                }

                $info['P'] = count($sumP);
                $info['E'] = count($sumE);
                $info['A'] = count($sumA);

                $collector[] = $info;
                // clear contents of array for the next loop
                $sumP = [];
                $sumE = [];
                $sumA = [];
            }
        } else {
            foreach ($array_attributes as $x => $y) {
                $info['pash_id'] = $y['pash_id'];

                foreach ($y as $k => $v) {
                    if ($v == 'P') {
                        $sumP[] = 'P';
                    }
                    if ($v == 'E') {
                        $sumE[] = 'E';
                    }
                    if ($v == 'A') {
                        $sumA[] = 'A';
                    }
                }

                $info['P'] = count($sumP);
                $info['E'] = count($sumE);
                $info['A'] = count($sumA);

                $collector[] = $info;
                // clear contents of array for the next loop
                $sumP = [];
                $sumE = [];
                $sumA = [];
            }
        }

        // $array_attributes2 = [];
        // foreach ($qry as $key2 => $value2) {
        //     $arr2 = $value2;
        //     $array_attributes2[] = $arr2->getAttributes();
        // }

        // $sumP2 = [];
        // $sumE = [];
        // $sumA = [];
        // $info2 = [];
        // $collector2 = [];
        // $counter = [];
        // foreach ($array_attributes2 as $x2 => $y2) {
        //     $info2['pash_id'] = $y2['pash_id'];

        //     foreach ($y2 as $k2 => $v2) {
        //         if ($k2 == 'Wk1_1') {
        //             if ($v2 != null) {
        //                 $counter[] = 1;
        //             }
        //         }
        //         if ($k2 == 'Wk1_2') {
        //             if ($v2 != null) {
        //                 $counter[] = 1;
        //             }
        //         }
        //         if ($k2 == 'Wk1_3') {
        //             if ($v2 != null) {
        //                 $counter[] = 1;
        //             }
        //         }
        //         if ($k2 == 'Wk1_4') {
        //             if ($v2 != null) {
        //                 $counter[] = 1;
        //             }
        //         }
        //         if ($k2 == 'Wk1_5') {
        //             if ($v2 != null) {
        //                 $counter[] = 1;
        //             }
        //         }
        //     }

        //     $info2[] = count($counter);
        // }

        $data = $collector;

        return $data;
    }
    // public function testQuery($term = "214")
    // {
    //     $prev_term = Term::where('Term_Code', 234)->first()->Term_Prev;

    //     // query students in class
    //     $students_in_class = Repo::where('Term', $prev_term)->whereHas('classrooms', function ($query) {
    //         $query->whereNotNull('Tch_ID')
    //             ->where('Tch_ID', '!=', 'TBD');
    //     })
    //         ->get();
    //     // put inside array
    //     $arr1 = [];
    //     foreach ($students_in_class as $key1 => $value1) {
    //         $arr1[] = $value1->INDEXID;
    //     }
    //     $arr1 = array_unique($arr1);

    //     $q = Preenrolment::where('Term', 234)->where('overall_approval', '1')->orderBy('created_at', 'asc')->get();
    //     $arr2 = [];
    //     foreach ($q as $key2 => $value2) {
    //         $arr2[] = $value2->INDEXID;
    //     }
    //     $arr2 = array_unique($arr2);

    //     // Compares array1 against one or more other arrays and returns the values in array1 that are not present in any of the other arrays
    //     $students_not_in_class = array_diff($arr2, $arr1); // get all enrolment_forms not included in students_in_class
    //     $unique_students_not_in_class = array_unique($students_not_in_class);

    //     $prev_prev_term = Term::where('Term_Code', $prev_term)->first()->Term_Prev;

    //     $students_within_two_terms = Repo::whereIn('INDEXID', $unique_students_not_in_class)->where('Term', $prev_prev_term)->get();
    //     // put inside array
    //     $within_two_terms = [];
    //     foreach ($students_within_two_terms as $key4 => $value4) {
    //         $within_two_terms[] = $value4->INDEXID;
    //     }
    //     $within_two_terms = array_unique($within_two_terms);

    //     $students_waitlisted = Repo::where('Term', 229)->whereHas('classrooms', function ($query) {
    //         $query->where('sectionNo', 1) // position of where clause needs to be here to take effect
    //             ->whereNull('Tch_ID')
    //             ->orWhere('Tch_ID', '=', 'TBD');
    //     })
    //         ->get();
    //     // put inside array
    //     $waitlisted = [];
    //     foreach ($students_waitlisted as $key3 => $value3) {
    //         $waitlisted[] = $value3->INDEXID;
    //     }
    //     $waitlisted = array_unique($waitlisted);
    //     $students_waitlisted_234 = Repo::where('Term', 234)->whereHas('classrooms', function ($query1) {
    //         $query1->whereNull('Tch_ID')
    //             ->orWhere('Tch_ID', '=', 'TBD')
    //             ->where('sectionNo', '>=', 2); // position of where clause needs to be here to take effect
    //     })
    //         ->get();
    //     // put inside array
    //     $waitlisted_234 = [];
    //     foreach ($students_waitlisted_234 as $key3 => $value3) {
    //         $waitlisted_234[] = $value3->INDEXID;
    //     }
    //     $waitlisted_234 = array_unique($waitlisted_234);

    //     dd($students_waitlisted, $waitlisted, $students_waitlisted_234, $waitlisted_234, $arr1, $arr2, $students_not_in_class, $unique_students_not_in_class, $within_two_terms);

    //     $pash_records = Repo::where('Term', $term)
    //         ->whereHas('classrooms', function ($q) {
    //             $q->whereNotNull('Tch_ID')
    //                 ->where('Tch_ID', '!=', 'TBD');
    //         })
    //         // ->where('Te_Code', 'like', "%1R%")
    //         // ->where(\DB::raw('substr(Te_Code, 2, 2)'), '=' , '1R')
    //         // ->whereRaw('SUBSTRING(Te_Code, 2, 2) = "1R"')
    //         // ->whereIn('L', ['A','C','R','S'])
    //         ->whereHas('courses', function ($q2) {
    //             $q2->where('level', '1');
    //         })
    //         ->with('users')->select('INDEXID', 'Te_Code')->groupBy('INDEXID', 'Te_Code')->get()->sortBy('INDEXID');

    //     $array = [];
    //     $arr_exists = [];
    //     // dd($pash_records);
    //     foreach ($pash_records as $value) {
    //         $existing = Repo::where('Term', '<', $term)->where('INDEXID', $value->INDEXID)->exists();
    //         // $array[] = $existing;
    //         if ($existing === false) {
    //             $array[] = [
    //                 'INDEXID' => $value->INDEXID,
    //                 'email' => strtolower($value->users->email),
    //                 'Te_Code' => $value->Te_Code,
    //             ];
    //         } else {
    //             $arr_exists[] = [
    //                 'INDEXID' => $value->INDEXID,
    //                 'email' => strtolower($value->users->email),
    //                 'Te_Code' => $value->Te_Code,
    //             ];
    //         }
    //     }

    //     $fromPlacements = Repo::where('Term', $term)
    //         ->whereHas('classrooms', function ($q3) {
    //             $q3->whereNotNull('Tch_ID')
    //                 ->where('Tch_ID', '!=', 'TBD');
    //         })
    //         ->whereHas('courses', function ($q4) {
    //             $q4->where('level', '!=', '1');
    //         })
    //         ->whereHas('placements', function ($query) use ($term) {
    //             $query->where('Term', $term)->whereIn('L', ['A', 'C', 'R', 'S'])->whereNotNull('CodeIndexID');
    //         })
    //         // ->whereRaw('SUBSTRING(Te_Code, 2, 2) = "1R"')
    //         ->whereIn('L', ['A', 'C', 'R', 'S'])
    //         ->with('users')->select('INDEXID', 'Te_Code')->groupBy('INDEXID', 'Te_Code')->get()->sortBy('INDEXID');

    //     $array2 = [];
    //     $arr2_exists = [];
    //     foreach ($fromPlacements as $value2) {
    //         $existing2 = Repo::where('Term', '<', $term)->where('INDEXID', $value2->INDEXID)->exists();
    //         if ($existing2 === false) {
    //             $array2[] = [
    //                 'INDEXID' => $value2->INDEXID,
    //                 'email' => strtolower($value2->users->email),
    //                 'Te_Code' => $value2->Te_Code,
    //             ];
    //         } else {
    //             $arr2_exists[] = [
    //                 'INDEXID' => $value2->INDEXID,
    //                 'email' => strtolower($value2->users->email),
    //                 'Te_Code' => $value2->Te_Code,
    //             ];
    //         }
    //     }

    //     dd($array2, $arr2_exists, $array, $arr_exists);
    // }
}
