<?php

namespace App\Http\Middleware;

use Closure;
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
    public function handle($request, Closure $next)
    {
        // $org_input = $request->organization;
        // $prev_url = URL::previous(); 
        $sess = $request->session()->get('_previous');
        $result = array();
            foreach($sess as $val)
            {
              $result = $val;
            }
        
        //middleware to check previous URL based on Session  
        if ($result == route('whatorg')) {
            return $next($request);
        } else {
            return redirect('home')->with('interdire-msg', 'Sorry, you cannot go directly to that link. First visit: < '. route('whatorg') .' >');
        }

    }
}
