<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

// import classes from RegisterUsers trait for custom methods
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use App\SDDEXTR;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Prevent public from accesssing the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    // public function showRegistrationForm()
    // {
    //     return redirect('home');
    // }

    // public function register(Request $request)
    // {
    //     $this->validator($request->all())->validate();

    //     event(new Registered($user = $this->create($request->all())));

    //     // $this->guard()->login($user); // override so after registration, 
    //     // user is not automatically logged in to the application
    //     return 'Verify Email First';

    //     return $this->registered($request, $user)
    //                     ?: redirect($this->redirectPath());
    // }

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // protected $redirectTo = '/home';
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'indexno' => ['nullable', 'max:255', 'unique:users'],
            'indexno' => ['nullable', 'max:255', 'unique:SDDEXTR,INDEXID_New'],
            'nameFirst' => ['required', 'string', 'max:255'],
            'nameLast' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:SDDEXTR,EMAIL'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'g-recaptcha-response' => ['required', 'captcha'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        if (is_null($data['indexno'])) {
            $qryLatestIndex = User::orderBy('id', 'desc')->first();
            $qryLatestIndexID = $qryLatestIndex->id;

            $indexGenerate = 'Z' . $qryLatestIndexID;
            $data['indexno'] = $indexGenerate;
        }
        $user = User::create([
            'indexno' => $data['indexno'],
            'name' => $data['nameFirst'] . ' ' . strtoupper($data['nameLast']),
            'nameFirst' => $data['nameFirst'],
            'nameLast' => strtoupper($data['nameLast']),
            'email' => strtolower($data['email']),
            'password' => Hash::make($data['password']),
        ]);

        // $user->sddextr()->create([
        //     'INDEXNO' => $data['indexno'],
        //     'LASTNAME' => $data['nameLast'],
        //     'FIRSTNAME' => $data['nameFirst'],
        //     'EMAIL' => $data['email'],
        // ]);
        // whatever else you need to do - send email, etc

        return $user;
    }
}
