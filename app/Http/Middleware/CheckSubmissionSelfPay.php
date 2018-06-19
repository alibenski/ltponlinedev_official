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

class CheckSubmissionSelfPay
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
            $request->session()->flash('enrolment_closed', 'Self-Pay Check Submission Count function error: Current Enrolment Model does not exist in the table. Please contact the Language Secretariat.');
            return redirect()->route('home');
        }
        $grouped = Preenrolment::distinct('Te_Code')->where('INDEXID', '=', $current_user)
            ->where(function($q){ 
                $current_enrol_term = \App\Helpers\GlobalFunction::instance()->currentEnrolTermObject();
                $current_enrol_termCode = $current_enrol_term->Term_Code;
                // do NOT count number of submitted forms disapproved by manager or HR learning partner  
                $q->where('Term', $current_enrol_termCode )->where('deleted_at', NULL)
                    ->where('is_self_pay_form', 1)
                    ;
            })->count('eform_submit_count');

        // count number of placement forms submitted
        $placementFromCount = PlacementForm::orderBy('Term', 'desc')
                ->where('INDEXID', $current_user)
                ->where('Term', $current_enrol_term->Term_Code)
                ->where('is_self_pay_form', 1)
                ->count();
        
        $sum = $grouped + $placementFromCount;

        if ($sum >= '4') {
            $request->session()->flash('overlimit', 'You have reached the payment-based enrolment form submission limit (2 enrolment forms and 2 placement test forms)');
            return redirect()->route('home');
        }
        return $next($request);
    }
}
