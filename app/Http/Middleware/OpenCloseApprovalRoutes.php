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

        // get approval limit date from Term table field
        $nextTermCode = \App\Helpers\GlobalFunction::instance()->currentEnrolTermObject();
        
        if (is_null($nextTermCode)) {
            abort(403, 'Unauthorized action. Value null set on Term.');
        }

        $approvalDateLimit = Term::where('Term_Code', '$nextTermCode->Term_Code')->value('Approval_Date_Limit');
        if ($approvalDateLimit == null || $now_date <= $approvalDateLimit) {
            return $next($request);
        }

        // redirect to page telling that the duration for approval has passed
        return redirect()->route('confirmationLinkExpired');
    }
}
