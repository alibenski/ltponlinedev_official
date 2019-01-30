<?php

namespace App\Http\Controllers;

use App\FileNewUser;
use App\Mail\SendAuthMail;
use App\NewUser;
use App\SDDEXTR;
use App\TORGAN;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class NewUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Get all users and pass it to the view
        // $users = User::paginate(50); 
        // Gets the query string from our form submission 
        $query = \Request::input('search');
        // Returns an array of users that have the query string located somewhere within 
        // our users name or email fields. Paginates them so we can break up lots of search results.
        $users = NewUser::orderBy('id', 'desc')
            ->where('approved_account', 0)
            ->where('name', 'LIKE', '%' . $query . '%')
            ->paginate(20);

        return view('users_new.index')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $now_date = Carbon::now();
        $enrol_object = \App\Helpers\GlobalFunction::instance()->currentEnrolTermObject();
        if ( is_null($enrol_object) ) {
            return view('page_not_available');
        }
        
        $enrol_object_start_date = $enrol_object->Enrol_Date_Begin;
        $enrol_object_end_date = $enrol_object->Enrol_Date_End;
        
        if ($enrol_object_start_date <= $now_date && $enrol_object_end_date >= $now_date) {
            return view('users_new.new_user');
        }
        return view('page_not_available');
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
                'indexno' => 'required|integer',
                'email' => 'required|email',
                'g-recaptcha-response' => 'required|captcha',
        ));

        // check if staff exists in Auth table
        $query_auth_record = User::where('indexno', $request->indexno)->orWhere('email', $request->email)->first();

        // if staff exists in auth table, redirect to login page
        if ($query_auth_record) {
            $request->session()->flash('warning', 'Your Index ID ('.$query_auth_record->indexno.') and email address ('.$query_auth_record->email.') already exist in our records. Please login or reset your password.' );
            return redirect('login');
        }

        // if staff exists in sddextr table, redirect to login page
        $query_sddextr_record = SDDEXTR::where('INDEXNO', $request->indexno)->orWhere('EMAIL', $request->email)->first();
        
        // if staff does not exist in auth table but index or email exists in sddextr, create auth record and send credentials
        if ($query_sddextr_record) {
            $user = User::create([ 
                'indexno' => $query_sddextr_record->INDEXNO,
                'email' => $query_sddextr_record->EMAIL, 
                'nameFirst' => $query_sddextr_record->FIRSTNAME,
                'nameLast' => $query_sddextr_record->LASTNAME,
                'name' => $query_sddextr_record->FIRSTNAME.' '.$query_sddextr_record->LASTNAME,
                'password' => Hash::make('Welcome2CLM'),
                'must_change_password' => 1,
                'approved_account' => 1,
            ]);
            $sddextr_email_address = $query_sddextr_record->EMAIL;
            // send credential email to user using email from sddextr 
            Mail::to($query_sddextr_record->EMAIL)->send(new SendAuthMail($sddextr_email_address));
            // Mail::raw("username: ".$query_sddextr_record->EMAIL." password: Welcome2CLM", function($message) use($query_sddextr_record){
            //     $message->from('clm_language@unog.ch', 'CLM Language');
            //     $message->to($query_sddextr_record->EMAIL)->subject('MGR - This is a test automated message');
            // });
            $request->session()->flash('warning', 'Login Credentials sent to: '.$query_sddextr_record->EMAIL );
            return redirect('login');
        }
        // if not in auth table and sddextr table, student fills out new user form
        if (!$query_auth_record && !$query_sddextr_record) {
            $request->session()->flash('warning', 'We do not have your Index and Email in our system. Please fill out the form below which will be reviewed by the Language Secretariat. Once validated, you will receive an email with your login credentials.');
            // return redirect()->route('get-new-new-user');
            return redirect()->route('get-new-outside-user-form');
        }
    }

    public function getNewNewUser()
    {
        $cat = DB::table('LTP_Cat')->pluck("Description","Cat")->all();
        $student_status = DB::table('STU_STATUS')->pluck("StandFor","Abbreviation")->all();
        $org = TORGAN::get(["Org Full Name","Org name"]);
        return view('users_new.new_new_user')->withCat($cat)->withStudent_status($student_status)->withOrg($org);
    }

    public function postNewNewUser(Request $request)
    {
        //validate the data
        $this->validate($request, array(
                'gender' => 'required|string|',
                'title' => 'required|',
                'profile' => 'required|',
                'nameLast' => 'required|string|max:255',
                'nameFirst' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:tblLTP_New_Users,email',
                'org' => 'required|string|max:255',
                'contact_num' => 'required|max:255',
                // 'cat' => 'required|',
                // 'student_cat' => 'required|',
                'dob' => 'required',
                'g-recaptcha-response' => 'required|captcha',
        ));

        //store in database
        $newUser = new NewUser;
        $newUser->indexno_new = $request->indexno;
        $newUser->gender = $request->gender;
        $newUser->title = $request->title;
        $newUser->profile = $request->profile;
        $newUser->name = $request->nameFirst.' '.$request->nameLast;
        $newUser->nameLast = $request->nameLast;
        $newUser->nameFirst = $request->nameFirst;
        $newUser->email = $request->email;
        $newUser->org = $request->org;
        $newUser->contact_num = $request->contact_num;
        $newUser->dob = $request->dob;
        // $newUser->cat = $request->cat;
        // $newUser->student_cat = $request->student_cat;
        $newUser->save();
        // send email notification to Secretariat to approve his login credentials to the system and sddextr record
        Mail::raw("New UN user request for: ".$request->nameFirst.' '.$request->nameLast, function($message) {
                $message->from('clm_onlineregistration@unog.ch', 'CLM Online Registration Administrator');
                $message->to('clm_language@un.org')->subject('Notification: New UN User Request');
            });
         // Mail::to($query_sddextr_record->EMAIL)->send(new NewUserNotification($sddextr_email_address));

        return redirect()->route('new_user_msg');
    }

    public function getNewOutsideUser()
    {
        $now_date = Carbon::now();
        $enrol_object = \App\Helpers\GlobalFunction::instance()->currentEnrolTermObject();
        if ( is_null($enrol_object) ) {
            return view('page_not_available');
        }

        $enrol_object_start_date = $enrol_object->Enrol_Date_Begin;
        $enrol_object_end_date = $enrol_object->Enrol_Date_End;
        
        if ($enrol_object_start_date <= $now_date && $enrol_object_end_date >= $now_date) {
            return view('users_new.new_outside_user');
        }
        return view('page_not_available');
    }

    public function postNewOutsideUser(Request $request)
    {
        //validate the data
        $this->validate($request, array(
                'email' => 'required|email',
                'g-recaptcha-response' => 'required|captcha',
        ));

        // check if staff exists in Auth table
        $query_auth_record = User::where('email', $request->email)->first();

        // if staff exists in auth table, redirect to login page
        if ($query_auth_record) {
            $request->session()->flash('warning', 'Your Index ID ('.$query_auth_record->indexno.') and email address ('.$query_auth_record->email.') already exist in our records. Please login or reset your password.' );
            return redirect('login');
        }

        // if staff exists in sddextr table, redirect to login page
        $query_sddextr_record = SDDEXTR::where('EMAIL', $request->email)->first();
        // if staff does not exist in auth table but index or email exists in sddextr, create auth record and send credentials
        if ($query_sddextr_record) {
            $user = User::create([ 
                'indexno_old' => $query_sddextr_record->INDEXNO_old,
                'indexno' => $query_sddextr_record->INDEXNO,
                'email' => $query_sddextr_record->EMAIL, 
                'nameFirst' => $query_sddextr_record->FIRSTNAME,
                'nameLast' => $query_sddextr_record->LASTNAME,
                'name' => $query_sddextr_record->FIRSTNAME.' '.$query_sddextr_record->LASTNAME,
                'password' => Hash::make('Welcome2CLM'),
                'must_change_password' => 1,
                'approved_account' => 1,
            ]);
            $sddextr_email_address = $query_sddextr_record->EMAIL;
            // send credential email to user using email from sddextr 
            Mail::to($query_sddextr_record->EMAIL)->send(new SendAuthMail($sddextr_email_address));
            // Mail::raw("username: ".$query_sddextr_record->EMAIL." password: Welcome2CLM", function($message) use($query_sddextr_record){
            //     $message->from('clm_language@unog.ch', 'CLM Language');
            //     $message->to($query_sddextr_record->EMAIL)->subject('MGR - This is a test automated message');
            // });
            $request->session()->flash('warning', 'Login Credentials sent to: '.$query_sddextr_record->EMAIL );
            return redirect('login');
        }

        // if not in auth table and sddextr table, student fills out new non-UN user form
        if (!$query_auth_record && !$query_sddextr_record) {
            $request->session()->flash('warning', 'We do not have your Index and Email in our system. Please fill out the form below which will be reviewed by the Language Secretariat. Once validated, you will receive an email with your login credentials.');
            return redirect()->route('get-new-outside-user-form');
        }
    }

    public function getNewOutsideUserForm()
    {
        $org = TORGAN::get(["Org Full Name","Org name"]);
        
        $now_date = Carbon::now();
        $enrol_object = \App\Helpers\GlobalFunction::instance()->currentEnrolTermObject();
        if ( is_null($enrol_object) ) {
            return view('page_not_available');
        }
        
        $enrol_object_start_date = $enrol_object->Enrol_Date_Begin;
        $enrol_object_end_date = $enrol_object->Enrol_Date_End;
        
        if ($enrol_object_start_date <= $now_date && $enrol_object_end_date >= $now_date) {
            return view('users_new.new_outside_user_form')->withOrg($org);
        }

        return view('page_not_available');
    }

    public function postNewOutsideUserForm(Request $request)
    {
        //validate the data
        $this->validate($request, array(
                'gender' => 'required|string|',
                'title' => 'required|',
                'profile' => 'required|',
                'nameLast' => 'required|string|max:255',
                'nameFirst' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:tblLTP_New_Users,email',
                'org' => 'required|string|max:255',
                'contact_num' => 'required|max:255',
                // 'cat' => 'required|',
                // 'student_cat' => 'required|',
                'dob' => 'required',
                'contractfile' => 'required|mimes:pdf,doc,docx|max:8000',
                'g-recaptcha-response' => 'required|captcha',
        ));

        //Store the attachments to storage path and save in db table
        if ($request->hasFile('contractfile')){
            $request->file('contractfile');
            $filename = 'new_user_request_'.$request->nameLast.'_'.$request->nameFirst.'.'.$request->contractfile->extension();
            //Store attachment
            $filestore = Storage::putFileAs('public/attachment_newuser', $request->file('contractfile'), $filename);
            //Create new record in db table
            $attachment_contract_file = new FileNewUser([
                    'filename' => $filename,
                    'size' => $request->contractfile->getClientSize(),
                    'path' => $filestore,
                            ]); 
            $attachment_contract_file->save();
        }

        //store in database
        $newUser = new NewUser;
        $newUser->indexno_new = $request->indexno;
        $newUser->gender = $request->gender;
        $newUser->title = $request->title;
        $newUser->profile = $request->profile;
        $newUser->name = $request->nameFirst.' '.$request->nameLast;
        $newUser->nameLast = $request->nameLast;
        $newUser->nameFirst = $request->nameFirst;
        $newUser->email = $request->email;
        $newUser->org = $request->org;
        $newUser->contact_num = $request->contact_num;
        $newUser->dob = $request->dob;
        $newUser->attachment_id = $attachment_contract_file->id;
        // $newUser->cat = $request->cat;
        // $newUser->student_cat = $request->student_cat;
        $newUser->save();
        // send email notification to Secretariat to approve his login credentials to the system and sddextr record
        Mail::raw("New UN user request for: ".$request->nameFirst.' '.$request->nameLast, function($message) {
                $message->from('clm_onlineregistration@unog.ch', 'CLM Online Registration Administrator');
                $message->to('clm_language@un.org')->subject('Notification: New Non-UN User Request');
            });
         // Mail::to($query_sddextr_record->EMAIL)->send(new NewUserNotification($sddextr_email_address));

        return redirect()->route('new_user_msg');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\NewUser  $newUser
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
                   
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\NewUser  $newUser
     * @return \Illuminate\Http\Response
     */
    public function editNewUser(Request $request)
    {
        if($request->ajax()){     
            $new_user_info = NewUser::find($request->id);
            $org = TORGAN::orderBy('Org Name', 'asc')->get(['Org Name','Org Full Name']);
            $ext_index = 'EXT'.$new_user_info->id;

            if (is_null($new_user_info->indexno_new)) {
                $auto_index = 'EXT'.$new_user_info->id;
            } else {
                $auto_index = $new_user_info->indexno_new;
            }

            $possible_dupes = User::where('name', 'LIKE', '%' . $new_user_info->nameLast . '%')
                ->orWhere('name', 'LIKE', '%' . $new_user_info->nameFirst . '%')
                ->orWhere('name', 'LIKE', '%' . $new_user_info->email . '%')
                ->get();
            $data = view('users_new.edit',compact('new_user_info','org','auto_index','possible_dupes', 'ext_index'))->render();
            return response()->json(['options'=>$data]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\NewUser  $newUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // check if there is a duplicate in Auth users table
        $this->validate($request, array(
                'indexno' => 'required|unique:users,indexno',
                // validate if email is unique 
                'email' => 'unique:users,email',
            )); 
        $this->validate($request, array(
                'indexno' => 'unique:SDDEXTR,INDEXNO_old',
                // validate if email is unique 
                'email' => 'unique:SDDEXTR,EMAIL',
            )); 

        $newUser = NewUser::findOrFail($id);
        $newUser->approved_account = 1;
        $newUser->save();

        // save entry to Auth table
        $user = User::create([ 
            'indexno_old' => $request->indexno,
            'indexno' => $request->indexno,
            'email' => $request->email, 
            'profile' => $request->profile, 
            'nameFirst' => $request->nameFirst,
            'nameLast' => $request->nameLast,
            'name' => $request->nameFirst.' '.$request->nameLast,
            'password' => Hash::make('Welcome2CLM'),
            'must_change_password' => 1,
            'approved_account' => 1,
        ]); 
        // send email with credentials
        $sddextr_email_address = $request->email;
        Mail::to($request->email)->send(new SendAuthMail($sddextr_email_address));
        
        // save entry to SDDEXTR table
        $sddextr = SDDEXTR::create([
            'INDEXNO_old' => $request->indexno,
            'INDEXNO' => $request->indexno,
            'TITLE' => $request->title,
            'FIRSTNAME' => $request->nameFirst,
            'LASTNAME' => $request->nameLast,            
            'SEX' => $request->gender,            
            'DEPT' => $request->org,       
            'PHONE' => $request->contact_num,       
            'BIRTH' => $request->dob,       
            'EMAIL' => $request->email, 
        ]);

        return redirect()->route('newuser.index');
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
