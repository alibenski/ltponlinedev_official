<?php

namespace App\Http\Controllers;

use App\File;
use App\Language;
use App\NewUser;
use App\PlacementForm;
use App\Preenrolment;
use App\Repo;
use App\SDDEXTR;
use App\TORGAN;
use App\Term;
use App\Time;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Session;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct() {
        
        $this->middleware(['auth', 'isAdmin']); //isAdmin middleware lets only users with a specific permission to access these resources
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = \Request::input('search');
        // Returns an array of users that have the query string located somewhere within 
        // our users name or email fields. Paginates them so we can break up lots of search results.
        $users = User::where('name', 'LIKE', '%' . $query . '%')->orWhere('email', 'LIKE', '%' . $query . '%')
            ->paginate(20);    
        if ($users->getCollection()->count() == 0) {
                        // $request->session()->flash('interdire-msg', 'No such user found in the login accounts records of the system. ');
                        return redirect()->route('users.index')->with('users', $users)->with('interdire-msg', 'No such user found in the login accounts records of the system. ');
                    } 
  
        return view('users.index')->with('users', $users);             
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Get all roles and pass it to the view
        $roles = Role::get();
        $cat = DB::table('LTP_Cat')->pluck("Description","Cat")->all();
        $org = TORGAN::get(["Org Full Name","Org name"]);

        return view('users.create', ['roles'=>$roles])->withCat($cat)->withOrg($org);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->decision == 0) {
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
                    'dob' => 'required',
            ));

            //store in database
            $newUser = new NewUser;
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
            $newUser->save();

            $ext_index = 'EXT'.$newUser->id;
            $request->merge(['indexno' => $ext_index]); 

            $user = $this->storeValidatedStudent($request);

            return redirect()->route('manage-user-enrolment-data', $user->id)
            ->with('flash_message',
             'User successfully added.');
        } 

        // else if decision == 1, then create with index no. 
        $user = $this->storeValidatedStudent($request);

        return redirect()->route('manage-user-enrolment-data', $user->id)
            ->with('flash_message',
             'User successfully added.');
    }

    public function storeValidatedStudent($request)
    {
        //Validate name, email and password fields
        $rules_user = [
            'indexno' => 'required|unique:users',
            'nameFirst'=>'required|max:120',
            'nameLast'=>'required|max:120',
            'email'=>'required|email|unique:users',
            // 'password'=>'required|min:6|confirmed'
            ];            
        $customMessagesUser = [
                'unique' => 'The :attribute already exists in the Auth Table.'
                ];

        $this->validate($request, $rules_user, $customMessagesUser);

        // if staff exists in sddextr table, copy data to auth table
        $query_sddextr_record = SDDEXTR::where('INDEXNO', $request->indexno)->orWhere('EMAIL', $request->email)->first();
        
        // if staff does not exist in auth table but index or email exists in sddextr, create auth record and send credentials
        if ($query_sddextr_record) {

            $this->validate($query_sddextr_record, [
                'INDEXNO' => 'required|unique:users,indexno',
                'INDEXNO_old' => 'required|unique:users,indexno_old',
                'EMAIL'=>'required|email|unique:users,email'
            ]);

            $user = User::create([ 
                'indexno_old' => $query_sddextr_record->INDEXNO_old,
                'indexno' => $query_sddextr_record->INDEXNO,
                'profile' => $request->profile,
                'email' => $query_sddextr_record->EMAIL, 
                'nameFirst' => $query_sddextr_record->FIRSTNAME,
                'nameLast' => $query_sddextr_record->LASTNAME,
                'name' => $query_sddextr_record->FIRSTNAME.' '.$query_sddextr_record->LASTNAME,
                'password' => Hash::make('Welcome2CLM'),
                'must_change_password' => 1,
                'approved_account' => 1,
            ]);

            return $user;
        }


        // if not in auth table and sddextr table, create
        $user = User::create([ 
            'indexno' => $request->indexno,
            'indexno_old' => $request->indexno,
            'profile' => $request->profile,
            'email' => $request->email, 
            'nameFirst' => $request->nameFirst,
            'nameLast' => $request->nameLast,
            'name' => $request->nameFirst.' '.$request->nameLast,
            'password' => Hash::make('Welcome2CLM'),
            'must_change_password' => 1,
            'approved_account' => 1,
        ]); 
        
        //Send Auth credentials to student via email
        $sddextr_email_address = $request->email;
        // send credential email to user using email from sddextr 
        // Mail::to($sddextr_email_address)->send(new SendAuthMail($sddextr_email_address));
        
        $this->validate($request, [
            'indexno' => 'required|unique:SDDEXTR,INDEXNO_old',
            'indexno' => 'required|unique:SDDEXTR,INDEXNO',
            'email' => 'required|unique:SDDEXTR,EMAIL',
        ]);

        $user->sddextr()->create([
            'INDEXNO' => $request->indexno,
            'INDEXNO_old' => $request->indexno,
            'TITLE' => $request->title,
            'FIRSTNAME' => $request->nameFirst,
            'LASTNAME' => $request->nameLast,
            'EMAIL' => $request->email,
            'SEX' => $request->gender,
            'DEPT' => $request->org,
            'PHONE' => $request->contact_num,
            // 'CAT' => $request->cat,
        ]);

        $roles = $request['roles']; //Retrieving the roles field
        //Checking if a role was selected
        if (isset($roles)) {

            foreach ($roles as $role) {
            $role_r = Role::where('id', '=', $role)->firstOrFail();            
            $user->assignRole($role_r); //Assigning role to user
            }
        }

        return $user;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('users');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id); //Get user with specified id
        $roles = Role::get(); //Get all roles

        return view('users.edit', compact('user', 'roles')); //pass user and roles data to view
    }

    public function passwordReset($id)
    {
        $user = User::findOrFail($id); //Get user with specified id
        return view('users.reset', compact('user'));
    }

    public function resetPassword(Request $request, $id)
    {
        $user = User::findOrFail($id); //Get role specified by id

    //Validate name, email and password fields  
        $this->validate($request, [
            'password'=>'required|min:6|confirmed'
        ]);
        $input = ([ 
            'password' => Hash::make($request->password),
            'must_change_password' => 1,
        ]); //Retreive the name, email and password fields

        $user->fill($input)->save();
        return redirect()->route('users.index')
            ->with('flash_message',
             'User: '.$user->name.' password reset successful.');
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
        $user = User::findOrFail($id); //Get role specified by id

    //Validate name, email and password fields  
        $this->validate($request, [
            'name'=>'required|max:120',
            'email'=>'required|email|unique:users,email,'.$id,
            // 'password'=>'required|min:6|confirmed'
        ]);
        $input = ([ 
            'email' => $request->email, 
            'name' => $request->name,
            // 'password' => Hash::make($request->password),
        ]); //Retreive the name, email and password fields
        $roles = $request['roles']; //Retreive all roles
        $user->fill($input)->save();

        if (isset($roles)) {        
            $user->roles()->sync($roles);  //If one or more role is selected associate user to roles          
        }        
        else {
            $user->roles()->detach(); //If no role is selected remove exisiting role associated to a user
        }
        return redirect()->route('users.index')
            ->with('flash_message',
             'User successfully edited.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Find a user with a given id and delete
        $user = User::findOrFail($id); 
        $user->delete();

        return redirect()->route('users.index')
            ->with('flash_message',
             'User successfully deleted.');
    }

    public function manageUserEnrolmentData(Request $request, $id)
    {
        $id = $id;
        $student = User::where('id', $id)->first();
        $terms = Term::orderBy('Term_Code', 'desc')->get();
        $student_enrolments = Preenrolment::where('INDEXID', $student->indexno)
            ->where('Term', $request->Term)->get();
        $student_placements = PlacementForm::where('INDEXID', $student->indexno)
            ->where('Term', $request->Term)->get();
        $student_last_term = Repo::orderBy('Term', 'desc')->where('INDEXID', $student->indexno)->first(['Term']);
        $historical_data = Repo::orderBy('Term', 'desc')->where('INDEXID', $student->indexno)->get();
     
        if ($student_last_term == null) {
            $repos_lang = null;
            return view('users.manageUserEnrolmentData')->withTerms($terms)->withId($id)->withStudent($student)->withStudent_enrolments($student_enrolments)->withStudent_placements($student_placements)->withRepos_lang($repos_lang)->withHistorical_data($historical_data);
        }    

        $repos_lang = Repo::orderBy('Term', 'desc')->where('Term', $student_last_term->Term)
                ->where('INDEXID', $student->indexno)->first();

        if (is_null($request->Term)) {
            $student_enrolments = null;
            $student_placements = null;
            
            return view('users.manageUserEnrolmentData')->withTerms($terms)->withId($id)->withStudent($student)->withStudent_enrolments($student_enrolments)->withStudent_placements($student_placements)->withRepos_lang($repos_lang)->withHistorical_data($historical_data);
        }      

        return view('users.manageUserEnrolmentData')->withTerms($terms)->withId($id)->withStudent($student)->withStudent_enrolments($student_enrolments)->withStudent_placements($student_placements)->withRepos_lang($repos_lang)->withHistorical_data($historical_data);
    }

    public function enrolStudentToCourseForm(Request $request, $id)
    {
        $student = User::find($id);
        $terms = Term::orderBy('Term_Code', 'desc')->get();
        $languages = Language::pluck("name","code")->all();
        $orgs = Torgan::orderBy('Org Name', 'asc')->get(['Org Name','Org Full Name']);

        // dd($request, $id);
        return view('users.enrol-student-to-course-form', compact('languages', 'terms', 'student', 'orgs'));
    }

    public function enrolStudentToCourseInsert(Request $request)
    {
        $code_index_id = $request->Te_Code.'-'.$request->schedule_id.'-'.$request->Term.'-'.$request->INDEXID;
        $request->request->add(['CodeIndexID' => $code_index_id]);

        $this->validate($request, [
                'CodeIndexID' => 'unique:tblLTP_Enrolment,CodeIndexID|',
                'INDEXID' => 'required|',
                'profile' => 'required|',
                'DEPT' => 'required|',
                'Term' => 'required|',
                'L' => 'required|',
                'Te_Code' => 'required',
                'schedule_id' => 'required',
                'decision' => 'nullable',
                'Comments' => 'required|',
            ]);

        if (is_null($request->decision)) {
            $new_enrolment = Preenrolment::create([ 
                'CodeIndexID' => $request->Te_Code.'-'.$request->schedule_id.'-'.$request->Term.'-'.$request->INDEXID,
                'Code' => $request->Te_Code.'-'.$request->schedule_id.'-'.$request->Term,
                'schedule_id' => $request->schedule_id,
                'L' => $request->L,
                'profile' => $request->profile,
                'Te_Code' => $request->Te_Code,
                'Term' => $request->Term,
                'INDEXID' => $request->INDEXID,
                'is_self_pay_form' => null,
                'approval' => 1,
                'approval_hr' => 1,
                "created_at" =>  \Carbon\Carbon::now(),
                "updated_at" =>  \Carbon\Carbon::now(),
                'continue_bool' => 1,
                'DEPT' => $request->DEPT, 
                'eform_submit_count' => 1,              
                'form_counter' => 1,  
                'agreementBtn' => 1,
                'flexibleBtn' => 1,
                'Comments' => $request->Comments,
                // 'contractDate' => $contractDate,
            ]); 
        } else {
            $this->storeAttachedFiles($request);            
        }

        $request->session()->flash('success', 'Enrolment saved to the database.');
        return redirect()->route('manage-user-enrolment-data', $request->id);
    }

    public function storeAttachedFiles($request)
    {
        $this->validate($request, [
                'identityfile' => 'required|mimes:pdf,doc,docx|max:8000',
                'payfile' => 'required|mimes:pdf,doc,docx|max:8000',
            ]);

            //Store the attachments to storage path and save in db table
        if ($request->hasFile('identityfile')){
            $request->file('identityfile');
            $filename = $request->INDEXID.'_'.$request->Term.'_'.$request->Te_Code.'.'.$request->identityfile->extension();
            //Store attachment
            $filestore = Storage::putFileAs('public/pdf/'.$request->INDEXID, $request->file('identityfile'), 'id_'.$request->INDEXID.'_'.$request->Term.'_'.$request->Te_Code.'.'.$request->identityfile->extension());
            //Create new record in db table
            $attachment_identity_file = new File([
                    'filename' => $filename,
                    'size' => $request->identityfile->getClientSize(),
                    'path' => $filestore,
                            ]); 
            $attachment_identity_file->save();
        }
        if ($request->hasFile('payfile')){
            $request->file('payfile');
            $filename = $request->INDEXID.'_'.$request->Term.'_'.$request->Te_Code.'.'.$request->payfile->extension();
            //Store attachment
            $filestore = Storage::putFileAs('public/pdf/'.$request->INDEXID, $request->file('payfile'), 'payment_'.$request->INDEXID.'_'.$request->Term.'_'.$request->Te_Code.'.'.$request->payfile->extension());
            //Create new record in db table
            $attachment_pay_file = new File([
                    'filename' => $filename,
                    'size' => $request->payfile->getClientSize(),
                    'path' => $filestore,
                            ]); 
            $attachment_pay_file->save();
        } 

        // check if regular or placement selfpay form
        if ($request->Te_Code) {
            $this->enrolSelfPayStudentToCourseInsert($request, $attachment_identity_file, $attachment_pay_file);
        }
        else {
            $this->enrolSelfPayPlacementInsert($request, $attachment_identity_file, $attachment_pay_file);
        }

    }

    public function enrolSelfPayStudentToCourseInsert($request, $attachment_identity_file, $attachment_pay_file)
    {
        $new_enrolment = Preenrolment::create([ 
                'CodeIndexID' => $request->Te_Code.'-'.$request->schedule_id.'-'.$request->Term.'-'.$request->INDEXID,
                'Code' => $request->Te_Code.'-'.$request->schedule_id.'-'.$request->Term,
                'schedule_id' => $request->schedule_id,
                'L' => $request->L,
                'profile' => $request->profile,
                'Te_Code' => $request->Te_Code,
                'Term' => $request->Term,
                'INDEXID' => $request->INDEXID,
                'is_self_pay_form' => 1,
                'attachment_id' => $attachment_identity_file->id,
                'attachment_pay' => $attachment_pay_file->id,
                'selfpay_approval' => 1,
                "created_at" =>  \Carbon\Carbon::now(),
                "updated_at" =>  \Carbon\Carbon::now(),
                'continue_bool' => 1,
                'DEPT' => $request->DEPT, 
                'eform_submit_count' => 1,              
                'form_counter' => 1,  
                'agreementBtn' => 1,
                'flexibleBtn' => 1,
                'Comments' => $request->Comments,
                // 'contractDate' => $contractDate,
            ]);
    }

    public function enrolStudentToPlacementForm(Request $request, $id)
    {
        $student = User::find($id);
        $terms = Term::orderBy('Term_Code', 'desc')->get();
        $languages = Language::pluck("name","code")->all();
        $orgs = Torgan::orderBy('Org Name', 'asc')->get(['Org Name','Org Full Name']);
        $times = Time::all();

        // dd($request, $id);
        return view('users.enrol-student-to-placement-form', compact('languages', 'terms', 'student', 'orgs','times'));
    }

    public function enrolStudentToPlacementInsert(Request $request)
    {
        // to-do: add validation rule for composite fields Term, L, INDEXID
        $rules = [
                'INDEXID' => 'required|',
                'Term' => 'required|',
                'L' => 'required|',
                'L' => Rule::unique('tblLTP_Placement_Forms')->where(function ($query) use($request) {
                        $query->where('INDEXID', $request->INDEXID)
                        ->where('L', $request->L)
                        ->where('Term', $request->Term);

                        return $query->count() === 0; 
                    }),
                'profile' => 'required|',
                'DEPT' => 'required|',
                'placementLang' => 'required|integer',
                'placement_time' => 'required|integer',
                'decision' => 'nullable',
                'Comments' => 'required|',
                ];

        $customMessages = [
                'unique' => 'Placement form for that language already exists.'
                ];

        $this->validate($request, $rules, $customMessages);

        if (is_null($request->decision)) {
            $new_enrolment = PlacementForm::create([ 
                'L' => $request->L,
                'placement_schedule_id' => $request->placementLang,
                'placement_time' => $request->placement_time,
                'profile' => $request->profile,
                'Term' => $request->Term,
                'INDEXID' => $request->INDEXID,
                'is_self_pay_form' => null,
                'approval' => 1,
                'approval_hr' => 1,
                "created_at" =>  \Carbon\Carbon::now(),
                "updated_at" =>  \Carbon\Carbon::now(),
                'continue_bool' => 1,
                'DEPT' => $request->DEPT, 
                'eform_submit_count' => 1,              
                'form_counter' => 0,  
                'agreementBtn' => 1,
                'flexibleBtn' => 1,
                'Comments' => $request->Comments,
                // 'contractDate' => $contractDate,
            ]); 
        } else {
            $this->storeAttachedFiles($request);            
        }

        $request->session()->flash('success', 'Placement saved to the database.');
        return redirect()->route('manage-user-enrolment-data', $request->id);
    }

    public function enrolSelfPayPlacementInsert($request, $attachment_identity_file, $attachment_pay_file)
    {
        $new_enrolment = PlacementForm::create([ 
                'L' => $request->L,
                'placement_schedule_id' => $request->placementLang,
                'placement_time' => $request->placement_time,
                'profile' => $request->profile,
                'Term' => $request->Term,
                'INDEXID' => $request->INDEXID,
                'is_self_pay_form' => 1,
                'attachment_id' => $attachment_identity_file->id,
                'attachment_pay' => $attachment_pay_file->id,
                'selfpay_approval' => 1,
                "created_at" =>  \Carbon\Carbon::now(),
                "updated_at" =>  \Carbon\Carbon::now(),
                'continue_bool' => 1,
                'DEPT' => $request->DEPT, 
                'eform_submit_count' => 1,              
                'form_counter' => 0,  
                'agreementBtn' => 1,
                'flexibleBtn' => 1,
                'Comments' => $request->Comments,
                // 'contractDate' => $contractDate,
            ]);
    }
}
