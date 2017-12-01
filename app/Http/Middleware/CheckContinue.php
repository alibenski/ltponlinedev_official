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

        // if continue_bool is true, redirect to myform2

        //else pass next 
        $current_user = Auth::user()->indexno;
        $grouped = Preenrolment::distinct('Te_Code')->where('INDEXID', '=', $current_user)
            ->where(function($q){ 
                $latest_term = Preenrolment::orderBy('Term', 'DESC')->value('Term');
                $q->where('Term', $latest_term );
            })->count('Te_Code');
        
        if ($grouped == '2') {
            $request->session()->flash('overlimit', 'You have reached submission form limit (2 Maximum Language Courses)');
            return redirect()->route('home');
        }
        return $next($request);
    }
}
