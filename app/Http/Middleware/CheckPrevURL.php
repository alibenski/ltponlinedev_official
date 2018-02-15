<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Routing\UrlGenerator;
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
    public function handle(Request $request, Closure $next)
    {
        $org_input = $request->org;
        $prev_url = URL::previous(); 
        $sess = $request->session()->get('_previous');
        $result = array();
            foreach($sess as $val)
            {
              $result = $val;
            }
        var_dump(isset($org_input));
        //dd(isset($org_input));
        //var_dump($result);
        //middleware to (1) check previous URL based on Session and 
        //(2) if org value isset and not NULL 
        if ( isset($org_input) ) {
            return $next($request);
        }

        return redirect('home')->with('interdire-msg', 'You cannot go directly to that link. First visit: < '. route('whatorg') .' >');
    }
}
