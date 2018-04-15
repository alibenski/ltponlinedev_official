<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;
use App\Preenrolment;
use App\User;
use Session;
use DB;


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
        $eformGrouped = Preenrolment::distinct('Te_Code')->where('INDEXID', '=', $current_user)
            ->where(function($q){ 
                $latest_term = \App\Helpers\GlobalFunction::instance()->nextTermCode();
                // do NOT count number of submitted forms disapproved by manager or HR learning partner  
                $q->where('Term', $latest_term )->where('deleted_at', NULL)
                    ->where('is_self_pay_form', NULL)
                    ;
            })->count('eform_submit_count');

        if ($eformGrouped >= '2') {
            $request->session()->flash('overlimit', 'You have reached the enrolment form submission limit (maximum of 2 enrolment forms)');
            return redirect()->route('home');
        }

        return $next($request);
    }
}
