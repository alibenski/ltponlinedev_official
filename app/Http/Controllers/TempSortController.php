<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TempSort;
use App\Preenrolment;
use App\Repo;
use App\Classroom;
use App\CourseSchedule;
use DB;

class TempSortController extends Controller
{
	public function orderCodes()
	{
		// query existing records where specific course is given
		$codeSortByCountIndexID = TempSort::select('Code', 'Term', DB::raw('count(*) as CountIndexID'))->where('Te_Code', 'FIO1')->groupBy('Code', 'Term')->orderBy(\DB::raw('count(INDEXID)'), 'ASC')->get();
    	foreach ($codeSortByCountIndexID as $value) {
    		DB::table('tblLTP_TempOrder')->insert(
			    ['Term' => $value->Term, 'Code' => $value->Code, 'CountIndexID' => $value->CountIndexID]
			);
    	}
    	// DB::table('tblLTP_TempOrder')->truncate();
    	dd($codeSortByCountIndexID);
	}

    public function sortEnrolmentForms()
    {	
    	return $this->checkCodeIfExistsInPash();
    	$getCode = TempSort::select('Code')->where('Te_Code', 'FIO1')->groupBy('Code')->get()->toArray();

    	$arrCodeCount = [];
    	$arrPerCode = [];
        $ingredients = [];
        // get the count for each Code
        $j = count($getCode);
    	for ($i=0; $i < $j; $i++) { 
    		$perCode = TempSort::where('Code', $getCode[$i])->value('Code');
    		$countPerCode = TempSort::where('Code', $getCode[$i])->get()->count();

    		$arrPerCode[] = $perCode;
			$arrCodeCount[] = $countPerCode;

        }

        //  get the min of the counts
        $minValue = min($arrCodeCount);
        $arr = [];
        $arrSaveToPash = [];

        // use min to determine the first course-schedule assignment
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
            } 
        }

        $new = array_map(null,$arrPerCode, $arrCodeCount, $num_classes);
    	dd($getCode,$arrPerCode,$arrCodeCount,$minValue, $num_classes, $new, $arr,$arrSaveToPash);    	
    }

    public function checkCodeIfExistsInPash()
    {
    	$checkCodeIfExisting = DB::table('tblLTP_TempOrder')->select('Code', 'Term')->orderBy('id')->get()->toArray();
    	$arr = [];
    	$arrStd = [];
    	foreach ($checkCodeIfExisting as $value) {
    		$queryPashForCodesArr = Repo::where('Code', $value->Code)->get()->toArray();
    		$arr[] = $queryPashForCodesArr;
    		$queryPashForCodes = Repo::where('Code', $value->Code)->get();
    		
    		if (empty($queryPashForCodesArr)) {
    			echo 'none exists';
    			echo '<br>';
    			// check INDEXID of students if existing in PASHQTcur
				$students = DB::table('tblLTP_TempSort')
		        ->select('tblLTP_TempSort.*')
		        ->where('tblLTP_TempSort.Term', "=",$value->Term)
		        ->where('tblLTP_TempSort.Code', "=",$value->Code)
		        // leftjoin sql statement with subquery using raw statement
		        ->leftJoin(DB::raw("(SELECT 
				      LTP_PASHQTcur.INDEXID FROM LTP_PASHQTcur
				      WHERE LTP_PASHQTcur.Term = '$value->Term') as items"),function($q){
				        $q->on("tblLTP_TempSort.INDEXID","=","items.INDEXID")
				        ;
				  })
		        ->whereNull('items.INDEXID')        
		        ->get();
		        // $arrStd[] = $students;
		        foreach ($students as $value) {
                    $arrStd[] = new  Repo([
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
                    foreach ($arrStd as $data) {
                        // $data->save();
                    }     
                } 
    		}
    	}
		
		/*
		 Start process of creating classes based on nnumber of students assigned per course-schedule 
		 */
		$getCodeForSectionNo = DB::table('tblLTP_TempOrder')->select('Code')->orderBy('id')->get();

		$arrCountStdPerCode = [];
		foreach ($getCodeForSectionNo as $value) {
			$countStdPerCode = Repo::where('Code', $value->Code)->get()->count();
			$arrCountStdPerCode[] = $countStdPerCode;
		}
		
		// calculate sum per code and divide by 14 or 15 for number of classes
		$num_classes =[];
		for ($i=0; $i < count($arrCountStdPerCode); $i++) { 
			$num_classes[] = intval(ceil($arrCountStdPerCode[$i]/15));
		}
		
		$getCode = DB::table('tblLTP_TempOrder')->select('Code')->orderBy('id')->get()->toArray();
		$arrGetCode = [];
		$arrGetDetails = [];
		foreach ($getCode as $valueCode) {
			$arrGetCode[] = $valueCode->Code;
			
			$getDetails = CourseSchedule::where('cs_unique', $valueCode->Code)->get();
			foreach ($getDetails as $valueDetails) {
				$arrGetDetails[] = $valueDetails;
			}
		}

		// $num_classes=[5,2];
		$ingredients = [];
		$k = count($num_classes);
		for ($i=0; $i < count($num_classes); $i++) { 
				// check existing section first
				// if null, then value is 1
				$sectionNo = 1;
				$sectionNo2 = 1;
				// if not null, get last value from TEVENTcur table
				// $sectionNo = $TEVENTcur->sectionNo;
				// $sectionNo2 = $TEVENTcur->sectionNo;
				
				$counter = $num_classes[$i];
				for ($i2=0; $i2 < $counter; $i2++) { 
					$ingredients[] = new  Classroom([
                        'Code' => $arrGetCode[$i].'-'.$sectionNo++,
                        'Te_Term' => $arrGetDetails[$i]->Te_Term,
                        'cs_unique' => $arrGetDetails[$i]->cs_unique,
                        'L' => $arrGetDetails[$i]->L, 
                        'Te_Code_New' => $arrGetDetails[$i]->Te_Code_New, 
                        'schedule_id' => $arrGetDetails[$i]->schedule_id,
                        'sectionNo' => $sectionNo2++,
                        ]);
					foreach ($ingredients as $data) {
                                // $data->save();
                            }
				}
		}

    	dd($k,$counter,$num_classes,$arrGetCode,$arrGetDetails,$ingredients);
    }

    public function createSections()
    {
    	
    }
}
