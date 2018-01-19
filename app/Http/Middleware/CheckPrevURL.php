<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Session;

class CheckPrevURL
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
        $prev_url = URL::previous();
        if ($prev_url == route('whatorg')) {
            return $next($request);
        }

        return redirect('home')->with('interdire-msg', 'You cannot go directly to that link. First visit: < '. route('whatorg') .' >');
    }
}
