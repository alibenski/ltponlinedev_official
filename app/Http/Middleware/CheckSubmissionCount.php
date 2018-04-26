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
        // count number of enrolment forms submitted 
        $eformGrouped = Preenrolment::distinct('Te_Code')->where('INDEXID', '=', $current_user)
            ->where(function($q){ 
                $latest_term = \App\Helpers\GlobalFunction::instance()->nextTermCode();
                // do NOT count number of submitted forms disapproved by manager or HR learning partner  
                $q->where('Term', $latest_term )->where('deleted_at', NULL)
                    ->where('is_self_pay_form', NULL)
                    ;
            })->count('eform_submit_count');

        // count number of placement forms submitted
        $now_date = Carbon::now()->toDateString();
        $terms = Term::orderBy('Term_Code', 'desc')
                ->whereDate('Term_End', '>=', $now_date)
                ->get()->min();

        $next_term = Term::orderBy('Term_Code', 'desc')->where('Term_Code', '=', $terms->Term_Next)->get()->min();
        
        $placementFromCount = PlacementForm::orderBy('Term', 'desc')
                ->where('INDEXID', $current_user)
                ->where('Term', $next_term->Term_Code)
                ->count();
        
        $sum = $eformGrouped + $placementFromCount;
        
        if ($sum >= '4') {
            $request->session()->flash('overlimit', 'You have reached the enrolment form submission limit (2 enrolment forms and 2 placement test forms)');
            return redirect()->route('home');
        }

        return $next($request);
    }
}
