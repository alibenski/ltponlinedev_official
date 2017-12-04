<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;
use App\Preenrolment;
use App\User;
use Session;
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
        // query for continue_bool in current term and current user
        $current_user = Auth::user()->indexno;
        $boolx = Preenrolment::where('INDEXID', '=', $current_user)
                    ->where('continue_bool','=', '1' )->value('continue_bool');
        // run only if there is already an entry in Preenrolment table
        if (empty($boolx)){
            return $next($request);
        } else if ($boolx == '1') {
            return redirect()->route('noform.create');
        } 
        // if continue_bool is true (1), redirect to myform2

        //else pass next 

        return $next($request);
    }
}
