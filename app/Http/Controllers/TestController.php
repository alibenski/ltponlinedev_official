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
            ->whereHas('classrooms', function($q)
            {
                $q->whereNotNull('Tch_ID')
                    ->where('Tch_ID', '!=', 'TBD');
            })
            // ->where('Te_Code', 'like', "%1R%")
            // ->where(\DB::raw('substr(Te_Code, 2, 2)'), '=' , '1R')
            // ->whereRaw('SUBSTRING(Te_Code, 2, 2) = "1R"')
            // ->whereIn('L', ['A','C','R','S'])
            ->whereHas('courses', function($q2)
            {
                $q2->where('level', '1');
            })
            ->with('users')->select('INDEXID','Te_Code')->groupBy('INDEXID','Te_Code')->get()->sortBy('Te_Code');
        $array = [];
        $arr_exists = [];
        // dd($pash_records);
        foreach ($pash_records as $key => $value) {
            $existing = Repo::where('Term', '<', $term)->where('INDEXID', $value->INDEXID)->exists();
            // $array[] = $existing;
            if($existing === false){
                $array[] = [
                    'INDEXID' => $value->INDEXID,
                    'email' => $value->users->email,
                    'Te_Code' => $value->Te_Code,
                ];
            } else {
                $arr_exists[] = [
                        'INDEXID' => $value->INDEXID,
                        'email' => $value->users->email,
                        'Te_Code' => $value->Te_Code,
                    ];
            }
        }

        $fromPlacements = Repo::where('Term', $term)
            ->whereHas('classrooms', function($q3)
            {
                $q3->whereNotNull('Tch_ID')
                    ->where('Tch_ID', '!=', 'TBD');
            })
            ->whereHas('placements', function($query) use ($term){
                $query->where('Term', $term)->whereIn('L', ['A','C','R','S'])->whereNotNull('CodeIndexID');
            })
            // ->whereRaw('SUBSTRING(Te_Code, 2, 2) = "1R"')
            ->whereIn('L', ['A','C','R','S'])
            ->with('users')->select('INDEXID','Te_Code')->groupBy('INDEXID','Te_Code')->get()->sortBy('Te_Code');
        $array2 = [];
        
        foreach ($fromPlacements as $key => $value2) {
            $array2[] = [
                'email' => $value2->users->email,
                'Te_Code' => $value2->Te_Code,
            ];
        }

        dd($array, $arr_exists);
        return $term;
    }
}
