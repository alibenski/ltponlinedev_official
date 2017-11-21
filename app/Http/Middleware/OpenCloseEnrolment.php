<?php

namespace App\Http\Middleware;

use Closure;
use App\Term;
use Carbon\Carbon;
use Session;
use DB;


class OpenCloseEnrolment
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
        //logic to check today is between [5wks after Term_Begin of CURRENT term] and [8wks after Term_Begin of CURRENT term]
        //if between, $next, else, redirect()
        
        //get current year and date
        $now_date = Carbon::now();
        $now_year = Carbon::now()->year;       
        //return string of Term_Begin of CURRENT term
        $current_term_begin = Term::whereYear('Term_End', $now_year)
                        ->orderBy('Term_Code', 'desc')
                        ->where('Term_End', '>=', $now_date)
                        ->min('Term_Begin');
        //Carbon parse string value
        $carbon_current_term_begin = Carbon::parse($current_term_begin);
        //add weeks to Carbon date value to get beginning and end dates of enrolment
        $start_enrolment_date = $carbon_current_term_begin->addWeeks(4);
        $end_enrolment_date = $carbon_current_term_begin->addWeeks(7);

        //check if $now_date is between start and end enrol dates
        if ($now_date >= $start_enrolment_date && $now_date <= $end_enrolment_date) {
            return $next($request);
        }
        
        $request->session()->flash('enrolment_closed', 'Enrolment Period is CLOSED');
        return redirect()->route('home');
    }
}
