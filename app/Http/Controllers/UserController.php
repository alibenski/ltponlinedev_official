<?php

namespace App\Http\Controllers;

use App\File;
use App\Language;
use App\ModifiedForms;
use App\NewUser;
use App\PlacementForm;
use App\Preenrolment;
use App\Repo;
use App\SDDEXTR;
use App\Torgan;
use App\Term;
use App\Time;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Session;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function updateIndexView()
    {

        // $getIndex = DB::connection('dev_db_ltpdata')->table('Local-SDDEXTR')->where('INDEXNO', '162089')->get();
        $getIndex = DB::connection('dev_db_ltpdata')->table('Local-SDDEXTR')->whereNotNull('INDEXNO')->get();

        $counter = [];
        $counterUser = [];
        $counterPASH = [];
        $counterEnrol = [];
        $counterPlacement = [];
        $counterModified = [];

        foreach ($getIndex as $key => $value) {
            // update SDDEXTR table
            $updateSDDEXTR = SDDEXTR::where('INDEXNO', $value->{'INDEXNO-August'});
            $checkIfExist = $updateSDDEXTR->get();

            if (!$checkIfExist->isEmpty()) {
                $count = $updateSDDEXTR->update(['INDEXNO' => $value->INDEXNO]);
                $counter[] = $count;
            }

            // update User table
            $updateUser = User::where('indexno', $value->{'INDEXNO-August'});
            $checkIfUserExist = $updateUser->get();

            if (!$checkIfUserExist->isEmpty()) {
                $countUser = $updateUser->update(['indexno' => $value->INDEXNO]);
                $counterUser[] = $countUser;
            }
        }

        return view('users.update-index-view', compact('getIndex', 'counter', 'counterUser', 'counterPASH', 'counterEnrol', 'counterPlacement', 'counterModified'));
    }


    public function updatePASH()
    {

        $pashTable = Repo::getModel()->getTable();

        // $runUpdate = \DB::update("UPDATE `{$pashTable}` SET `INDEXID` = CASE `INDEXID` WHEN 'EXT1036' THEN ? WHEN 'L21545' THEN ? END WHERE `INDEXID` IN ('EXT1036','L21545')", ['EXT123','EXT12345']);
        // return $runUpdate;

        $getLastKey = [];
        $getIndex = DB::connection('dev_db_ltpdata')->table('Local-SDDEXTR')->select('INDEXNO', 'INDEXNO-August')->whereNotNull('INDEXNO')->get();
        $getIndexChunk = $getIndex->chunk(100);

        foreach ($getIndexChunk as $key => $value) {
            $getLastKey[] = $key;
        }

        return view('users.update-pash-view', compact('getIndex', 'getLastKey'));
    }


    public function updatePASHIndexID(Request $request)
    {
        $pashTable = Repo::getModel()->getTable();
        $getIndex = DB::connection('dev_db_ltpdata')->table('Local-SDDEXTR')->select('INDEXNO', 'INDEXNO-August')->whereNotNull('INDEXNO')->get();
        $getIndexChunk = $getIndex->chunk(100);

        $i = [];
        $ids = [];
        $cases = [];
        $params = [];

        foreach ($getIndexChunk[$request->batchNo] as $key => $value) {
            $updatePASH = Repo::select('id')->where('INDEXID', $value->{'INDEXNO-August'});
            $checkIfPASHExist = $updatePASH->get();

            if (!$checkIfPASHExist->isEmpty()) {
                $id = $value->{'INDEXNO-August'};
                $cases[] = "WHEN '{$id}' THEN ?";
                $params[] = $value->INDEXNO;
                $ids[] = "'{$id}'";

                foreach ($checkIfPASHExist as $kk => $vv) {
                    $i[] = $vv->id;
                }
            }
        }


        if (empty($ids)) {
            $data = 'batch has been processed. no further changes needed.';
            return response()->json($data);
        }

        $ids = implode(',', $ids);
        $cases = implode(' ', $cases);
        $params[] = implode(',', $params);

        $runUpdate = \DB::update("UPDATE `{$pashTable}` SET `INDEXID` = CASE `INDEXID` {$cases} END WHERE `INDEXID` IN ({$ids})", $params);


        foreach ($i as $valuePASH) {
            $pashRecord = Repo::select('id', 'INDEXID', 'CodeIndexIDClass', 'CodeClass', 'CodeIndexID', 'Code')->find($valuePASH);

            if (!is_null($pashRecord->CodeClass)) {
                $pashRecord->CodeIndexIDClass = $pashRecord->CodeClass . '-' . $pashRecord->INDEXID;
            }

            if (is_null($pashRecord->Te_Code)) {
                $pashRecord->CodeIndexID = $pashRecord->Code . '/' . $pashRecord->INDEXID;
            } else {
                $pashRecord->CodeIndexID = $pashRecord->Code . '-' . $pashRecord->INDEXID;
            }

            $pashRecord->save();
        }

        $data = $i;
        return response()->json($data);
    }

    public function updatePASHTrashed()
    {
        $getIndex = DB::connection('dev_db_ltpdata')->table('Local-SDDEXTR')->whereNotNull('INDEXNO')->get();

        $counterPASH = [];

        foreach ($getIndex as $key => $value) {

            // update soft deleted PASH records
            $updatePASH = Repo::onlyTrashed()->where('INDEXID', $value->{'INDEXNO'});
            $checkIfPASHExist = $updatePASH->get();

            if (!$checkIfPASHExist->isEmpty()) {
                $countPASH = $updatePASH->update(['INDEXID' => $value->INDEXNO]);

                foreach ($checkIfPASHExist as $keyPASH => $valuePASH) {

                    $pashRecord = Repo::onlyTrashed()->find($valuePASH->id);

                    if (!is_null($pashRecord->CodeClass)) {
                        $pashRecord->CodeIndexIDClass = $pashRecord->CodeClass . '-' . $value->INDEXNO;
                    }

                    if (is_null($pashRecord->Te_Code)) {
                        $pashRecord->CodeIndexID = $pashRecord->Code . '/' . $value->INDEXNO;
                    } else {
                        $pashRecord->CodeIndexID = $pashRecord->Code . '-' . $value->INDEXNO;
                    }

                    $pashRecord->save();

                    $counterPASH[] = $countPASH;
                }
            }
        }

        $stringPASH = count($counterPASH);
        $data = $stringPASH . ' PASH soft-deleted records updated';
        return response()->json($data);
    }

    public function updateEnrolmentIndex()
    {
        $getIndex = DB::connection('dev_db_ltpdata')->table('Local-SDDEXTR')->whereNotNull('INDEXNO')->get();

        $counterEnrol = [];

        foreach ($getIndex as $key => $value) {

            // update Enrolment table
            $updateEnrolment = Preenrolment::withTrashed()->select('id', 'INDEXID', 'Code', 'CodeIndexID')->where('INDEXID', $value->{'INDEXNO-August'});
            $checkIfEnrolmentExist = $updateEnrolment->get();

            if (!$checkIfEnrolmentExist->isEmpty()) {
                $countEnrol = $updateEnrolment->update(['INDEXID' => $value->INDEXNO]);

                foreach ($checkIfEnrolmentExist as $keyEnrolment => $valueEnrolment) {

                    $enrolmentRecord = Preenrolment::withTrashed()->select('id', 'INDEXID', 'Code', 'CodeIndexID')->find($valueEnrolment->id);

                    if (!is_null($enrolmentRecord->Code)) {
                        $enrolmentRecord->CodeIndexID = $enrolmentRecord->Code . '-' . $value->INDEXNO;
                    }

                    $enrolmentRecord->save();

                    $counterEnrol[] = $countEnrol;
                }
            }
        }

        if (empty($counterEnrol)) {
            $data = 0;
            return response()->json($data);
        }

        $data = count($counterEnrol);
        return response()->json($data);
    }

    public function updatePlacementIndex()
    {
        $getIndex = DB::connection('dev_db_ltpdata')->table('Local-SDDEXTR')->whereNotNull('INDEXNO')->get();

        $counterPlacement = [];

        foreach ($getIndex as $key => $value) {

            // update Placement table
            $updatePlacement = PlacementForm::withTrashed()->select('id', 'INDEXID', 'Code', 'CodeIndexID')->where('INDEXID', $value->{'INDEXNO-August'});
            $checkIfPlacementExist = $updatePlacement->get();

            if (!$checkIfPlacementExist->isEmpty()) {
                $countPlacement = $updatePlacement->update(['INDEXID' => $value->INDEXNO]);

                foreach ($checkIfPlacementExist as $keyPlacement => $valuePlacement) {

                    $placementRecord = PlacementForm::withTrashed()->select('id', 'INDEXID', 'Code', 'CodeIndexID')->find($valuePlacement->id);

                    if (!is_null($placementRecord->Code)) {
                        $placementRecord->CodeIndexID = $placementRecord->Code . '-' . $value->INDEXNO;
                    }

                    $placementRecord->save();

                    $counterPlacement[] = $countPlacement;
                }
            }
        }

        if (empty($counterPlacement)) {
            $data = 0;
            return response()->json($data);
        }

        $data = count($counterPlacement);
        return response()->json($data);
    }

    public function updateModifiedFormsIndex()
    {
        $getIndex = DB::connection('dev_db_ltpdata')->table('Local-SDDEXTR')->whereNotNull('INDEXNO')->get();

        $counterModified = [];

        foreach ($getIndex as $key => $value) {
            // update Modified Forms table
            $updateModified = ModifiedForms::withTrashed()->select('id', 'INDEXID', 'Code', 'CodeIndexID')->where('INDEXID', $value->{'INDEXNO-August'});
            $checkIfModifiedExist = $updateModified->get();

            if (!$checkIfModifiedExist->isEmpty()) {
                $countModified = $updateModified->update(['INDEXID' => $value->INDEXNO]);

                foreach ($checkIfModifiedExist as $keyModified => $valueModified) {

                    $modifiedRecord = ModifiedForms::withTrashed()->select('id', 'INDEXID', 'Code', 'CodeIndexID')->find($valueModified->id);

                    if (!is_null($modifiedRecord->Code)) {
                        $modifiedRecord->CodeIndexID = $modifiedRecord->Code . '-' . $value->INDEXNO;
                    }

                    $modifiedRecord->save();

                    $counterModified[] = $countModified;
                }
            }
        }

        if (empty($counterModified)) {
            $data = 0;
            return response()->json($data);
        }

        $data = count($counterModified);
        return response()->json($data);
    }

    public function __construct()
    {

        $this->middleware(['auth', 'isAdmin']); //isAdmin middleware lets only users with a specific permission to access these resources
    }

    public function user_switch_start(Request $request, $new_user)
    {
        $new_user = User::find($new_user);
        Session::put('orig_user', Auth::id());
        Auth::login($new_user);

        return redirect()->back();
    }

    public function user_switch_stop()
    {
        $id = Session::pull('orig_user');
        $orig_user = User::find($id);
        Auth::login($orig_user);

        return redirect()->back();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Request::input('search')) {
            $query = \Request::input('search');
            // Returns an array of users that have the query string located somewhere within 
            // our users name or email fields. Paginates them so we can break up lots of search results.
            $users = User::search($query)->paginate(20);
            if ($users->getCollection()->count() == 0) {
                // $request->session()->flash('interdire-msg', 'No such user found in the login accounts records of the system. ');
                return redirect()->route('users.index')->with('users', $users)->with('interdire-msg', 'No such user found in the login accounts records of the system. ');
            }

            return view('users.index')->with('users', $users);
        }
        $users = User::paginate(20);
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
        $cat = DB::table('LTP_Cat')->pluck("Description", "Cat")->all();
        $org = Torgan::get(["Org Full Name", "Org name"]);

        return view('users.create', ['roles' => $roles, 'org' => $org, 'cat' => $cat]);
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
            $newUser->name = $request->nameFirst . ' ' . $request->nameLast;
            $newUser->nameLast = $request->nameLast;
            $newUser->nameFirst = $request->nameFirst;
            $newUser->email = $request->email;
            $newUser->org = $request->org;
            $newUser->contact_num = $request->contact_num;
            $newUser->dob = $request->dob;
            $newUser->approved_account = 1;
            $newUser->save();

            $ext_index = 'EXT' . $newUser->id;
            $request->merge(['indexno' => $ext_index]);

            $user = $this->storeValidatedStudent($request);

            return redirect()->route('manage-user-enrolment-data', $user->id)
                ->with(
                    'flash_message',
                    'User successfully added.'
                );
        }

        // else if decision == 1, then create with index no. 
        $user = $this->storeValidatedStudent($request);

        return redirect()->route('manage-user-enrolment-data', $user->id)
            ->with(
                'flash_message',
                'User successfully added.'
            );
    }

    public function storeValidatedStudent($request)
    {
        //Validate name, email and password fields
        $rules_user = [
            'indexno' => 'required|unique:users',
            'nameFirst' => 'required|max:120',
            'nameLast' => 'required|max:120',
            'email' => 'required|email|unique:users',
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
            $query_sddextr_record_array = $query_sddextr_record->toArray();

            $validator = Validator::make($query_sddextr_record_array, [
                'INDEXNO' => 'required|unique:users,indexno',
                'INDEXNO_old' => 'required|unique:users,indexno_old',
                'EMAIL' => 'required|email|unique:users,email'
            ]);

            $user = User::create([
                'indexno_old' => $query_sddextr_record->INDEXNO_old,
                'indexno' => $query_sddextr_record->INDEXNO,
                'profile' => $request->profile,
                'email' => $query_sddextr_record->EMAIL,
                'nameFirst' => $query_sddextr_record->FIRSTNAME,
                'nameLast' => $query_sddextr_record->LASTNAME,
                'name' => $query_sddextr_record->FIRSTNAME . ' ' . $query_sddextr_record->LASTNAME,
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
            'name' => $request->nameFirst . ' ' . $request->nameLast,
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
            'BIRTH' => $request->dob,
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
            'password' => 'required|min:6|confirmed'
        ]);
        $input = ([
            'password' => Hash::make($request->password),
            'must_change_password' => 1,
        ]); //Retreive the name, email and password fields

        $user->fill($input)->save();
        return redirect()->route('users.index')
            ->with(
                'flash_message',
                'User: ' . $user->name . ' password reset successful.'
            );
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
            'name' => 'required|max:120',
            'email' => 'required|email|unique:users,email,' . $id,
            // 'password'=>'required|min:6|confirmed'
        ]);
        $input = ([
            'email' => $request->email,
            'name' => $request->name,
            // 'password' => Hash::make($request->password),
        ]); //Retreive the name, email and password fields
        $roles = $request['roles']; //Retreive all roles

        // update users table with new email
        $user->fill($input)->save();

        // update SDDEXTR table with new email
        $sddextr = SDDEXTR::where('INDEXNO', $user->indexno)->get();
        foreach ($sddextr as $record) {
            $record->update(['EMAIL' => $request->email]);
        }


        if (isset($roles)) {
            $user->roles()->sync($roles);  //If one or more role is selected associate user to roles          
        } else {
            $user->roles()->detach(); //If no role is selected remove exisiting role associated to a user
        }
        return redirect()->route('users.index')
            ->with('flash_message', 'User successfully edited.');
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
        $sddextr = SDDEXTR::where('INDEXNO', $user->indexno)->first();
        $sddextr->delete();
        $user->delete();

        return redirect()->route('users.index')
            ->with(
                'flash_message',
                'User successfully deleted.'
            );
    }

    public function manageUserEnrolmentData(Request $request, $id)
    {
        $id = $id;
        $student = User::where('id', $id)->first();
        $terms = Term::orderBy('Term_Code', 'desc')->get();

        $student_enrolments = Preenrolment::withTrashed()->where('INDEXID', $student->indexno)
            ->where('Term', $request->Term)
            ->groupBy(['Te_Code', 'Term', 'INDEXID', 'DEPT', 'is_self_pay_form', 'selfpay_approval', 'continue_bool', 'form_counter', 'deleted_at', 'eform_submit_count', 'cancelled_by_student', 'cancelled_by_admin', 'created_at', 'L', 'approval', 'approval_hr', 'attachment_id', 'attachment_pay', 'modified_by', 'updated_by_admin', 'std_comments', 'admin_eform_cancel_comment'])
            ->get(['Te_Code', 'Term', 'INDEXID', 'DEPT', 'is_self_pay_form', 'selfpay_approval', 'continue_bool', 'form_counter', 'deleted_at', 'eform_submit_count', 'cancelled_by_student', 'cancelled_by_admin', 'created_at', 'L', 'approval', 'approval_hr', 'attachment_id', 'attachment_pay', 'modified_by', 'updated_by_admin', 'std_comments', 'admin_eform_cancel_comment']);

        $student_placements = PlacementForm::withTrashed()
            ->orderBy('id', 'asc')
            ->where('INDEXID', $student->indexno)
            ->where('Term', $request->Term)->get();

        $student_convoked = Repo::withTrashed()->whereNotNull('CodeIndexIDClass')->where('INDEXID', $student->indexno)->where('Term', $request->Term)->get();

        $batch_implemented = Repo::where('Term', $request->Term)->count(); // flag to indicate if batch has been ran or not

        $student_last_term = Repo::orderBy('Term', 'desc')->where('INDEXID', $student->indexno)->first(['Term']);
        $historical_data = Repo::orderBy('Term', 'desc')->where('INDEXID', $student->indexno)->get();
        $placement_records = PlacementForm::withTrashed()
            ->where('INDEXID', $student->indexno)
            ->get();

        $term_info = Term::where('Term_Code', $request->Term)->first();

        if ($student_last_term == null) {
            $repos_lang = null;
            return view('users.manageUserEnrolmentData', compact('terms', 'id', 'student', 'student_enrolments', 'student_placements', 'repos_lang', 'historical_data', 'placement_records', 'student_convoked', 'term_info', 'batch_implemented'));
        }

        $repos_lang = Repo::orderBy('Term', 'desc')->where('Term', $student_last_term->Term)
            ->where('INDEXID', $student->indexno)->first();

        if (is_null($request->Term)) {
            $student_enrolments = null;
            $student_placements = null;

            return view('users.manageUserEnrolmentData', compact('terms', 'id', 'student', 'student_enrolments', 'student_placements', 'repos_lang', 'historical_data', 'placement_records', 'student_convoked', 'term_info', 'batch_implemented'));
        }


        return view('users.manageUserEnrolmentData', compact('terms', 'id', 'student', 'student_enrolments', 'student_placements', 'repos_lang', 'historical_data', 'placement_records', 'student_convoked', 'term_info', 'batch_implemented'));
    }

    public function enrolStudentToCourseForm(Request $request, $id)
    {
        $student = User::find($id);
        $terms = Term::orderBy('Term_Code', 'desc')->get();
        $languages = Language::pluck("name", "code")->all();
        $orgs = Torgan::orderBy('Org name', 'asc')->get(['Org name', 'Org Full Name']);

        // dd($request, $id);
        return view('users.enrol-student-to-course-form', compact('languages', 'terms', 'student', 'orgs'));
    }

    public function enrolStudentToCourseInsert(Request $request)
    {
        $code_index_id = $request->Te_Code . '-' . $request->schedule_id . '-' . $request->Term . '-' . $request->INDEXID;
        $request->request->add(['CodeIndexID' => $code_index_id]);

        $this->validate($request, [
            'CodeIndexID' => 'unique:tblLTP_Enrolment,CodeIndexID,NULL,id,deleted_at,NULL|',  // do not include soft deleted records 
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

            // control the number of submitted enrolment forms
            $qryEformCount = Preenrolment::withTrashed()
                ->where('INDEXID', $request->INDEXID)
                ->where('Term', $request->Term)
                ->orderBy('eform_submit_count', 'desc')->first();

            $eform_submit_count = 1;
            if (isset($qryEformCount->eform_submit_count)) {
                $eform_submit_count = $qryEformCount->eform_submit_count + 1;
            }

            // control the number of submitted courses per enrolment form submission
            // set default value of $form_counter to 1 and then add succeeding
            $lastValueCollection = Preenrolment::withTrashed()
                ->where('Te_Code', $request->Te_Code)
                ->where('INDEXID', $request->INDEXID)
                ->where('Term', $request->Term)
                ->orderBy('form_counter', 'desc')->first();

            $form_counter = 1;
            if (isset($lastValueCollection->form_counter)) {
                $form_counter = $lastValueCollection->form_counter + 1;
            }

            $input = [
                'CodeIndexID' => $request->Te_Code . '-' . $request->schedule_id . '-' . $request->Term . '-' . $request->INDEXID,
                'Code' => $request->Te_Code . '-' . $request->schedule_id . '-' . $request->Term,
                'schedule_id' => $request->schedule_id,
                'L' => $request->L,
                'profile' => $request->profile,
                'Te_Code' => $request->Te_Code,
                'Term' => $request->Term,
                'INDEXID' => $request->INDEXID,
                'is_self_pay_form' => null,
                'approval' => 1,
                'approval_hr' => 1,
                "created_at" =>  $request->created_at,
                "updated_at" =>  \Carbon\Carbon::now(),
                'continue_bool' => 1,
                'DEPT' => $request->DEPT,
                'eform_submit_count' => $eform_submit_count,
                'form_counter' => $form_counter,
                'agreementBtn' => 1,
                'flexibleBtn' => 1,
                'overall_approval' => 1,
                'Comments' => $request->Comments,
                // 'contractDate' => $contractDate,
            ];
            array_filter($input);

            $new_enrolment = Preenrolment::create($input);
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
        if ($request->hasFile('identityfile')) {
            $request->file('identityfile');
            $filename = $request->INDEXID . '_' . $request->Term . '_' . $request->Te_Code . '.' . $request->identityfile->extension();
            //Store attachment
            $filestore = Storage::putFileAs('public/pdf/' . $request->INDEXID, $request->file('identityfile'), 'id_' . $request->INDEXID . '_' . $request->Term . '_' . $request->Te_Code . '.' . $request->identityfile->extension());
            //Create new record in db table
            $attachment_identity_file = new File([
                'filename' => $filename,
                'size' => $request->identityfile->getClientSize(),
                'path' => $filestore,
            ]);
            $attachment_identity_file->save();
        }
        if ($request->hasFile('payfile')) {
            $request->file('payfile');
            $filename = $request->INDEXID . '_' . $request->Term . '_' . $request->Te_Code . '.' . $request->payfile->extension();
            //Store attachment
            $filestore = Storage::putFileAs('public/pdf/' . $request->INDEXID, $request->file('payfile'), 'payment_' . $request->INDEXID . '_' . $request->Term . '_' . $request->Te_Code . '.' . $request->payfile->extension());
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
        } else {
            $this->enrolSelfPayPlacementInsert($request, $attachment_identity_file, $attachment_pay_file);
        }
    }

    public function enrolSelfPayStudentToCourseInsert($request, $attachment_identity_file, $attachment_pay_file)
    {
        // control the number of submitted enrolment forms
        $qryEformCount = Preenrolment::withTrashed()
            ->where('INDEXID', $request->INDEXID)
            ->where('Term', $request->Term)
            ->orderBy('eform_submit_count', 'desc')->first();

        $eform_submit_count = 1;
        if (isset($qryEformCount->eform_submit_count)) {
            $eform_submit_count = $qryEformCount->eform_submit_count + 1;
        }

        // control the number of submitted courses per enrolment form submission
        // set default value of $form_counter to 1 and then add succeeding
        $lastValueCollection = Preenrolment::withTrashed()
            ->where('Te_Code', $request->Te_Code)
            ->where('INDEXID', $request->INDEXID)
            ->where('Term', $request->Term)
            ->orderBy('form_counter', 'desc')->first();

        $form_counter = 1;
        if (isset($lastValueCollection->form_counter)) {
            $form_counter = $lastValueCollection->form_counter + 1;
        }

        $new_enrolment = Preenrolment::create([
            'CodeIndexID' => $request->Te_Code . '-' . $request->schedule_id . '-' . $request->Term . '-' . $request->INDEXID,
            'Code' => $request->Te_Code . '-' . $request->schedule_id . '-' . $request->Term,
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
            "created_at" =>  $request->created_at,
            "updated_at" =>  \Carbon\Carbon::now(),
            'continue_bool' => 1,
            'DEPT' => $request->DEPT,
            'eform_submit_count' => $eform_submit_count,
            'form_counter' => $form_counter,
            'agreementBtn' => 1,
            'flexibleBtn' => 1,
            'overall_approval' => 1,
            'Comments' => $request->Comments,
            // 'contractDate' => $contractDate,
        ]);
    }

    public function enrolStudentToPlacementForm(Request $request, $id)
    {
        $student = User::find($id);
        $terms = Term::orderBy('Term_Code', 'desc')->get();
        $languages = Language::pluck("name", "code")->all();
        $orgs = Torgan::orderBy('Org name', 'asc')->get(['Org name', 'Org Full Name']);
        $times = Time::all();

        // dd($request, $id);
        return view('users.enrol-student-to-placement-form', compact('languages', 'terms', 'student', 'orgs', 'times'));
    }

    public function enrolStudentToPlacementInsert(Request $request)
    {
        $rules = [
            'INDEXID' => 'required|',
            'Term' => 'required|',
            'L' => 'required|',

            /**
             * validation rule function for composite fields was taken out due to the need of admin 
             * to create another placement form for students who want to attend more than 1 course 
             * of the same language
             */

            // validation rule function for composite fields Term, L, INDEXID
            // 'L' => Rule::unique('tblLTP_Placement_Forms')->where(function ($query) use($request) {
            //         $query->where('INDEXID', $request->INDEXID)
            //         ->where('L', $request->L)
            //         ->where('Term', $request->Term)
            //         ->whereNull('deleted_at');

            //         return $query->count() === 0; 
            //     }),
            'profile' => 'required|',
            'DEPT' => 'required|',
            'placementLang' => 'required|integer',
            'decision' => 'nullable',
            'Comments' => 'required|',
        ];

        $customMessages = [
            'unique' => 'Placement form for that language already exists.'
        ];

        $this->validate($request, $rules, $customMessages);

        if ($request->L != 'F') {

            $this->validate($request, [
                // 'placement_time' => 'required|integer',
            ]);
        }

        if (is_null($request->decision)) {
            // control the number of submitted enrolment forms
            $qryEformCount = PlacementForm::withTrashed()
                ->where('INDEXID', $request->INDEXID)
                ->where('Term', $request->Term)
                ->orderBy('eform_submit_count', 'desc')->first();

            $eform_submit_count = 1;
            if (isset($qryEformCount->eform_submit_count)) {
                $eform_submit_count = $qryEformCount->eform_submit_count + 1;
            }

            $lastValueCollection = PlacementForm::withTrashed()
                ->where('INDEXID', $request->INDEXID)
                ->where('L', $request->L)
                ->where('Term', $request->Term)
                ->orderBy('form_counter', 'desc')->first();

            $form_counter = 0;
            if (isset($lastValueCollection->form_counter)) {
                $form_counter = $lastValueCollection->form_counter + 1;
            }

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
                "created_at" =>  $request->created_at,
                "updated_at" =>  \Carbon\Carbon::now(),
                'continue_bool' => 1,
                'DEPT' => $request->DEPT,
                'eform_submit_count' => $eform_submit_count,
                'form_counter' => $form_counter,
                'agreementBtn' => 1,
                'flexibleBtn' => 1,
                'overall_approval' => 1,
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
        // control the number of submitted enrolment forms
        $qryEformCount = PlacementForm::withTrashed()
            ->where('INDEXID', $request->INDEXID)
            ->where('Term', $request->Term)
            ->orderBy('eform_submit_count', 'desc')->first();

        $eform_submit_count = 1;
        if (isset($qryEformCount->eform_submit_count)) {
            $eform_submit_count = $qryEformCount->eform_submit_count + 1;
        }

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
            "created_at" =>  $request->created_at,
            "updated_at" =>  \Carbon\Carbon::now(),
            'continue_bool' => 1,
            'DEPT' => $request->DEPT,
            'eform_submit_count' => $eform_submit_count,
            'form_counter' => 0,
            'agreementBtn' => 1,
            'flexibleBtn' => 1,
            'overall_approval' => 1,
            'Comments' => $request->Comments,
            // 'contractDate' => $contractDate,
        ]);
    }
}
