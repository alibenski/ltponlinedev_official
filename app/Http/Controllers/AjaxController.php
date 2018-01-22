<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AjaxController extends Controller
{
        /**
     * Show the application selectAjax.
     *
     * @return \Illuminate\Http\response
     */
    public function selectAjax(Request $request)
    {
        if($request->ajax()){
            //$courses = DB::table('courses')->where('language_id',$request->language_id)->pluck("name","id")->all();
            $select_courses = Course::where('L', $request->L)
            ->whereNotNull('Te_Code_New')
            ->orderBy('id', 'asc')
            ->pluck("Description","Te_Code_New")
            ->all();
            
            $data = view('ajax-select',compact('select_courses'))->render();
            return response()->json(['options'=>$data]);
        }
    }

    public function selectAjax2(Request $request)
    {
        if($request->ajax()){

            //$select_schedules = DB::table('LTP_TEVENTCur')
            $select_schedules = Classroom::where('Te_Code_New', $request->course_id)
            ->where(function($q){
                //get current year and date
                $now_date = Carbon::now()->toDateString();
                $now_year = Carbon::now()->year;

                //query the current term based on Term_End column is greater than today's date  
                $latest_term = Term::orderBy('Term_Code', 'desc')
                                ->whereDate('Term_End', '>=', $now_date)
                                ->get()->min();            
                //$latest_term = DB::table('LTP_Terms')->orderBy('Term_Code', 'DESC')->value('Term_Code');
                $q->where('Te_Term', $latest_term->Term_Code );
            })

            //Eager Load scheduler function and pluck using "dot" 
            ->with('scheduler')->get()->pluck('scheduler.name', 'schedule_id');

            $data = view('ajax-select2',compact('select_schedules'))->render();
            return response()->json(['options'=>$data]);
        }
    }
}
