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
        //get current year and date
        $now_date = Carbon::now()->toDateString();
        $now_year = Carbon::now()->year;

        //query the current term based on year and Term_End column is greater than today's date
        //whereYear('Term_End', $now_year)  
        $terms = Term::orderBy('Term_Code', 'desc')
                        ->whereDate('Term_End', '>=', $now_date)
                        ->get()->min();
        //query the next term based Term_Begin column is greater than today's date and then get min
        $next_term = Term::orderBy('Term_Code', 'desc')
                        ->where('Term_Code', '=', $terms->Term_Next)
                        ->get()->min('Term_Begin');

        //query to get Comments field value
        $next_season = Term::orderBy('Term_Code', 'desc')
                        ->where('Term_Code', '=', $terms->Term_Next)->get()->min('Comments');

        //set limit of 4 working days                        
        $carbon_next_term_begin = Carbon::parse($next_term)->subWeekdays(4)->toDateString(); 
        //set limit of 2 weeks days
        $carbon_next_summer_term_begin = Carbon::parse($next_term)->subWeeks(2)->toDateString();

        //logic to check if today is 4 WORKING days AFTER the start of the NEXT term 
        //and if the NEXT term is NOT Summer
        //if yes, redirect(), else, $next
        if ($next_season !== 'SUMMER' && $now_date >= $carbon_next_term_begin) {
            return redirect()->route('home')
            ->with('interdire-msg','Cancellation period expired.');
        } 
        //logic to check if today is 2 weeks AFTER the start of the NEXT term 
        //and if the NEXT term is Summer
        //if yes, redirect(), else, $next
        elseif ($next_season == 'SUMMER' && $now_date >= $carbon_next_summer_term_begin) {
            return redirect()->route('home')
            ->with('interdire-msg','Summer term cancellation period expired.');        
        } 

        else  

        return $next($request);
        
    }
}
