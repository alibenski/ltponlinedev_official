<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;
use App\Preenrolment;
use App\User;
use Session;
use DB;
use Carbon\Carbon;
use App\Term;
use App\PlacementForm;

class CheckSubmissionCount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $current_user = Auth::user()->indexno;
        $current_enrol_term = \App\Helpers\GlobalFunction::instance()->currentEnrolTermObject();
        if (is_null($current_enrol_term)) {
            $request->session()->flash('enrolment_closed', 'Check Submission Count function error: Current Enrolment Model does not exist in the table. Please contact the Language Secretariat.');
            return redirect()->route('home');
        }
        // count number of enrolment forms submitted for the CURRENT ENROLMENT TERM
        $eformGrouped = Preenrolment::distinct('Te_Code')->where('INDEXID', '=', $current_user)
            ->where(function($q){ 
                $current_enrol_term = \App\Helpers\GlobalFunction::instance()->currentEnrolTermObject();
                $current_enrol_termCode = $current_enrol_term->Term_Code;
                // do NOT count number of submitted forms disapproved by manager or HR learning partner  
                $q->where('Term', $current_enrol_termCode )->where('deleted_at', NULL)
                    ->where('is_self_pay_form', NULL)
                    ;
            })->count('eform_submit_count');

        // count number of placement forms submitted for the CURRENT ENROLMENT TERM        
        $placementFromCount = PlacementForm::orderBy('Term', 'desc')
                ->where('INDEXID', $current_user)
                ->where('Term', $current_enrol_term->Term_Code)
                ->count();

        $sum = $eformGrouped + $placementFromCount;
        
        if ($sum >= '4') {
            $request->session()->flash('overlimit', 'You have reached the enrolment form submission limit (2 enrolment forms and 2 placement test forms)');
            return redirect()->route('home');
        }

        return $next($request);
    }
}
