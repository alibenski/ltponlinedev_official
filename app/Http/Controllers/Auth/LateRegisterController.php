<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use App\Torgan;
use App\FileNewUser;
use App\NewUser;
use App\Mail\EmailLateRegister;
use App\Services\User\OhchrEmailChecker;



class LateRegisterController extends Controller
{
    protected function generateRandomURL(Request $request)
    {
        if ($request->ajax()) {

            $recordId = DB::table('url_generator')->insertGetId(
                ['user_id' => Auth::id(), 'email' => $request->email]
            );

            $url = URL::temporarySignedRoute('late-new-user-form', now()->addDays(1), ['transaction' => $recordId]);

            Mail::to($request->email)->send(new EmailLateRegister($url));

            return response()->json($url);
        }
    }

    public function lateUserManagement()
    {
        return view('users_new.late_user_management');
    }

    public function lateNewUserForm(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }
        $org = Torgan::get(["Org Full Name", "Org name"]);
        return view('users_new.late_new_user_form', compact('org'));
    }

    public function lateRegister(Request $request, OhchrEmailChecker $ohchrEmailChecker)
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
            'dob' => 'required',
            'contractfile' => 'required|mimes:pdf,doc,docx|max:8000',
            // 'g-recaptcha-response' => 'required|captcha',
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
                'size' => $request->contractfile->getClientSize(),
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
                'size' => $request->contractfile2->getClientSize(),
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
            $message->from('clm_onlineregistration@unog.ch', 'CLM Online Administrator');
            $message->to('clm_language@un.org')->subject('Notification: New Late User Request');
        });

        return redirect()->route('new_user_msg');
    }
}
