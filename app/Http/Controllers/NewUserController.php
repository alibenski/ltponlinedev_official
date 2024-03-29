<?php

namespace App\Http\Controllers;

use App\Services\User\MsuUpdateField;
use App\Services\User\NgoUpdateField;
use App\Services\User\OhchrEmailChecker;
use App\FileNewUser;
use App\Mail\SendAuthMail;
use App\NewUser;
use App\NewUserComments;
use App\SDDEXTR;
use App\Torgan;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            ->orWhere('approved_account', 3)
            ->where('name', 'LIKE', '%' . $query . '%')
            ->paginate(20);

        return view('users_new.index', compact('users'));
    }

    public function newUserIndexAll()
    {
        //Get all users and pass it to the view
        // $users = User::paginate(50); 
        // Gets the query string from our form submission 
        $query = \Request::input('search');
        // Returns an array of users that have the query string located somewhere within 
        // our users name or email fields. Paginates them so we can break up lots of search results.
        $users = NewUser::orderBy('id', 'desc')
            // ->where('approved_account', 0)
            ->where('name', 'LIKE', '%' . $query . '%')
            ->paginate(20);

        return view('users_new.newuser_index_all', compact('users'));
    }

    public function closeNewUserForm($enrol_object_start_date, $enrol_object_end_date, $now_date)
    {
        if ($enrol_object_start_date <= $now_date && $enrol_object_end_date >= $now_date) {
            return false;
        }
        return true;
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
        if (is_null($enrol_object)) {
            return view('page_not_available');
        }

        $enrol_object_start_date = $enrol_object->Enrol_Date_Begin;
        $enrol_object_end_date = $enrol_object->Enrol_Date_End;

        $closeNewUserForm = $this->closeNewUserForm($enrol_object_start_date, $enrol_object_end_date, $now_date);
        if ($closeNewUserForm != true) {
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
            $request->session()->flash('warning', 'Your Index ID (' . $query_auth_record->indexno . ') and email address (' . $query_auth_record->email . ') already exist in our records. Please login or reset your password.');
            return redirect('login');
        }

        // query if staff exists in sddextr table
        $query_sddextr_record = SDDEXTR::where('INDEXNO', $request->indexno)->orWhere('EMAIL', $request->email)->first();

        // if staff does not exist in auth table but index or email exists in sddextr, create auth record and send credentials
        if ($query_sddextr_record) {
            $user = User::create([
                'indexno' => $query_sddextr_record->INDEXNO,
                'email' => strtolower(trim($query_sddextr_record->EMAIL)),
                'nameFirst' => $query_sddextr_record->FIRSTNAME,
                'nameLast' => strtoupper($query_sddextr_record->LASTNAME),
                'name' => $query_sddextr_record->FIRSTNAME . ' ' . strtoupper($query_sddextr_record->LASTNAME),
                'password' => Hash::make('Welcome2CLM'),
                'must_change_password' => 1,
                'approved_account' => 1,
            ]);
            $sddextr_email_address = trim($query_sddextr_record->EMAIL);
            // send credential email to user using email from sddextr 
            Mail::to(trim($query_sddextr_record->EMAIL))->send(new SendAuthMail($sddextr_email_address));
            // Mail::raw("username: ".$query_sddextr_record->EMAIL." password: Welcome2CLM", function($message) use($query_sddextr_record){
            //     $message->from('clm_language@unog.ch', 'CLM Language');
            //     $message->to($query_sddextr_record->EMAIL)->subject('MGR - This is a test automated message');
            // });
            $request->session()->flash('warning', 'Login Credentials sent to: ' . $query_sddextr_record->EMAIL);
            return redirect('login');
        }
        // if not in auth table and sddextr table, student fills out new user form
        if (!$query_auth_record && !$query_sddextr_record) {
            $request->session()->flash('warning', 'We do not have your Index and Email in our system. Please fill out the form below which will be reviewed by the Language Secretariat. Once validated, you will receive an email with your login credentials.');
            // return redirect()->route('get-new-new-user');
            return redirect()->route('get-new-outside-user-form');
        }
    }

    /**
     * getNewNewUser method not used 
     * automation to check index to Umoja not ready
     */
    public function getNewNewUser()
    {
        $cat = DB::table('LTP_Cat')->pluck("Description", "Cat")->all();
        $student_status = DB::table('STU_STATUS')->pluck("StandFor", "Abbreviation")->all();
        $org = Torgan::get(["Org Full Name", "Org name"]);
        return view('users_new.new_new_user', compact('cat', 'student_status', 'org'));
    }

    /**
     * postNewNewUser method not used 
     * automation to check index to Umoja not ready
     */
    public function postNewNewUser(Request $request)
    {
        // //validate the data
        // $this->validate($request, array(
        //     'gender' => 'required|string|',
        //     'title' => 'required|',
        //     'profile' => 'required|',
        //     'nameLast' => 'required|string|max:255',
        //     'nameFirst' => 'required|string|max:255',
        //     'email' => 'required|string|email|max:255|unique:tblLTP_New_Users,email',
        //     'org' => 'required|string|max:255',
        //     'contact_num' => 'required|max:255',
        //     // 'cat' => 'required|',
        //     // 'student_cat' => 'required|',
        //     'dob' => 'required',
        //     'g-recaptcha-response' => 'required|captcha',
        // ));

        // //store in database
        // $newUser = new NewUser;
        // $newUser->indexno_new = $request->indexno;
        // $newUser->gender = $request->gender;
        // $newUser->title = $request->title;
        // $newUser->profile = $request->profile;
        // $newUser->name = $request->nameFirst . ' ' . strtoupper($request->nameLast);
        // $newUser->nameLast = strtoupper($request->nameLast);
        // $newUser->nameFirst = $request->nameFirst;
        // $newUser->email = $request->email;
        // $newUser->org = $request->org;
        // $newUser->contact_num = $request->contact_num;
        // $newUser->dob = $request->dob;
        // // $newUser->cat = $request->cat;
        // // $newUser->student_cat = $request->student_cat;
        // $newUser->save();
        // // send email notification to Secretariat to approve his login credentials to the system and sddextr record
        // Mail::raw("New UN user request for: " . $request->nameFirst . ' ' . strtoupper($request->nameLast), function ($message) {
        //     $message->from('clm_onlineregistration@unog.ch', 'CLM Online Registration Administrator');
        //     $message->to('clm_language@un.org')->subject('Notification: New UN User Request');
        // });
        // // Mail::to($query_sddextr_record->EMAIL)->send(new NewUserNotification($sddextr_email_address));

        // return redirect()->route('new_user_msg');
    }

    public function getNewOutsideUser()
    {
        $now_date = Carbon::now();
        $enrol_object = \App\Helpers\GlobalFunction::instance()->currentEnrolTermObject();
        if (is_null($enrol_object)) {
            return view('page_not_available');
        }

        $enrol_object_start_date = $enrol_object->Enrol_Date_Begin;
        $enrol_object_end_date = $enrol_object->Enrol_Date_End;

        $closeNewUserForm = $this->closeNewUserForm($enrol_object_start_date, $enrol_object_end_date, $now_date);
        if ($closeNewUserForm != true) {
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
            $request->session()->flash('warning', 'Your Index ID (' . $query_auth_record->indexno . ') and email address (' . $query_auth_record->email . ') already exist in our records. Please login or reset your password.');
            return redirect('login');
        }

        // if staff exists in sddextr table, redirect to login page
        $query_sddextr_record = SDDEXTR::where('EMAIL', $request->email)->first();
        // if staff does not exist in auth table but index or email exists in sddextr, create auth record and send credentials
        if ($query_sddextr_record) {
            $user = User::create([
                'indexno_old' => $query_sddextr_record->INDEXNO_old,
                'indexno' => $query_sddextr_record->INDEXNO,
                'email' => strtolower($query_sddextr_record->EMAIL),
                'nameFirst' => $query_sddextr_record->FIRSTNAME,
                'nameLast' => strtoupper($query_sddextr_record->LASTNAME),
                'name' => $query_sddextr_record->FIRSTNAME . ' ' . strtoupper($query_sddextr_record->LASTNAME),
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
            $request->session()->flash('warning', 'Login Credentials sent to: ' . $query_sddextr_record->EMAIL);
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
        $org = Torgan::get(["Org Full Name", "Org name"]);

        $now_date = Carbon::now();
        $enrol_object = \App\Helpers\GlobalFunction::instance()->currentEnrolTermObject();
        if (is_null($enrol_object)) {
            return view('page_not_available');
        }

        $enrol_object_start_date = $enrol_object->Enrol_Date_Begin;
        $enrol_object_end_date = $enrol_object->Enrol_Date_End;

        $closeNewUserForm = $this->closeNewUserForm($enrol_object_start_date, $enrol_object_end_date, $now_date);
        if ($closeNewUserForm != true) {
            return view('users_new.new_outside_user_form', compact('org'));
        }

        return view('page_not_available');
    }

    public function postNewOutsideUserForm(Request $request, OhchrEmailChecker $ohchrEmailChecker)
    {
        $email_add = $request->email;
        $ohchrBoolean = $ohchrEmailChecker->ohchrEmailChecker($email_add);
        if ($ohchrBoolean) {
            \Session::flash('warning', 'Email address with @ohchr.org detected. For OHCHR staff, please use your @un.org email address.');
            return redirect()->back();
        }

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

        //validate 2nd attachment for Spouse profile
        if ($request->contractfile2) {
            $this->validate($request, array(
                'contractfile2' => 'required|mimes:pdf,doc,docx|max:8000',
            ));
        }

        //validate further if org is MSU or MGO
        if ($request->org == 'MSU') {
            $this->validate($request, array(
                'countryMission' => 'required',
            ));
        }
        if ($request->org == 'NGO') {
            $this->validate($request, array(
                'ngoName' => 'required|string|max:255',
            ));
        }

        //Store the attachments to storage path and save in db table
        if ($request->hasFile('contractfile')) {
            $request->file('contractfile');
            $time = date("d-m-Y") . "-" . time();
            $filename = $time . '_new_user_request_' . strtoupper($request->nameLast) . '_' . $request->nameFirst . '.' . $request->contractfile->extension();
            //Store attachment
            $filestore = Storage::putFileAs('public/attachment_newuser', $request->file('contractfile'), $filename);
            //Create new record in db table
            $attachment_contract_file = new FileNewUser([
                'filename' => $filename,
                'size' => $request->contractfile->getSize(),
                'path' => $filestore,
            ]);
            $attachment_contract_file->save();
        }

        if ($request->hasFile('contractfile2')) {
            $request->file('contractfile2');
            $time = date("d-m-Y") . "-" . time();
            $filename2 = $time . '_new_user_request_spouse_2_' . strtoupper($request->nameLast) . '_' . $request->nameFirst . '.' . $request->contractfile2->extension();
            //Store attachment
            $filestore2 = Storage::putFileAs('public/attachment_newuser', $request->file('contractfile2'), $filename2);
            //Create new record in db table
            $attachment_contract_file2 = new FileNewUser([
                'filename' => $filename2,
                'size' => $request->contractfile2->getSize(),
                'path' => $filestore2,
            ]);
            $attachment_contract_file2->save();
        }

        //store in database
        $newUser = new NewUser;
        $newUser->indexno_new = $request->indexno;
        $newUser->gender = $request->gender;
        $newUser->title = $request->title;
        $newUser->profile = $request->profile;
        $newUser->name = $request->nameFirst . ' ' . strtoupper($request->nameLast);
        $newUser->nameLast = strtoupper($request->nameLast);
        $newUser->nameFirst = $request->nameFirst;
        $newUser->email = $request->email;
        $newUser->org = $request->org;
        $newUser->contact_num = $request->contact_num;
        $newUser->dob = $request->dob;
        $newUser->attachment_id = $attachment_contract_file->id;

        if ($request->contractfile2) {
            $newUser->attachment_id_2 = $attachment_contract_file2->id;
        }

        if ($request->org == 'MSU') {
            $newUser->country_mission = $request->countryMission;
        }

        if ($request->org == 'NGO') {
            $newUser->ngo_name = $request->ngoName;
        }
        $newUser->save();
        // send email notification to Secretariat to approve his login credentials to the system and sddextr record
        Mail::raw("New UN user request for: " . $request->nameFirst . ' ' . strtoupper($request->nameLast), function ($message) {
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
        if ($request->ajax()) {
            $new_user_info = NewUser::find($request->id);
            $org = Torgan::orderBy('Org name', 'asc')->get(['Org name', 'Org Full Name']);
            $ext_index = 'EXT' . $new_user_info->id;

            if (is_null($new_user_info->indexno_new)) {
                $auto_index = 'EXT' . $new_user_info->id;
            } else {
                $auto_index = $new_user_info->indexno_new;
            }

            $possible_dupes = User::where('name', 'LIKE', '%' . $new_user_info->nameLast . '%')
                ->orWhere('name', 'LIKE', '%' . $new_user_info->nameFirst . '%')
                ->orWhere('name', 'LIKE', '%' . $new_user_info->email . '%')
                ->get();
            $data = view('users_new.edit', compact('new_user_info', 'org', 'auto_index', 'possible_dupes', 'ext_index'))->render();
            return response()->json(['options' => $data]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\NewUser  $newUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, MsuUpdateField $msuUpdateField, NgoUpdateField $ngoUpdateField)
    {
        if ($request->submit == 2) {
            $newUser = NewUser::findOrFail($id);
            $newUser->approved_account = 2;
            $newUser->updated_by = Auth::user()->id;
            $newUser->save();


            if ($request->emailText != null) {
                // get emailText and save but no email send
                $newUser = NewUser::findOrFail($id);
                $newUserComment = new NewUserComments;
                $newUserComment->comments = $request->emailText;
                $newUserComment->new_user_id = $request->id;
                $newUserComment->user_id = Auth::user()->id;
                $newUserComment->save();
            }

            $request->session()->flash('warning', 'Applicant has been rejected.');
            return redirect()->route('newuser.index');
        }

        if ($request->submit == 3) {
            // save pending status
            $newUser = NewUser::findOrFail($id);
            $newUser->approved_account = 3;
            $newUser->updated_by = Auth::user()->id;

            $this->updateAttachments($request, $newUser);

            $this->updateNewUser($newUser, $request, $msuUpdateField, $ngoUpdateField);

            if ($request->emailText != null) {
                // get emailText and send email
                $newUser = NewUser::findOrFail($id);
                $newUserComment = new NewUserComments;
                $newUserComment->comments = $request->emailText;
                $newUserComment->new_user_id = $request->id;
                $newUserComment->user_id = Auth::user()->id;
                $newUserComment->save();

                $html = "Your user account request has been set to PENDING status. <br/>Please see comment from the secretariat: <br/><b>" . $request->emailText . "</b>";
                Mail::send([], [], function ($message) use ($newUser, $html) {
                    $message->from('clm_language@unog.ch', 'CLM LTP Secretariat');
                    $message->to($newUser->email)->subject('Pending: LTP Online User Account Request');
                    $message->setBody($html, 'text/html');
                });

                $request->session()->flash('warning', 'Email sent. Applicant has been set to pending.');
                return redirect()->route('newuser.index');
            }

            $request->session()->flash('warning', 'Applicant has been set to pending.');
            return redirect()->route('newuser.index');
        }

        if ($request->submit == 1) {
            // check if there is a duplicate in Auth users table
            $this->validate($request, array(
                'indexno' => 'required|unique:users,indexno',
                'email' => 'unique:users,email',
            ));
            $this->validate($request, array(
                'indexno' => 'unique:SDDEXTR,INDEXNO_old',
                'indexno' => 'unique:SDDEXTR,INDEXNO',
                'email' => 'unique:SDDEXTR,EMAIL',
            ));

            if ($request->emailText != null) {
                // get emailText and save
                $newUser = NewUser::findOrFail($id);
                $newUserComment = new NewUserComments;
                $newUserComment->comments = $request->emailText;
                $newUserComment->new_user_id = $request->id;
                $newUserComment->user_id = Auth::user()->id;
                $newUserComment->save();
            }

            $newUser = NewUser::findOrFail($id);
            $newUser->approved_account = 1;
            $newUser->updated_by = Auth::user()->id;

            $this->updateAttachments($request, $newUser);

            $this->updateNewUser($newUser, $request, $msuUpdateField, $ngoUpdateField);

            // save entry to Auth table
            $user = User::create([
                'indexno_old' => $request->indexno,
                'indexno' => $request->indexno,
                'email' => strtolower($request->email),
                'profile' => $newUser->profile,
                'nameFirst' => $request->nameFirst,
                'nameLast' => strtoupper($request->nameLast),
                'name' => $request->nameFirst . ' ' . strtoupper($request->nameLast),
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
                'TITLE' => $newUser->title,
                'FIRSTNAME' => $request->nameFirst,
                'LASTNAME' => strtoupper($request->nameLast),
                'SEX' => $newUser->gender,
                'DEPT' => $newUser->org,
                'PHONE' => $newUser->contact_num,
                'BIRTH' => $newUser->dob,
                'EMAIL' => strtolower($request->email),
            ]);

            if ($newUser->org == 'MSU') {
                $sddextr->country_mission = $newUser->country_mission;
            }

            if ($newUser->org == 'NGO') {
                $sddextr->ngo_name = $newUser->ngo_name;
            }

            $sddextr->save();

            $request->session()->flash('success', 'User account approved. User account credentials have been sent.');
            return redirect()->route('newuser.index');
        }

        $request->session()->flash('error', 'Nothing happened.');
        return redirect()->route('newuser.index');
    }

    public function emailNewUser($email, $emailText)
    {
        // send email if new user application is pending or rejected
    }

    public function updateAttachments($request, $newUser)
    {
        //Store the attachments to storage path and save in db table
        if ($request->hasFile('contractfile')) {
            $request->file('contractfile');

            $fileId = $newUser->attachment_id;
            $attachment_contract_file = FileNewUser::findOrFail($fileId);
            $time = date("d-m-Y") . "-" . time();
            $filename = $time . '_' . $attachment_contract_file->filename;
            //Store attachment
            $filestore = Storage::putFileAs('public/attachment_newuser', $request->file('contractfile'), $filename);
            //UPDATE record in db table
            $attachment_contract_file->update([
                'filename' => $filename,
                'size' => $request->contractfile->getSize(),
                'path' => $filestore,
            ]);
            $attachment_contract_file->save();
        }

        if ($request->hasFile('contractfile2')) {
            $request->file('contractfile2');

            $fileId2 = $newUser->attachment_id_2;
            if ($fileId2) {
                $attachment_contract_file2 = FileNewUser::findOrFail($fileId2);
                $time = date("d-m-Y") . "-" . time();
                $filename2 = $time . '_' . $attachment_contract_file2->filename;
                //Store attachment
                $filestore2 = Storage::putFileAs('public/attachment_newuser', $request->file('contractfile2'), $filename2);
                //UPDATE record in db table
                $attachment_contract_file2->update([
                    'filename' => $filename2,
                    'size' => $request->contractfile2->getSize(),
                    'path' => $filestore2,
                ]);
                $attachment_contract_file2->save();
            } else {
                $time = date("d-m-Y") . "-" . time();
                $filename2 = $time . '_new_user_request_spouse_2_' . strtoupper($newUser->nameLast) . '_' . $newUser->nameFirst . '.' . $request->contractfile2->extension();
                //Store attachment
                $filestore2 = Storage::putFileAs('public/attachment_newuser', $request->file('contractfile2'), $filename2);
                //Create record in db table
                $attachment_contract_file2 = new FileNewUser([
                    'filename' => $filename2,
                    'size' => $request->contractfile2->getSize(),
                    'path' => $filestore2,
                ]);
                $attachment_contract_file2->save();

                $newUser->attachment_id_2 = $attachment_contract_file2->id;
            }
        }
    }

    public function updateNewUser($newUser, $request, $msuUpdateField, $ngoUpdateField)
    {
        $filtered = array_filter($request->all());
        $newUser->update($filtered);

        if (!is_null($request->org)) {
            $msuUpdateField->checkMsuValueNewUser($newUser, $request);
            $ngoUpdateField->checkNgoValueNewUser($newUser, $request);
        }
        $newUser->indexno_new = $request->indexno;
        $newUser->name = $request->nameFirst . ' ' . strtoupper($request->nameLast);
        $newUser->nameLast = strtoupper($request->nameLast);
        $newUser->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\NewUser  $newUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        dd($request);
    }
}
