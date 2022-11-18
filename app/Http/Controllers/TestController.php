<?php

namespace App\Http\Controllers;

use App\PlacementForm;
use App\Preenrolment;
use Illuminate\Http\Request;
use App\User;
use App\Repo;
use Session;

class TestController extends Controller
{
    public function testQuery($term = "214")
    {
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
