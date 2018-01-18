<?php

namespace App\Http\Middleware;

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
        
        return redirect()->route('home')
            ->with('interdire-msg','Cancellation period expired.');
        //return $next($request);
    }
}
