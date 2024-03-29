<?php

namespace App\Http\Controllers;

use App\Services\User\MsuUpdateField;
use App\Services\User\NgoUpdateField;
use App\Course;
use App\FocalPoints;
use App\Language;
use App\Mail\updateEmail;
use App\Preenrolment;
use App\Repo;
use App\SDDEXTR;
use App\Teachers;
use App\Term;
use App\Torgan;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Session;
use Spatie\Permission\Models\Role;

class StudentController extends Controller
{
    /**
     * Call Middleware instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('updateProfileConfirmed');
        $this->middleware('prevent-back-history');
        $this->middleware('redirect-if-not-profile')->except('index', 'updateProfileConfirmed');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $current_user = Auth::user()->indexno;
        //query last UN Language Course enrolled in the past based on PASHQ table
        $repos_lang = Repo::orderBy('Term', 'desc')->where('INDEXID', $current_user)->first();

        return view('students.index', compact('repos_lang'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $student = User::find($id);
        $org = Torgan::orderBy('Org name', 'asc')->get(['Org name', 'Org Full Name']);

        return view('students.edit', compact('student', 'org'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, MsuUpdateField $msuUpdateField, NgoUpdateField $ngoUpdateField)
    {
        // Validate data
        $this->validate($request, array(
            // 'title' => 'required|',
            // 'lastName' => 'required|string',
            // 'firstName' => 'required|string',
            // validate if email is unique 
            'email' => 'required_without_all:gender,profile,TITLE,lastName,firstName,org,contactNo,dob,jobAppointment,gradeLevel,organization|unique:users,email',
            // 'org' => 'required|',
            'contactNo' => 'regex:/^[0-9\-+]+$/|nullable',
            // 'jobAppointment' => 'required|string',
            // 'gradeLevel' => 'required|string',

        ));

        if ($request->organization === 'MSU') {
            $this->validate($request, array(
                'countryMission' => 'required',
            ));
        }

        if ($request->organization === 'NGO') {
            $this->validate($request, array(
                'ngoName' => 'required',
            ));
        }

        // Save the data to db
        $student = User::findOrFail($id);
        if ($student->hasRole('Teacher')) {
            $indexno = $student->indexno;
            $this->updateTeacher($indexno, $request);
        }
        if (is_null($request->input('email'))) {
            $this->updateNoEmail($student, $request, $msuUpdateField, $ngoUpdateField);
            $request->session()->flash('success', 'Update successful.');
            return redirect()->route('home');
        } else {
            $this->validate($request, array(
                'email' => 'email',
            ));
            $this->updateWithEmail($student, $request, $msuUpdateField, $ngoUpdateField);
            return redirect('login');
        }
    }

    public function updateTeacher($indexno, $request)
    {
        $teacher = Teachers::where('IndexNo', $indexno)->first();
        if (!is_null($request->input('TITLE'))) {
            $teacher->Tch_Title = $request->input('TITLE');
        }
        if (!is_null($request->input('firstName'))) {
            $teacher->Tch_Firstname = $request->input('firstName');
        }
        if (!is_null($request->input('lastName'))) {
            $teacher->Tch_Lastname = strtoupper($request->input('lastName'));
        }
        if (!is_null($request->input('gender'))) {
            $teacher->sex = $request->input('gender');
        }
        if (!is_null($request->input('contactNo'))) {
            $teacher->Phone = $request->input('contactNo');
        }
        if (!is_null($request->input('dob'))) {
            $teacher->DoB = $request->input('dob');
        }

        $teacher->Tch_Name = strtoupper($teacher->Tch_Lastname) . ', ' . $teacher->Tch_Firstname;
        $teacher->save();
    }

    public function updateNoEmail($student, $request, $msuUpdateField, $ngoUpdateField)
    {
        if (!is_null($request->input('profile'))) {
            $student->profile = $request->input('profile');
            $student->sddextr->CAT = $request->input('profile');
        }
        if (!is_null($request->input('TITLE'))) {
            $student->sddextr->TITLE = $request->input('TITLE');
        }
        if (!is_null($request->input('firstName'))) {
            $student->nameFirst = $request->input('firstName');
            $student->sddextr->FIRSTNAME = $request->input('firstName');
        }
        if (!is_null($request->input('lastName'))) {
            $student->nameLast = strtoupper($request->input('lastName'));
            $student->sddextr->LASTNAME = strtoupper($request->input('lastName'));
        }
        if (!is_null($request->input('organization'))) {
            $student->sddextr->DEPT = $request->input('organization');
            $msuUpdateField->checkMsuValue($student, $request);
            $ngoUpdateField->checkNgoValue($student, $request);
        }
        if (!is_null($request->input('contactNo'))) {
            $student->sddextr->PHONE = $request->input('contactNo');
        }
        if (!is_null($request->input('dob'))) {
            $student->sddextr->BIRTH = $request->input('dob');
        }
        if (!is_null($request->input('jobAppointment'))) {
            $student->sddextr->CATEGORY = $request->input('jobAppointment');
        }
        if (!is_null($request->input('gradeLevel'))) {
            $student->sddextr->LEVEL = $request->input('gradeLevel');
        }
        if (!is_null($request->input('gender'))) {
            $student->sddextr->SEX = $request->input('gender');
        }

        $student->name = $student->nameFirst . ' ' . strtoupper($student->nameLast);
        $student->save();
        $student->sddextr->save();
    }

    public function updateWithEmail($student, $request, $msuUpdateField, $ngoUpdateField)
    {
        $this->updateNoEmail($student, $request, $msuUpdateField, $ngoUpdateField);

        $student->temp_email = $request->input('email');
        $student->approved_update = '0';
        $student->update_token = Str::random(60);
        $student->save();

        $this->sendUpdateEmail($student);

        $request->session()->flash('success', 'You have been logged out. Update Confirmation Email sent to your email address.');

        // log the user out of application
        Auth::logout();
    }

    public function sendUpdateEmail($student)
    {
        // handle invalid email by catching error of Mail method

        // send confirmation e-mail
        Mail::to($student['temp_email'])->send(new updateEmail($student));
    }

    /**
     * Update the specified resource in Users table after e-mail confirmation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateProfileConfirmed(Request $request, $id, $temp_email, $update_token)
    {
        // get variables from URL to decrypt and pass to controller logic 
        $temp_email = Crypt::decrypt($temp_email);
        $id = Crypt::decrypt($id);

        $nullCheck = User::where(['id' => $id])->first();
        if ($nullCheck->temp_email == NULL && $nullCheck->update_token == NULL) {
            return view('confirmationLinkExpired');
        }

        // query the user from the Users table
        $student = User::where(['id' => $id, 'temp_email' => $temp_email, 'update_token' => $update_token])->first();

        // check if student is clicking on the latest link
        if ($student == NULL) {
            return view('confirmationLinkExpired');
        }


        // check for token expiration after 24 hours
        $timeOfRequest = $student->updated_at;
        $expiration = $timeOfRequest->addHours(24);
        // if more than 24 hours have passed, reset values to NULL
        if ($student->updated_at->lt($expiration)) {
            if ($student) {
                // change data in the User table
                // User::where(['id' => $id, 'temp_email' => $temp_email, 'update_token' => $update_token])->update(['email' => $temp_email, 'temp_email' => NULL, 'approved_update' => '1', 'update_token' => NULL]);
                $u = User::find($id);
                $u->email = strtolower($temp_email);
                $u->temp_email = NULL;
                $u->approved_update = '1';
                $u->update_token = NULL;
                $u->save();
                // change e-mail address in the sddextr
                SDDEXTR::where(['INDEXNO' => $student->indexno])->update(['EMAIL' => strtolower($temp_email)]);

                // change e-mail address in the teachers table if profile is a teacher
                $teacher = Teachers::where('IndexNo', $student->indexno)->first();
                if ($teacher) {
                    $teacher->update(['email' => strtolower($temp_email)]);
                }

                // redirect to login page to use new email as username 
                $request->session()->flash('success', 'E-mail address has been updated. Please log back in with your new e-mail address');
                return redirect('login');
            } else {

                return view('confirmationLinkUsed');
            }
        } else {
            // set data in the User table to NULL
            User::where(['id' => $id])->update(['temp_email' => NULL, 'update_token' => NULL]);
            return view('confirmationLinkExpired');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
