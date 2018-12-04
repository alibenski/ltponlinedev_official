<?php

namespace App\Http\Controllers;

use App\Classroom;
use App\Course;
use App\CourseSchedule;
use App\Day;
use App\Language;
use App\Mail\MailPlacementTesttoApprover;
use App\Mail\MailtoApprover;
use App\PlacementForm;
use App\PlacementSchedule;
use App\Preenrolment;
use App\Preview;
use App\PreviewTempSort;
use App\Repo;
use App\SDDEXTR;
use App\Schedule;
use App\Term;
use App\Torgan;
use App\User;
use App\Waitlist;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Session;

class PreviewController extends Controller
{
	public function previewCourse3(Request $request)
    {
    	$preview = Preview::select(['schedule_id', 'Code', 'INDEXID', 'id'])->groupBy(['schedule_id', 'Code', 'INDEXID', 'id'])->get(['schedule_id', 'Code', 'INDEXID', 'id']);
    	
    	$preview_course = Preview::first();

    	$arr_key =[];
        $arr_count = [];
        $code = Preview::select(['schedule_id', 'Code'])->groupBy(['schedule_id', 'Code'])->get(['schedule_id', 'Code']);

	        foreach ($code as $key => $value) {
	            $arr_key[] = $value->schedules->name;
	            // var_dump($value->schedule_id);
	            // var_dump($value->Code);
	            $count_enrolment_forms = Preview::where('Code', $value->Code)->where('schedule_id', $value->schedule_id)->count();
	            $arr_count[] = $count_enrolment_forms;
	            $arr_count = array_combine($arr_key, $arr_count);
	            // var_dump($count_enrolment_forms);
	        }


    	return view('preview-course-3')->withPreview($preview)
    		->withPreview_course($preview_course)
    		->withArr_count($arr_count);

        // $languages = DB::table('languages')->pluck("name", "code")->all();
        // $org = Torgan::orderBy('Org Name', 'asc')->get(['Org Name', 'Org Full Name']);
        // $terms = Term::orderBy('Term_Code', 'desc')->get();



        // if (!Session::has('Term')) {
        //     $enrolment_forms = null;
        //     return view('preview-course')->withEnrolment_forms($enrolment_forms)->withLanguages($languages)->withOrg($org)->withTerms($terms);
        // }

        // $enrolment_forms = new Preenrolment;
        // $count_enrolment = new Preenrolment;
        // // $currentQueries = \Request::query();
        // $queries = [];

        // $columns = [
        //     'L', 'DEPT', 'Te_Code'
        // ];


        // foreach ($columns as $column) {
        //     if (\Request::has($column)) {
        //         $enrolment_forms = $enrolment_forms->where($column, \Request::input($column));
        //         $count_enrolment = $count_enrolment->where($column, \Request::input($column));
        //         $queries[$column] = \Request::input($column);
        //     }

        // }
        // if (Session::has('Term')) {
        //     $enrolment_forms = $enrolment_forms->where('Term', Session::get('Term'));
        //     $count_enrolment = $count_enrolment->where('Term', Session::get('Term'));
        //     $queries['Term'] = Session::get('Term');
        // }

        // if (\Request::has('search')) {
        //     $name = \Request::input('search');
        //     $enrolment_forms = $enrolment_forms->with('users')
        //         ->whereHas('users', function ($q) use ($name) {
        //             return $q->where('name', 'LIKE', '%' . $name . '%')->orWhere('email', 'LIKE', '%' . $name . '%');
        //         });
        //     $queries['search'] = \Request::input('search');
        // }

        // if (\Request::has('sort')) {
        //     $enrolment_forms = $enrolment_forms->orderBy('created_at', \Request::input('sort'));
        //     $queries['sort'] = \Request::input('sort');
        // }

        
        
        // // $allQueries = array_merge($queries, $currentQueries);
        // $enrolment_forms = $enrolment_forms->orderBy('schedule_id', 'asc')->paginate(20)->appends($queries);
        // return view('preview-course')->withEnrolment_forms($enrolment_forms)->withLanguages($languages)->withOrg($org)->withTerms($terms)->withArr_count($arr_count);
    }

    public function vsaPage1()
    {
    	DB::table('tblLTP_preview_TempSort')->truncate();
        $terms = Term::orderBy('Term_Code', 'desc')->get();
        return view('preview-course')->withTerms($terms);
    }

	public function getApprovedEnrolmentForms(Request $request)
    {

        // sort enrolment forms by date of submission
        $approved_0_1_collect = Preenrolment::whereIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])->where('Term', $request->Term)->where('approval','1')->orderBy('created_at', 'asc')->get();
        
        $approved_0_1 = Preenrolment::select('INDEXID')->whereIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])->where('Term', $request->Term)->where('approval','1')->orderBy('created_at', 'asc')->get();
        // apply unique() method to remove dupes 
        // apply values() method to reset key series of the array 
        $approved_1 = $approved_0_1->unique('INDEXID')->values()->all(); // becomes an array

        $approved_0_2_collect = Preenrolment::whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])->where('Term', $request->Term)->where('approval','1')->where('approval_hr', '1')->orderBy('created_at', 'asc')->get();
        
        $approved_0_2 = Preenrolment::select('INDEXID')->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])->where('Term', $request->Term)->where('approval','1')->where('approval_hr', '1')->orderBy('created_at', 'asc')->get();
        $approved_2 = $approved_0_2->unique('INDEXID')->values()->all();

        // !!!!!! add where selfpay_approval == 1 !!!!!!
        $approved_0_3_collect = Preenrolment::whereNotNull('is_self_pay_form')->where('Term', $request->Term)->orderBy('created_at', 'asc')->get();
        
        $approved_0_3 = Preenrolment::select('INDEXID')->whereNotNull('is_self_pay_form')->where('Term', $request->Term)->orderBy('created_at', 'asc')->get();
        $approved_3 = $approved_0_3->unique('INDEXID')->values()->all(); 
        

        $approved_collections = collect($approved_0_1_collect)->merge($approved_0_2_collect)->merge($approved_0_3_collect)->sortBy('created_at'); // merge collections with sorting by submission date and time
        $approved_collections = $approved_collections->unique('INDEXID')->values()->all(); 
        
        // merge collections but without sorting
        // $approved_all = array_merge($approved_1,$approved_2,$approved_3);
        
        /*
        Priority 1 re-enrolled students and Priority 2 waitlisted students
         */
        $arrCollect = [];
        // foreach ($approved_collections as $value) {
        // 	// $arrCollect[] = $value->INDEXID;
        // 	$arrCollect[] = $value->L;
        // }
        
        // logic to get previous Term of current/existing Term
        // 9 is 4, 1 is 9, 4 is 1
        // if last digit value is 9, subtract 5 from selectedTerm value
        $selectedTerm = $request->Term; // No need of type casting
        // echo substr($selectedTerm, 0, 1); // get first value
        // echo substr($selectedTerm, -1); // get last value
        $lastDigit = substr($selectedTerm, -1);

        if ($lastDigit == 9) {
            $prev_term = $selectedTerm - 5;
            // dd($term);
        }
        // if last digit is 1, check Term table for previous term value or subtract 2 from selectedTerm value
        if ($lastDigit == 1) {
            $prev_term = $selectedTerm - 2;
        }
        // if last digit is 4, check Term table for previous term value or subtract 3 from selectedTerm value
        if ($lastDigit == 4) {
            $prev_term = $selectedTerm - 3;
        }
        if ($lastDigit == 8) {
            $prev_term = $selectedTerm - 4;
        }

        $arrINDEXID = [];
        $arrL = [];
        $arrStudentReEnrolled = [];
        $arrValue = [];
        $countApprovedCollections = count($approved_collections);
        for ($i=0; $i < $countApprovedCollections; $i++) { 
            $arrINDEXID[] = $approved_collections[$i]['INDEXID'];
            $arrL[] = $approved_collections[$i]['L'];
            // echo $i. " - " .$arrINDEXID[$i] ;
            // echo "<br>";
        
            // priority 1: check each index id if they are already in re-enroling students from previous term via PASHQTcur table
            $student_reenrolled = Repo::select('INDEXID')
            	->where('Term', $prev_term)
            	->where('L', $arrL[$i])
            	->where('INDEXID', $arrINDEXID[$i])
            	->groupBy('INDEXID')
            	->get()->toArray();

            $arrStudentReEnrolled[] = $student_reenrolled;
            $student_reenrolled_filtered = array_filter($student_reenrolled);

            // iterate to get the index id of staff who are re-enroling
            foreach($student_reenrolled_filtered as $item) {
                // to know what's in $item
                // echo '<pre>'; var_dump($item);
                foreach ($item as $value) {
                    $arrValue[] = $value; // store the reenrolled INDEXID values in array
                    // echo $value['INDEXID'];
                    // echo "<br>";
                    // echo '<pre>'; var_dump($value['INDEXID']);
                }
            }
        }

        $arr_enrolment_forms_reenrolled = [];
        $ingredients = []; 
        $countArrValue = count($arrValue);
        for ($i=0; $i < $countArrValue; $i++) {
        	// collect priority 1 enrolment forms 
            $enrolment_forms_reenrolled = Preenrolment::where('Term', $request->Term)->where('INDEXID', $arrValue[$i])->orderBy('created_at', 'asc')->get();
            // $enrolment_forms_reenrolled = $enrolment_forms_reenrolled->unique('INDEXID')->values()->all();
            $arr_enrolment_forms_reenrolled[] = $enrolment_forms_reenrolled;

            // assigning of students to classes and saved in TempSort table
            foreach ($enrolment_forms_reenrolled as $value) {
                $ingredients[] = new  PreviewTempSort([
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
                    foreach ($ingredients as $data) {
                        $data->save();
                    }     
            }   
        }

        /*
        Priority 2 
         */


        /*
        Priority 3 
         */
        $arrPriority3 = [];
        $ingredients3 = [];
        // get the INDEXID's which are not existing
        $priority3_not_reset = array_diff($arrINDEXID,$arrValue);
        $priority3 = array_values($priority3_not_reset) ;
        $countPriority3 = count($priority3);
        for ($i=0; $i < $countPriority3; $i++) {
            // collect priority 3 enrolment forms 
            $enrolment_forms_priority3 = Preenrolment::where('Term', $request->Term)->where('INDEXID', $priority3[$i])->orderBy('created_at', 'asc')->get();
            $arrPriority3[] = $enrolment_forms_priority3 ;

            foreach ($enrolment_forms_priority3 as $value) {
                $ingredients3[] = new  PreviewTempSort([
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
                    foreach ($ingredients3 as $data) {
                        $data->save();
                    }     
            }  
        }
        
        /*
        Priority 4 new students, no PASHQTcur records and comes from Placement Test table and its results
         */
        
        // dd($approved_1,$approved_2,$approved_3);
        // PreviewTempSort::truncate();
        $request->session()->flash('success', 'Validation done!');
        return redirect()->route('preview-vsa-page-2');
        // dd('Count '.count($approved_collections),$arrPriority3, $arrValue,$ingredients, $ingredients3);
    }

    public function getApprovedPlacementForms(Request $request)
    {
        $approved_1 = PlacementForm::select('INDEXID')->whereIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])->where('approval','1')->get();
        $approved_2 = PlacementForm::select('INDEXID')->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS','DPKO'])->where('approval','1')->where('approval_hr', '1')->get();
        $approved_3 = PlacementForm::select('INDEXID')->whereNotNull('is_self_pay_form')->get();
        
        $placement_forms = $approved_1->merge($approved_2)->merge($approved_3);
        $arr = [];
        foreach ($approved_2 as $value) {
        	$arr[] = $value->INDEXID;
        }
        
        dd($approved_1,$approved_2,$approved_3, $arr);
    }

    public function vsaPage2()
    {
        $languages = DB::table('languages')->pluck("name","code")->all();
        $term = PreviewTempSort::orderBy('id', 'desc')->first();
        return view('preview-course-2')->withLanguages($languages)->withTerm($term);
    }

	public function orderCodes(Request $request)
	{	
		// truncate/clear table content first
		DB::table('tblLTP_preview_TempOrder')->truncate();
		DB::table('tblLTP_preview')->truncate();
		$te_code = $request->course_id;
		// first step
		// query existing records where specific course is given
		$codeSortByCountIndexID = PreviewTempSort::select('Code', 'Term', DB::raw('count(*) as CountIndexID'))->where('Te_Code', $te_code)->groupBy('Code', 'Term')->orderBy(\DB::raw('count(INDEXID)'), 'ASC')->get();
    	foreach ($codeSortByCountIndexID as $value) {
    		DB::table('tblLTP_preview_TempOrder')->insert(
			    ['Term' => $value->Term, 'Code' => $value->Code, 'CountIndexID' => $value->CountIndexID]
			);
    	}
    	// DB::table('tblLTP_preview_TempOrder')->truncate();
    	// dd($codeSortByCountIndexID);
		$this->sortEnrolmentForms($te_code);

        $request->session()->flash('success', 'Auto '.$te_code.' done!');
        return redirect()->route('preview-course-3');
	}

    public function sortEnrolmentForms($te_code)
    {	
    	$getCode = PreviewTempSort::select('Code')->where('Te_Code', $te_code)->groupBy('Code')->get()->toArray();

    	$arrCodeCount = [];
    	$arrPerCode = [];
    	$arrPerTerm = [];
        $ingredients = [];
        // get the count for each Code
        $j = count($getCode);
    	for ($i=0; $i < $j; $i++) { 
    		$perCode = PreviewTempSort::where('Code', $getCode[$i])->value('Code');
    		$perTerm = PreviewTempSort::where('Code', $getCode[$i])->value('Term');
    		$countPerCode = PreviewTempSort::where('Code', $getCode[$i])->get()->count();

    		$arrPerCode[] = $perCode;
    		$arrPerTerm[] = $perTerm;
			$arrCodeCount[] = $countPerCode;

        }

        //  get the min of the counts for each Code
        $minValue = min($arrCodeCount);       
        $arr = [];
        $arrSaveToPash = [];

        // use min to determine the first course-schedule assignment
        for ($i=0; $i < count($arrPerCode); $i++) { 

            if ($minValue >= $arrCodeCount[$i]) {
                // $arr = $arrPerCode[$i]; 
		        
		        // if there are 2 or more codes with equal count
		        // run query with leftJoin() to remove duplicates
		        $queryEnrolForms = DB::table('tblLTP_preview_TempSort')
			        ->select('tblLTP_preview_TempSort.*')
			        ->where('tblLTP_preview_TempSort.Term', "=",$arrPerTerm[$i])
			        ->where('tblLTP_preview_TempSort.Code', "=",$arrPerCode[$i])
			        // leftjoin sql statement with subquery using raw statement
			        ->leftJoin(DB::raw("(SELECT 
					      tblLTP_preview.INDEXID FROM tblLTP_preview
					      WHERE tblLTP_preview.Term = '$arrPerTerm[$i]') as items"),function($q){
					        $q->on("tblLTP_preview_TempSort.INDEXID","=","items.INDEXID")
					        ;
					  })
			        ->whereNull('items.INDEXID')        
			        ->get();

                // $queryEnrolForms = PreviewTempSort::where('Code', $arrPerCode[$i])->get();
                // assign course-schedule to student and save in PASHQTcur
                foreach ($queryEnrolForms as $value) {
                    $arrSaveToPash[] = new  Preview([
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
                        $data->save();
                    }     
                }   
            } 
        }

        // $new = array_map(null,$arrPerCode, $arrCodeCount);
    	// dd($getCode,$arrPerCode,$arrCodeCount,$minValue, $new, $arr,$arrSaveToPash);
    	$this->checkCodeIfExistsInPash();
    }

    public function checkCodeIfExistsInPash()
    {
    	$checkCodeIfExisting = DB::table('tblLTP_preview_TempOrder')->select('Code', 'Term')->orderBy('id')->get()->toArray();
    	$arr = [];
    	$arrStd = [];
    	foreach ($checkCodeIfExisting as $value) {
    		$queryPashForCodesArr = Preview::where('Code', $value->Code)->get()->toArray();
    		$arr[] = $queryPashForCodesArr;
    		$queryPashForCodes = Preview::where('Code', $value->Code)->get();
    		
    		if (empty($queryPashForCodesArr)) {
    			echo 'none exists';
    			echo '<br>';
    			// check INDEXID of students if existing in PASHQTcur
				$students = DB::table('tblLTP_preview_TempSort')
			        ->select('tblLTP_preview_TempSort.*')
			        ->where('tblLTP_preview_TempSort.Term', "=",$value->Term)
			        ->where('tblLTP_preview_TempSort.Code', "=",$value->Code)
			        // leftjoin sql statement with subquery using raw statement
			        ->leftJoin(DB::raw("(SELECT 
					      tblLTP_preview.INDEXID FROM tblLTP_preview
					      WHERE tblLTP_preview.Term = '$value->Term') as items"),function($q){
					        $q->on("tblLTP_preview_TempSort.INDEXID","=","items.INDEXID")
					        ;
					  })
			        ->whereNull('items.INDEXID')        
			        ->get();
		        // $arrStd[] = $students;
		        // save the queried students above to PASHQTcur table 
		        foreach ($students as $value) {
                    $arrStd[] = new  Preview([
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
                        $data->save();
                    }     
                } 
    		}
    	}
		
return redirect()->route('preview-course-3');

		/*
		 Start process of creating classes based on number of students assigned per course-schedule 
		 */
		$getCodeForSectionNo = DB::table('tblLTP_preview_TempOrder')->select('Code')->orderBy('id')->get();

		$arrCountStdPerCode = [];
		foreach ($getCodeForSectionNo as $value) {
			// query student count who are not yet assigned to a class section (null)
			$countStdPerCode = Preview::where('Code', $value->Code)->where('CodeIndexIDClass', null)->get()->count();
			$arrCountStdPerCode[] = $countStdPerCode;
		}
		
		// calculate sum per code and divide by 14 or 15 for number of classes
		$num_classes =[];
		for ($i=0; $i < count($arrCountStdPerCode); $i++) { 
			$num_classes[] = intval(ceil($arrCountStdPerCode[$i]/15));
		}
		
		$getCode = DB::table('tblLTP_preview_TempOrder')->select('Code')->orderBy('id')->get()->toArray();
		$arrGetCode = [];
		$arrGetDetails = [];
		
		foreach ($getCode as $valueCode) {
			$arrGetCode[] = $valueCode->Code;
			
			// update record in CourseSchedule table to indicate that classroom has been created for this cs_unique 
			// $updateCourseSchedule = CourseSchedule::where('cs_unique', $valueCode->Code)->update(['Code' => 'Y']);

			$getDetails = CourseSchedule::where('cs_unique', $valueCode->Code)->get();
			foreach ($getDetails as $valueDetails) {
				$arrGetDetails[] = $valueDetails;
			}
		}

		// $num_classes=[5,2];
		$ingredients = [];
		$k = count($num_classes);
		$arrExistingSection = [];
        $arr = [];
        
		for ($i=0; $i < count($num_classes); $i++) { 
			// check existing section(s) first
            // value of section is 1, if $existingSection is empty
            $counter = $num_classes[$i];
            $existingSection = Classroom::where('cs_unique', $arrGetCode[$i])->orderBy('sectionNo', 'desc')->get()->toArray();
            $arrExistingSection[] = $existingSection;
            // if not, get existing value of sectionNo
            if (!empty($existingSection)) {
                $sectionNo = $existingSection[0]['sectionNo'] + 1;
                $sectionNo2 = $existingSection[0]['sectionNo'] + 1;
                $arr[] = $sectionNo;
                // var_dump($sectionNo);

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
                                $data->save();
                    }
                }
            } 
            else {
                $sectionNo = 1;
                $sectionNo2 = 1;
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
                                $data->save();
                    }
                }
            }
                // var_dump('section value starts at: '.$sectionNo);
		}
		$this->assignAndAnalyze($getCode);
    }

    public function assignAndAnalyze($getCode)
    {
    	// query PASHQTcur and take 15 students to assign classroom created in TEVENTcur
		$arrGetClassRoomDetails = [];
		$arrCountCodeClass = [];
        $arrGetOrphanStudents =[];
        $arrNotCompleteClasses = [];
        $arrNotCompleteCount = [];
        $arrjNotCompleteCount = [];
        $arrNotCompleteCode = [];
        $arrGetOrphanIndexID = [];
        $arrNotCompleteScheduleID = []; 

		foreach ($getCode as $valueCode2) {
			// code from PreviewTempSort, put in array
			$arrGetCode[] = $valueCode2->Code; 
			
			$getClassRoomDetails = Classroom::where('cs_unique', $valueCode2->Code)->get();
			foreach ($getClassRoomDetails as $valueClassRoomDetails) {
				$arrGetClassRoomDetails[] = $valueClassRoomDetails;
				
				// query student count who are not yet assigned to a class section (null)
				$getPashStudents = Preview::where('Code', $valueCode2->Code)->where('CodeIndexIDClass', null)->get()->take(15);
				foreach ($getPashStudents as $valuePashStudents) {
					$pashUpdate = Preview::where('INDEXID', $valuePashStudents->INDEXID)->where('Code', $valueClassRoomDetails->cs_unique);
					// update record with classroom assigned
					$pashUpdate->update(['CodeClass' => $valueClassRoomDetails->Code, 'CodeIndexIDClass' => $valueClassRoomDetails->Code.'-'.$valuePashStudents->INDEXID]);
                }

                // query PASH entries to get CodeClass count
                $checkCountCodeClass = Preview::select('Te_Code', 'Code','CodeClass', 'schedule_id', 'L', DB::raw('count(*) as CountCodeClass'))->where('Code', $valueClassRoomDetails->cs_unique)->where('CodeClass', $valueClassRoomDetails->Code)->groupBy('Te_Code','Code','CodeClass','schedule_id','L')->orderBy('CountCodeClass', 'asc')->get();
                $checkCountCodeClass->sortBy('CountCodeClass');

                // query count of CodeClass which did not meet the minimum number of students
                foreach ($checkCountCodeClass as $valueCountCodeClass) {
                        $arrCountCodeClass[] = $valueCountCodeClass->CountCodeClass;
                    
                    // if the count is less than 6 where L = Ar,Ch,Ru 
        			$language_group_1 = ['A','C','R'];
        			if (in_array($valueCountCodeClass->L, $language_group_1) && $valueCountCodeClass->CountCodeClass < 6) {
                        $getOrphanStudents = Preview::where('CodeClass', $valueCountCodeClass->CodeClass)->where('Te_Code', $valueCountCodeClass->Te_Code)->where('L', $valueCountCodeClass->L)->get();
                        
                        foreach ($getOrphanStudents as $valueOrphanStudents) {
                            $arrGetOrphanStudents[] = $valueOrphanStudents->id;
                            $arrGetOrphanIndexID[] = $valueOrphanStudents->INDEXID;
                        }
                    }
        			// if the count is less than 8 where L = Fr,En,Sp
                    $language_group_2 = ['E','F','S'];
                    if (in_array($valueCountCodeClass->L, $language_group_2) && $valueCountCodeClass->CountCodeClass < 8) {
                        $getOrphanStudents = Preview::where('CodeClass', $valueCountCodeClass->CodeClass)->where('Te_Code', $valueCountCodeClass->Te_Code)->where('L', $valueCountCodeClass->L)->get();
                        
                        foreach ($getOrphanStudents as $valueOrphanStudents) {
                            $arrGetOrphanStudents[] = $valueOrphanStudents->id;
                            $arrGetOrphanIndexID[] = $valueOrphanStudents->INDEXID;
                            // $setNullToOrphans = Preview::where('id', $valueOrphanStudents->id)->update(['CodeIndexIDClass' => null]);
                        }
                        
                        // $pashUpdate->update(['CodeClass' => $valueClassRoomDetails->Code, 'CodeIndexIDClass' => $valueClassRoomDetails->Code.'-'.$valuePashStudents->INDEXID]);
                    }

                    if ($valueCountCodeClass->CountCodeClass > 8 && $valueCountCodeClass->CountCodeClass < 15) {
                        $arrNotCompleteClasses[] = $valueCountCodeClass->CodeClass;
                        $arrNotCompleteCode[] = $valueCountCodeClass->Code;
                        $arrNotCompleteCount[] = $valueCountCodeClass->CountCodeClass;
                        $arrNotCompleteScheduleID[] = $valueCountCodeClass->schedule_id;
                    } 
                }
            }
        }

        // then change CodeClass and assign to same Te_Code with a Code count which is less than 15
        // assign orphaned students with classrooms which are not at max capacity
        $c = count($arrNotCompleteClasses);
        if ($c != 0) {
        	for ($iCount=0; $iCount < $c; $iCount++) {
            // $arrjNotCompleteCount[] = $arrNotCompleteCount[$iCount]; 
            $jNotCompleteCount = intVal(15 - $arrNotCompleteCount[$iCount]);
            $arrjNotCompleteCount[] = $jNotCompleteCount;

	            for ($iCounter2=0; $iCounter2 < $jNotCompleteCount; $iCounter2++) { 
	            	if (!empty($arrGetOrphanStudents[$iCounter2])) {          		
	            		$setClassToOrphans = Preview::where('id', $arrGetOrphanStudents[$iCounter2])->update(['CodeClass' => $arrNotCompleteClasses[$iCount], 'CodeIndexIDClass' => $arrNotCompleteClasses[$iCount].'-'.$arrGetOrphanIndexID[$iCounter2], 'CodeIndexID' => $arrNotCompleteCode[$iCount].'-'.$arrGetOrphanIndexID[$iCounter2], 'Code' => $arrNotCompleteCode[$iCount], 'schedule_id' => $arrNotCompleteScheduleID[$iCount]]);
	            	} 
	            }
        	}
        }
        // else statement if necessary
        // dd($arrCountCodeClass,$arrGetOrphanStudents, $arrNotCompleteClasses, $arrNotCompleteCount, $arrjNotCompleteCount, $c);
    }
}
