<?php

namespace App\Http\Middleware;

use App\Term;
use Carbon\Carbon;
use Closure;
use DB;
use Illuminate\Support\Facades\Crypt;
use Session;
use Illuminate\Contracts\Encryption\DecryptException;

class OpenCloseApprovalRoutesHR
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
        try {
            // get current date
            $now_date = Carbon::now();
            // get the term from http route parameters via $request
            $term = Crypt::decrypt($request->route('term'));

            $approvalDateLimit = Term::where('Term_Code', $term)->value('Approval_Date_Limit_HR');
            if ($approvalDateLimit == null || $now_date <= $approvalDateLimit) {
                return $next($request);
            }

            // redirect to page telling that the duration for approval has passed
            // return redirect()->route('confirmationLinkExpired');
            abort(403, 'Unauthorized action. Date limit for approval reached.');
        } catch (DecryptException $e) {
            abort(403, 'Error: Invalid Payload. Please click the link from the email message again.');
        }
    }
}
