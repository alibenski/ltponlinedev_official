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
            ->with('limit-cancel','Cancellation period expired.');
        //return $next($request);
    }
}
