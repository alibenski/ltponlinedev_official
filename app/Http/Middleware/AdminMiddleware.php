<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;
class AdminMiddleware
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
        $user = User::all()->count();
        if (!($user == 1)) {
            if (!Auth::user()->hasPermissionTo('Administer roles & permissions') && !Auth::user()->hasPermissionTo('Teacher administration')) {
                //abort('401'); //redeirect to home with flash message instead of 401 error
                return redirect('home')->with('interdire-msg', 'You are not authorized to access that page.');
            }
        }

        return $next($request);
    }
}