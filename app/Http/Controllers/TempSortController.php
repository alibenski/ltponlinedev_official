<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TempSort;

class TempSortController extends Controller
{
    public function sortEnrolmentForms()
    {
    	$getCode = TempSort::select('Code')->where('Te_Code', 'FIO1')->groupBy('Code')->get()->toArray();

    	$arrCodeCount = [];
    	$arrPerCode = [];

    	for ($i=0; $i < count($getCode); $i++) { 
    		$perCode = TempSort::where('Code', $getCode[$i])->value('Code');
    		$countPerCode = TempSort::where('Code', $getCode[$i])->get()->count();

    		$arrPerCode[] = $perCode;
			$arrCodeCount[] = $countPerCode;
			$minValue = min($arrCodeCount);
    		// if ($minValue != $countPerCode) {
    		// 	$code = TempSort::where('Code', $getCode[$i])->get();
    		// 	dd($perCode);
    		// }
    		// else {
    		// 	dd($perCode);
    			
    		// }
    	}
    	dd($getCode,$arrPerCode,$arrCodeCount,$minValue);
    	
    }
}
