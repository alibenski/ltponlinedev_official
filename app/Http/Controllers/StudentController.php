<?php

namespace App\Http\Controllers;

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
        $this->middleware('redirect-if-not-profile')->except('index','updateProfileConfirmed');
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
        $gender = DB::table('SEX')->limit(4)->pluck('Title', 'Title');
        $org = Torgan::orderBy('Org Name', 'asc')->get(['Org Name','Org Full Name']);
        
        return view('students.edit', compact('student', 'gender', 'org'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate data
        $this->validate($request, array(
                // 'title' => 'required|',
                // 'lastName' => 'required|string',
                // 'firstName' => 'required|string',
                // validate if email is unique 
                'email' => 'required_without_all:TITLE,lastName,firstName,org,contactNo,dob,jobAppointment,gradeLevel,organization|unique:users,email',
                // 'org' => 'required|',
                'contactNo' => 'regex:/^[0-9\-+]+$/|nullable',
                // 'jobAppointment' => 'required|string',
                // 'gradeLevel' => 'required|string',

            ));        
        
        // Save the data to db
        $student = User::findOrFail($id);

        if (is_null($request->input('email'))) {
            $this->updateNoEmail($student, $request);
            $request->session()->flash('success', 'Update successful.');
            return redirect()->route('home');
        } else {
            $this->updateWithEmail($student, $request);
            return redirect('login');
        }
    }

    public function updateNoEmail($student, $request)
    {
            if (!is_null($request->input('profile'))) {
                $student->profile = $request->input('profile');
            }
            if (!is_null($request->input('TITLE'))) {
                $student->sddextr->TITLE = $request->input('TITLE');
            }
            if (!is_null($request->input('firstName'))) {
                $student->nameFirst = $request->input('firstName');
                $student->sddextr->FIRSTNAME = $request->input('firstName');
            }
            if (!is_null($request->input('lastName'))) {
                $student->nameLast = $request->input('lastName');
                $student->sddextr->LASTNAME = $request->input('lastName');
            }
            if (!is_null($request->input('organization'))) {
                        $student->sddextr->DEPT = $request->input('organization');
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

            $student->name = $student->nameFirst.' '.$student->nameLast;
            $student->save();
            $student->sddextr->save();
    }

    public function updateWithEmail($student, $request)
    {
            $this->updateNoEmail($student, $request);

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

        $nullCheck = User::where(['id'=>$id])->first();
        if ($nullCheck->temp_email == NULL && $nullCheck->update_token == NULL) {
            return view('confirmationLinkExpired');
        }
        
        // query the user from the Users table
        $student = User::where(['id'=>$id, 'temp_email'=>$temp_email, 'update_token'=>$update_token])->first();
        
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
                User::where(['id'=>$id, 'temp_email'=>$temp_email, 'update_token'=>$update_token])->update(['email'=>$temp_email, 'temp_email'=>NULL, 'approved_update'=>'1', 'update_token'=>NULL]);
                // change e-mail address in the sddextr
                SDDEXTR::where(['INDEXNO'=>$student->indexno])->update(['EMAIL'=>$temp_email]);

                // change e-mail address in the teachers table if profile is a teacher
                $teacher = Teachers::where('IndexNo', $student->indexno)->first();
                if ($teacher) {
                    $teacher->update(['email'=>$temp_email]);
                }

                // redirect to login page to use new email as username 
                $request->session()->flash('success', 'E-mail address has been updated. Please log back in with your new e-mail address');
                return redirect('login');
            } else {
                
                return view('confirmationLinkUsed');
            }
        } else {
            // set data in the User table to NULL
            User::where(['id'=>$id])->update(['temp_email'=>NULL, 'update_token'=>NULL]);
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
