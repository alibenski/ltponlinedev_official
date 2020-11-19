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
use App\Term;
use App\Mail\EmailLateRegister;
use Carbon\Carbon;


class LateRegisterController extends Controller
{
    public function lateWhatOrg()
    {
        //get current year and date
        $now_date = Carbon::now()->toDateString();
        $now_year = Carbon::now()->year;
        // actual current term
        $terms = Term::orderBy('Term_Code', 'desc')->whereDate('Term_End', '>=', $now_date)->get()->min();
        // actual enrolment term
        $next_term = \App\Helpers\GlobalFunction::instance()->currentEnrolTermObject();

        $org = Torgan::orderBy('Org name', 'asc')->get(['Org name', 'Org Full Name']);
        // ->pluck('Org name','Org name', 'Org Full Name');

        $late = 1;

        return view('form.whatorg', compact('terms', 'next_term', 'org', 'late'));
    }

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

    public function lateRegister(Request $request)
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
            'dob' => 'required',
            'contractfile' => 'required|mimes:pdf,doc,docx|max:8000',
            'g-recaptcha-response' => 'required|captcha',
        ));

        //Store the attachments to storage path and save in db table
        if ($request->hasFile('contractfile')) {
            $request->file('contractfile');
            $filename = 'new_user_request_' . $request->nameLast . '_' . $request->nameFirst . '.' . $request->contractfile->extension();
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
        $newUser->name = $request->nameFirst . ' ' . $request->nameLast;
        $newUser->nameLast = $request->nameLast;
        $newUser->nameFirst = $request->nameFirst;
        $newUser->email = $request->email;
        $newUser->org = $request->org;
        $newUser->contact_num = $request->contact_num;
        $newUser->dob = $request->dob;
        $newUser->attachment_id = $attachment_contract_file->id;
        $newUser->save();

        // send email notification to Secretariat to approve his login credentials to the system and sddextr record
        Mail::raw("New UN user request for: " . $request->nameFirst . ' ' . $request->nameLast, function ($message) {
            $message->from('clm_onlineregistration@unog.ch', 'CLM Online Administrator');
            $message->to('clm_language@un.org')->subject('Notification: New Late User Request');
        });

        return redirect()->route('new_user_msg');
    }
}
