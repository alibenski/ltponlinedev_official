<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Spatie\Permission\Traits\HasRoles;
use Session;
use URL;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/home';
    // protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Custom redirect based on Spatie roles
     */
    use HasRoles;

    protected $guard_name = 'web';

    public function showLoginForm()
    {
        $intendedURL = Session::get('url.intended');

        if ($intendedURL) {
            Session::put($intendedURL, URL::previous());
            Session::flash('expired', 'You have been logged out');
        }

        return view('auth.login');
    }

    protected function authenticated($request, $user)
    {
        $user->timestamps = false;
        $user->update([
            'last_login_at' => Carbon::now()->toDateTimeString(),
            'last_login_ip' => $request->getClientIp(),
        ]);

        if ($user->hasRole('Admin')) {
            return redirect()->intended('/admin');
        }

        if ($user->hasRole('Teacher')) {
            return redirect()->intended('/admin/teacher-dashboard');
        }

        return redirect()->intended('/home');
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
