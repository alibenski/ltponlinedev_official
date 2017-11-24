<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Preenrolment;


class ApprovalController extends Controller
{
    public function show($id)
    {
        //
    }

    /**
     * Show the pre-enrolment forms for approving the forms submitted by staff member 
     *
     */
    public function getForm($staff)
    {
    	$staff = Crypt::decrypt($staff);
        //execute Mail class before redirect     
        //$staff = Auth::user();
        //$current_user = Auth::user()->indexno;
        //$now_date = Carbon::now();
        //$next_term = Term::orderBy('Term_Code', 'desc')
        //                ->where('Term_Begin', '>', $now_date)->get()->min('Term_Code');
        //$course = Preenrolment::orderBy('Term', 'desc')->orderBy('id', 'desc')
        //                        ->where('INDEXID', $current_user)
          //                      ->value('Te_Code');
        //query from Preenrolment table the needed information data to include in email
        $input_course = Preenrolment::orderBy('Term', 'desc')->orderBy('id', 'desc')
                                ->where('INDEXID', $staff)
                                ->get();
        //$input_schedules = Preenrolment::orderBy('Term', 'desc')->orderBy('id', 'desc')
        //                        ->where('INDEXID', $current_user)
        //                        ->where('Term', $next_term)
          //                      ->where('Te_Code', $course)
            //                    ->get();

        return view('form.approval')->withInput_course($input_course);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateForm(Request $request, $id)
    {
        // Validate data
        $course = Course::find($id);
            $this->validate($request, array(
                'name' => 'required|max:255',
            )); 

        // Save the data to db
        $course = Course::find($id);

        $course->name = $request->input('name');
        $course->save();         
        // Set flash data with message
        $request->session()->flash('success', 'Changes have been saved!');
        // Redirect to flash data to posts.show
        return redirect()->route('courses.index');
    }
}
