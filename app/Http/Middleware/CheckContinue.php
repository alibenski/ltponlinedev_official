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
        // check most current term first and next term
        $now_date = Carbon::now()->toDateString();
        $current_term_code = Term::orderBy('Term_Code', 'desc')
                ->whereDate('Term_End', '>=', $now_date)
                ->get()->min();
        $next_term_code = Term::orderBy('Term_Code', 'desc')->where('Term_Code', '=', $current_term_code->Term_Next)->get()->min('Term_Code');
        // query for continue_bool with term code for the next term and current user
        $current_user = Auth::user()->indexno;
        $boolx = Preenrolment::where('INDEXID', '=', $current_user)->where('Term','=', $next_term_code )
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
