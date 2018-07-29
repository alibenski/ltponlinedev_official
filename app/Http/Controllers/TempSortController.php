<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TempSort;
use App\Preenrolment;
use App\Repo;

class TempSortController extends Controller
{
    public function sortEnrolmentForms()
    {
    	$getCode = TempSort::select('Code')->where('Te_Code', 'FIO1')->groupBy('Code')->get()->toArray();

    	$arrCodeCount = [];
    	$arrPerCode = [];
        $num_classes =[];

    	for ($i=0; $i < count($getCode); $i++) { 
    		$perCode = TempSort::where('Code', $getCode[$i])->value('Code');
    		$countPerCode = TempSort::where('Code', $getCode[$i])->get()->count();

    		$arrPerCode[] = $perCode;
			$arrCodeCount[] = $countPerCode;
            // calculate sum per code and divide by 14 or 15 for number of classes
            $num_classes[] = $arrCodeCount[$i]/15;

        }
        
        $minValue = min($arrCodeCount);
        $arr = [];
        $arrSaveToPash = [];
        for ($i=0; $i < count($arrPerCode); $i++) { 

            if ($minValue >= $arrCodeCount[$i]) {
                $arr = $arrPerCode[$i]; 
                // assign course-schedule to student and save in PASHQTcur
                $queryEnrolForms = TempSort::where('Code', $arrPerCode[$i])->get();
                foreach ($queryEnrolForms as $value) {
                    $arrSaveToPash[] = new  Repo([
                    'CodeIndexID' => $value->CodeIndexID,
                    'Code' => $value->Code,
                    'schedule_id' => $value->schedule_id,
                    'L' => $value->L,
                    'profile' => $value->profile,
                    'Te_Code' => $value->Te_Code,
                    'Term' => $value->Term,
                    'INDEXID' => $value->INDEXID,
                    "created_at" =>  $value->created_at,
                    "UpdatedOn" =>  $value->UpdatedOn,
                    'mgr_email' =>  $value->mgr_email,
                    'mgr_lname' => $value->mgr_lname,
                    'mgr_fname' => $value->mgr_fname,
                    'continue_bool' => $value->continue_bool,
                    'DEPT' => $value->DEPT, 
                    'eform_submit_count' => $value->eform_submit_count,              
                    'form_counter' => $value->form_counter,  
                    'agreementBtn' => $value->agreementBtn,
                    'flexibleBtn' => $value->flexibleBtn,
                    ]); 
                    foreach ($arrSaveToPash as $data) {
                        // $data->save();
                    }     
                }   
            } elseif ($minValue < $arrCodeCount[$i]) {
                // check and exclude existing INDEXID with the Code
                $queryIndexID = TempSort::where('Code', $arrPerCode[$i])->get(); 
                foreach ($queryIndexID as $value) {
                    $arrSaveToPash[] = $value->INDEXID;
                }
            }
        }

        $new = array_map(null,$arrPerCode, $arrCodeCount, $num_classes);
    	dd($getCode,$arrPerCode,$arrCodeCount,$minValue, $num_classes, $new, $arr,$arrSaveToPash);
    	
    }
}
