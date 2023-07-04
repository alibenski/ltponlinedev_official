<?php

namespace App\Http\Controllers;

use App\PlacementForm;
use App\Preenrolment;
use Illuminate\Http\Request;
use App\User;
use App\Repo;
use App\Term;
use Session;

class TestController extends Controller
{
    public function testQuery($term = "214")
    {
        $prev_term = Term::where('Term_Code', 234)->first()->Term_Prev;

        // query students in class
        $students_in_class = Repo::where('Term', $prev_term)->whereHas('classrooms', function ($query) {
            $query->whereNotNull('Tch_ID')
                ->where('Tch_ID', '!=', 'TBD');
        })
            ->get();
        // put inside array
        $arr1 = [];
        foreach ($students_in_class as $key1 => $value1) {
            $arr1[] = $value1->INDEXID;
        }
        $arr1 = array_unique($arr1);

        $q = Preenrolment::where('Term', 234)->where('overall_approval', '1')->orderBy('created_at', 'asc')->get();
        $arr2 = [];
        foreach ($q as $key2 => $value2) {
            $arr2[] = $value2->INDEXID;
        }
        $arr2 = array_unique($arr2);

        // Compares array1 against one or more other arrays and returns the values in array1 that are not present in any of the other arrays
        $students_not_in_class = array_diff($arr2, $arr1); // get all enrolment_forms not included in students_in_class
        $unique_students_not_in_class = array_unique($students_not_in_class);

        $prev_prev_term = Term::where('Term_Code', $prev_term)->first()->Term_Prev;

        $students_within_two_terms = Repo::whereIn('INDEXID', $unique_students_not_in_class)->where('Term', $prev_prev_term)->get();
        // put inside array
        $within_two_terms = [];
        foreach ($students_within_two_terms as $key4 => $value4) {
            $within_two_terms[] = $value4->INDEXID;
        }
        $within_two_terms = array_unique($within_two_terms);

        $students_waitlisted = Repo::where('Term', 229)->whereHas('classrooms', function ($query) {
            $query->where('sectionNo', 1) // position of where clause needs to be here to take effect
                ->whereNull('Tch_ID')
                ->orWhere('Tch_ID', '=', 'TBD');
        })
            ->get();
        // put inside array
        $waitlisted = [];
        foreach ($students_waitlisted as $key3 => $value3) {
            $waitlisted[] = $value3->INDEXID;
        }
        $waitlisted = array_unique($waitlisted);
        $students_waitlisted_234 = Repo::where('Term', 234)->whereHas('classrooms', function ($query1) {
            $query1->whereNull('Tch_ID')
                ->orWhere('Tch_ID', '=', 'TBD')
                ->where('sectionNo', '>=', 2); // position of where clause needs to be here to take effect
        })
            ->get();
        // put inside array
        $waitlisted_234 = [];
        foreach ($students_waitlisted_234 as $key3 => $value3) {
            $waitlisted_234[] = $value3->INDEXID;
        }
        $waitlisted_234 = array_unique($waitlisted_234);

        dd($students_waitlisted, $waitlisted, $students_waitlisted_234, $waitlisted_234, $arr1, $arr2, $students_not_in_class, $unique_students_not_in_class, $within_two_terms);

        $pash_records = Repo::where('Term', $term)
            ->whereHas('classrooms', function ($q) {
                $q->whereNotNull('Tch_ID')
                    ->where('Tch_ID', '!=', 'TBD');
            })
            // ->where('Te_Code', 'like', "%1R%")
            // ->where(\DB::raw('substr(Te_Code, 2, 2)'), '=' , '1R')
            // ->whereRaw('SUBSTRING(Te_Code, 2, 2) = "1R"')
            // ->whereIn('L', ['A','C','R','S'])
            ->whereHas('courses', function ($q2) {
                $q2->where('level', '1');
            })
            ->with('users')->select('INDEXID', 'Te_Code')->groupBy('INDEXID', 'Te_Code')->get()->sortBy('INDEXID');

        $array = [];
        $arr_exists = [];
        // dd($pash_records);
        foreach ($pash_records as $value) {
            $existing = Repo::where('Term', '<', $term)->where('INDEXID', $value->INDEXID)->exists();
            // $array[] = $existing;
            if ($existing === false) {
                $array[] = [
                    'INDEXID' => $value->INDEXID,
                    'email' => strtolower($value->users->email),
                    'Te_Code' => $value->Te_Code,
                ];
            } else {
                $arr_exists[] = [
                    'INDEXID' => $value->INDEXID,
                    'email' => strtolower($value->users->email),
                    'Te_Code' => $value->Te_Code,
                ];
            }
        }

        $fromPlacements = Repo::where('Term', $term)
            ->whereHas('classrooms', function ($q3) {
                $q3->whereNotNull('Tch_ID')
                    ->where('Tch_ID', '!=', 'TBD');
            })
            ->whereHas('courses', function ($q4) {
                $q4->where('level', '!=', '1');
            })
            ->whereHas('placements', function ($query) use ($term) {
                $query->where('Term', $term)->whereIn('L', ['A', 'C', 'R', 'S'])->whereNotNull('CodeIndexID');
            })
            // ->whereRaw('SUBSTRING(Te_Code, 2, 2) = "1R"')
            ->whereIn('L', ['A', 'C', 'R', 'S'])
            ->with('users')->select('INDEXID', 'Te_Code')->groupBy('INDEXID', 'Te_Code')->get()->sortBy('INDEXID');

        $array2 = [];
        $arr2_exists = [];
        foreach ($fromPlacements as $value2) {
            $existing2 = Repo::where('Term', '<', $term)->where('INDEXID', $value2->INDEXID)->exists();
            if ($existing2 === false) {
                $array2[] = [
                    'INDEXID' => $value2->INDEXID,
                    'email' => strtolower($value2->users->email),
                    'Te_Code' => $value2->Te_Code,
                ];
            } else {
                $arr2_exists[] = [
                    'INDEXID' => $value2->INDEXID,
                    'email' => strtolower($value2->users->email),
                    'Te_Code' => $value2->Te_Code,
                ];
            }
        }

        dd($array2, $arr2_exists, $array, $arr_exists);
    }
}
