<?php

namespace App\Http\Controllers;

use App\NewUser;
use App\SDDEXTR;
use App\TORGAN;
use App\User;
use DB;
use Illuminate\Http\Request;

class NewUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return 'page for approving new user requests';
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cat = DB::table('LTP_Cat')->pluck("Description","Cat")->all();
        $student_status = DB::table('STU_STATUS')->pluck("StandFor","Abbreviation")->all();
        $org = TORGAN::get(["Org Full Name","Org name"]);
        // return view('page_not_available');
        return view('users.new_user')->withCat($cat)->withStudent_status($student_status)->withOrg($org);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
                //validate the data
        $this->validate($request, array(
                // 'gender' => 'required|string|',
                // 'nameLast' => 'required|string|max:255',
                // 'nameFirst' => 'required|string|max:255',
                // 'email' => 'required|string|email|max:255|unique:tblLTP_New_Users,email',
                // 'org' => 'required|string|max:255',
                // 'contact_num' => 'required|max:255',
                // 'cat' => 'required|',
                // 'student_cat' => 'required|',
                // 'g-recaptcha-response' => 'required|captcha',
        ));

        $query_auth_record = User::where('indexno', $request->indexno)->orWhere('email', $request->email)->first();

        // if staff exists in auth table, redirect to login page
        if ($query_auth_record) {
            $request->session()->flash('warning', 'Your Index ID ('.$query_auth_record->indexno.') and email address ('.$query_auth_record->email.') already exist in our records. Please login or reset your password.' );
            return redirect('login');
        }

        $query_sddextr_record = SDDEXTR::where('INDEXNO', $request->indexno)->orWhere('EMAIL', $request->email)->first();
        // if staff does not exist in auth table but index or email exists in sddextr, create auth record and send credentials
        if ($query_sddextr_record) {
            $user = User::create([ 
                'indexno' => $request->indexno,
                'email' => $request->email, 
                'nameFirst' => $request->nameFirst,
                'nameLast' => $request->nameLast,
                'name' => $request->nameFirst.' '.$request->nameLast,
                'password' => Hash::make($request->password),
                'must_change_password' => 1,
                'approved_account' => 1,
            ]);
            
        }

        // if not in auth table and sddextr table, send email to Secretariat to create his login credentials to the system and sddextr record
        
        dd($request, $query_auth_record);


        //store in database
        $newUser = new NewUser;
        $newUser->indexno_new = $request->indexno;
        $newUser->gender = $request->gender;
        $newUser->name = $request->nameFirst.' '.$request->nameLast;
        $newUser->nameLast = $request->nameLast;
        $newUser->nameFirst = $request->nameFirst;
        $newUser->email = $request->email;
        $newUser->org = $request->org;
        $newUser->contact_num = $request->contact_num;
        $newUser->cat = $request->cat;
        $newUser->student_cat = $request->student_cat;
        // $newUser->save();

        // $request->session()->flash('success', 'Thank you for your registration. Your new credentials will be emailed.' ); //laravel 5.4 version

        return redirect()->route('new_user_msg');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\NewUser  $newUser
     * @return \Illuminate\Http\Response
     */
    public function show(NewUser $newUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\NewUser  $newUser
     * @return \Illuminate\Http\Response
     */
    public function edit(NewUser $newUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\NewUser  $newUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NewUser $newUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\NewUser  $newUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(NewUser $newUser)
    {
        //
    }
}
