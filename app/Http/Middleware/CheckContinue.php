<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;
use App\Preenrolment;
use App\User;
use App\Term;
use Session;
use Carbon\Carbon;
use DB;


class CheckContinue
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
        // check latest enrolment term 
        $now_date = Carbon::now()->toDateString();

        $current_enrol_term_object = Term::orderBy('Term_Code', 'desc')
                ->whereDate('Enrol_Date_End', '>=', $now_date)
                ->get()->min();
                
        if (is_null($current_enrol_term_object)) {
            $request->session()->flash('enrolment_closed', 'Check Continue function error: Current Enrolment Model does not exist in the table.  Please contact the Language Secretariat.');
            return redirect()->route('home');
        }
        // get the code of latest enrolment term 
        $current_enrol_termCode = $current_enrol_term_object->Term_Code;

        // query for continue_bool with term code for the latest enrolment term and current user
        $current_user = Auth::user()->indexno;
        $boolx = Preenrolment::where('INDEXID', '=', $current_user)->where('Term','=', $current_enrol_termCode )
                    ->where('continue_bool','=', '1' )->value('continue_bool');

        // run only if there is already an entry in Preenrolment table
        if (empty($boolx)){
            return $next($request);
        // if continue_bool is true (1), redirect to myform2
        } else if ($boolx == '1') {
            return redirect()->route('noform.create');
        } 
        //else pass next 

        return $next($request);
    }
}
