<?php

namespace App\Http\Middleware;

use App\Term;
use Carbon\Carbon;
use Closure;

class LimitCancelPeriod
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
        $now_date = Carbon::now();
        $termCode = $request->deleteTerm;
        //set limit of 4 working days                        
        // $carbon_next_term_begin = Carbon::parse($next_term)->subWeekdays(4)->toDateString(); 
        //set limit of 2 weeks days
        // $carbon_next_summer_term_begin = Carbon::parse($next_term)->subWeeks(2)->toDateString();
        
        // refactored code: $cancellationDateLimit replaces $carbon_next_term_begin
        // and $carbon_next_summer_term_begin in the IF statements below

        $cancellationDateLimit = Term::where('Term_Code', $termCode)->value('Cancel_Date_Limit');
        $current_enrol_season = Term::where('Term_Code', $termCode)->value('Comments');

        if (!is_null($cancellationDateLimit)) {
            //logic to check if today is 4 WORKING days AFTER the start of the NEXT term 
            //and if the NEXT term is NOT Summer
            //if yes, redirect(), else, $next
            if ($current_enrol_season !== 'SUMMER' && $now_date >= $cancellationDateLimit) {
                return redirect()->route('home')
                ->with('interdire-msg','Cancellation period expired.');
            } 
            //logic to check if today is 2 weeks AFTER the start of the NEXT term 
            //and if the NEXT term is Summer
            //if yes, redirect(), else, $next
            elseif ($current_enrol_season == 'SUMMER' && $now_date >= $cancellationDateLimit) {
                return redirect()->route('home')
                ->with('interdire-msg','Summer term cancellation period expired.');        
            } 
        } elseif (is_null($cancellationDateLimit)) {
            return redirect()->route('home')
                ->with('interdire-msg','Cancellation of enrolment forms has been disabled. Please contact the Language Secretariat.');
        } 
        
        return $next($request);
                
    }
}
