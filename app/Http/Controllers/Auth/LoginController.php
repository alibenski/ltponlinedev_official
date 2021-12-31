<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasRoles;
use Session;
use URL;
use App\Services\User\OhchrEmailChecker;

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

    protected function login(Request $request, OhchrEmailChecker $ohchrEmailChecker)
    {
        $email_add = $request->email;
        $ohchrBoolean = $ohchrEmailChecker->ohchrEmailChecker($email_add);
        if ($ohchrBoolean) {
            \Session::flash('warning', 'Email address with @ohchr.org detected. For OHCHR staff, please use your @un.org email address.');
            return redirect()->back();
        }
        
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);        
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
