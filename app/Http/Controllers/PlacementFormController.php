<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailtoApprover;
use App\Language;
use App\Course;
use App\User;
use App\Repo;
use App\Term;
use App\Classroom;
use App\Schedule;
use App\Preenrolment;
use App\SDDEXTR;
use App\Torgan;
use Session;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;

use App\PlacementSchedule;
use App\PlacementForm;

class PlacementFormController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
        $this->middleware('prevent-back-history');
        // $this->middleware('opencloseenrolment');
        // $this->middleware('checksubmissioncount');
        // $this->middleware('checkcontinue');
    }

    public function getPlacementInfo()
    {
        $languages = DB::table('languages')->pluck("name","code")->all();
        $now_date = Carbon::now()->toDateString();
            $terms = Term::orderBy('Term_Code', 'desc')
                    ->whereDate('Term_End', '>=', $now_date)
                    ->get()->min();

        $next_term = Term::orderBy('Term_Code', 'desc')->where('Term_Code', '=', $terms->Term_Next)->get()->min();
        return view('form.myformplacement')->withLanguages($languages)->withNext_term($next_term);
    }

    public function postPlacementInfo(Request $request)
    {
        $this->validate($request, array(
            'indexno' => 'required|',
            'org' => 'required|',
            'term' => 'required|',
            'langInput' => 'required|',
            'placementLang' => 'required|',
            'agreementBtn' => 'required|',
        ));

        $placementForm = new PlacementForm;
        $placementForm->INDEXID = $request->indexno;
        $placementForm->Term = $request->term;
        $placementForm->DEPT = $request->org;
        $placementForm->L = $request->langInput;
        $placementForm->schedule_id = $request->placementLang;
        $placementForm->save();
        
        // mail student regarding placement form information

        $request->session()->flash('success', 'Your Placement Test request has been submitted.'); //laravel 5.4 version
        return redirect()->route('home');
    }
}