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
        //refactored code to \App\Helpers\GlobalFunction::instance()->currentTerm()
        $current_term_begin = \App\Helpers\GlobalFunction::instance()->currentTerm();
        //Carbon parse string value
        $carbon_current_term_begin = Carbon::parse($current_term_begin);
        //we needed to have 2 separate variable for Carbon parsing of $current_term_begin 
        //since $start_enrolment_date is affected by $end_enrolment_date
        //addWeeks gets added from $start_enrolment_date and NOT from $current_term_begin
        $carbon_current_term_begin_for_end = Carbon::parse($current_term_begin);
        //automatically add weeks to Carbon date value to get beginning and end dates of enrolment
        $start_enrolment_date = $carbon_current_term_begin->addWeeks(4);
        $end_enrolment_date = $carbon_current_term_begin_for_end->addWeeks(7);

        // check if current enrolment term exists in Term table
        if (!is_null(\App\Helpers\GlobalFunction::instance()->currentEnrolTermObject())) {
            $currentEnrolTermCode = \App\Helpers\GlobalFunction::instance()->currentEnrolTermObject()->Term_Code;
            $startEnrolDate = Term::where('Term_Code', $currentEnrolTermCode)->value('Enrol_Date_Begin');
            $endEnrolDate = Term::where('Term_Code', $currentEnrolTermCode)->value('Enrol_Date_End');
            
            // check if $now_date is between start and end enrol dates
            // if ($now_date >= $start_enrolment_date && $now_date <= $end_enrolment_date) {
            if ($now_date >= $startEnrolDate && $now_date <= $endEnrolDate) {
                return $next($request);
            }
            
            // return string of PREVIOUS term
            $prev_termCode = \App\Helpers\GlobalFunction::instance()->currentEnrolTermObject()->Term_Prev;
            $prev_term = Term::orderBy('Term_Code', 'desc')
                            ->where('Term_Code', $prev_termCode)->first();

            $prev_term_description =  $prev_term->Comments;
            $prev_term_name =  $prev_term->Term_Name;
            $request->session()->flash('enrolment_closed', 'Enrolment Period for the '.$prev_term_description.' season ('.$prev_term_name.') is CLOSED');
            return redirect()->route('home');
        } 
        // back to home if current enrolment term does not exist in Term table
        $request->session()->flash('enrolment_closed', 'Enrolment is not yet available');
        return redirect()->route('home');
    }
}
