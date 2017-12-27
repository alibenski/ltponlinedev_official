<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;
use App\User;
use Session;

class RedirectIfNotYourProfile
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
        if(Auth::check() && Auth::id() != request()->route('student'))
        {
          $request->session()->flash('redirect_back_to_own_profile', 'You are not allowed to view other user profiles.');
          return redirect()->back();
        }
        return $next($request);
    }
}
