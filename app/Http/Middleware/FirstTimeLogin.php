<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;

class FirstTimeLogin
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
        $user = $request->user();
        $checker = $request->user()->must_change_password;

        if ($checker == 1) {
            return redirect()->route('password.expired');
        }


        return $next($request);
    }
}
