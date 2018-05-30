<?php

namespace App\Http\Middleware;

use Closure;
use App\Term;
use Carbon\Carbon;
use Session;
use DB;

class OpenCloseApprovalRoutes
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
        // get current year and date
        $now_date = Carbon::now();
        $now_year = Carbon::now()->year;

        // ideally - get date from Term table field
        if ($now_date <= '2018-05-29 00:00:00') {
            return $next($request);
        }

        // redirect to page telling that the duration for approval has passed
        return redirect()->route('confirmationLinkExpired');
    }
}
