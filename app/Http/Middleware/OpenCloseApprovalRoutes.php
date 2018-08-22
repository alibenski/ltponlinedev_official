<?php

namespace App\Http\Middleware;

use App\Term;
use Carbon\Carbon;
use Closure;
use DB;
use Illuminate\Support\Facades\Crypt;
use Session;

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
        // get current date
        $now_date = Carbon::now();
        // get the term from http route parameters via $request
        $term = Crypt::decrypt($request->route('term'));

        $approvalDateLimit = Term::where('Term_Code', $term)->value('Approval_Date_Limit');
        if ($approvalDateLimit == null || $now_date <= $approvalDateLimit) {
            return $next($request);
        }

        // redirect to page telling that the duration for approval has passed
        // return redirect()->route('confirmationLinkExpired');
        abort(403, 'Unauthorized action. Date limit for approval reached.');
    }
}
