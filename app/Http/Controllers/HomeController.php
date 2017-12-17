<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Language;
use App\Course;
use App\User;
use App\Repo;
use App\Preenrolment;
use App\Term;
use Session;
use Carbon\Carbon;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('prevent-back-history');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $current_user = Auth::user()->indexno;
        //query last UN Language Course enrolled in the past based on PASHQ table
        $repos_lang = Repo::orderBy('Term', 'desc')->where('INDEXID', $current_user)->first();
        //query the current term based on year and Term_End column is greater than today's date
        //whereYear('Term_End', $now_year)  
                        //->first();
        $now_date = Carbon::now()->toDateString();
        $terms = Term::orderBy('Term_Code', 'desc')
                ->whereDate('Term_End', '>=', $now_date)
                ->get()->min();
        $next_term_code = Term::orderBy('Term_Code', 'desc')->where('Term_Code', '=', $terms->Term_Next)->get()->min('Term_Code');
        //query submitted forms based from tblLTP_Enrolment table
        $forms_submitted = Preenrolment::distinct('Te_Code')
            ->where('INDEXID', '=', $current_user)
            ->where('Term', $next_term_code )->get();
        $next_term = Term::orderBy('Term_Code', 'desc')->where('Term_Code', '=', $terms->Term_Next)->get()->min();
        
        return view('home')->withRepos_lang($repos_lang)->withForms_submitted($forms_submitted)->withNext_term($next_term);
    }

    public function index2()
    {
        $current_user = Auth::user()->indexno;
        //query last UN Language Course enrolled in the past based on PASHQ table
        $repos_lang = Repo::orderBy('Term', 'desc')->where('INDEXID', $current_user)->first();
        //query the current term based on year and Term_End column is greater than today's date
        //whereYear('Term_End', $now_year)  
                        //->first();
        $now_date = Carbon::now()->toDateString();
        $terms = Term::orderBy('Term_Code', 'desc')
                ->whereDate('Term_End', '>=', $now_date)
                ->get()->min();
        $next_term_code = Term::orderBy('Term_Code', 'desc')->where('Term_Code', '=', $terms->Term_Next)->get()->min('Term_Code');
        //query submitted forms based from tblLTP_Enrolment table
        $forms_submitted = Preenrolment::distinct('Te_Code')
            ->where('INDEXID', '=', $current_user)
            ->where('Term', $next_term_code )->get(['Te_Code', 'INDEXID' ,'approval','approval_hr']);
        $next_term = Term::orderBy('Term_Code', 'desc')->where('Term_Code', '=', $terms->Term_Next)->get()->min();
 
        return view('form.submitted')->withRepos_lang($repos_lang)->withForms_submitted($forms_submitted)->withNext_term($next_term);
    }

    public function showMod(Request $request)
    {
            $current_user = Auth::user()->indexno;
            $now_date = Carbon::now()->toDateString();
            $terms = Term::orderBy('Term_Code', 'desc')
                    ->whereDate('Term_End', '>=', $now_date)
                    ->get()->min();
            $next_term_code = Term::orderBy('Term_Code', 'desc')->where('Term_Code', '=', $terms->Term_Next)->get()->min('Term_Code');
            //query submitted forms based from tblLTP_Enrolment table
            //$schedules = Preenrolment::all();
            $schedules = Preenrolment::where('Te_Code', $request->tecode)
                ->where('INDEXID', '=', $current_user)
                ->where('Term', $next_term_code )->get(['schedule_id'])->pluck('schedule.name');

                $data = view('form.modalshowinfo',compact('schedules'))->render();
            return response()->json([$data]);
            //return $request->tecode;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    public function destroy(Request $request, $staff, $tecode)
    {
        return 'delete';
    }
}
