<?php

namespace App\Http\Controllers;

use App\Classroom;
use App\Course;
use App\FocalPoints;
use App\Jobs\SendEmailJob;
use App\Language;
use App\Mail\MailtoApprover;
use App\Mail\SendMailable;
use App\Mail\SendMailableReminderPlacement;
use App\Mail\SendReminderEmailHR;
use App\Mail\SendReminderEmailPlacementHR;
use App\PlacementForm;
use App\Preenrolment;
use App\Repo;
use App\SDDEXTR;
use App\Schedule;
use App\Term;
use App\Torgan;
use App\User;
use App\Waitlist;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Session;

class WaitlistController extends Controller
{
    public function testQuery()
    {
        $encryptedValue = Crypt::encrypt('L16008/');
        $encryptedValue2 = Crypt::encrypt('C7R1/');
        $encryptedValue3 = Crypt::encrypt('207/');
        $encryptedValue4 = Crypt::encrypt('1/');
        $encryptedValue5 = Crypt::encrypt('191');
        echo $encryptedValue;
        echo $encryptedValue2;
        echo $encryptedValue3;
        echo $encryptedValue4;
        echo $encryptedValue5;
        $decrypted = Crypt::decrypt($encryptedValue);
        
        return ;
    }
    // {
    //     $never_logged = User::where('must_change_password', 1)->get();
    //     $input = ([ 
    //         'password' => Hash::make('Welcome2CLM'),
    //     ]);
    //     foreach ($never_logged as $user) {
    //         $user->fill($input)->save();
    //     }

    //     dd($never_logged);
    // }
    // {
    //     $enrolments_no_mgr_approval = PlacementForm::where('Term', '188')->whereNull('is_self_pay_form')->whereNull('approval')->select('INDEXID', 'L', 'eform_submit_count', 'mgr_email','created_at')->groupBy('INDEXID', 'L', 'eform_submit_count', 'mgr_email','created_at')->get()->take(1);
    //     foreach ($enrolments_no_mgr_approval as  $valueMgrEmails) 
    //     {           
    //             $arrRecipient[] = $valueMgrEmails->mgr_email; 
    //             $recipient = $valueMgrEmails->mgr_email;

    //             $staff = User::where('indexno', $valueMgrEmails->INDEXID)->first();
    //             $input_course = PlacementForm::orderBy('id', 'desc')->where('Term', '188')->where('INDEXID', $valueMgrEmails->INDEXID)->where('L', $valueMgrEmails->L)->first();

    //             Mail::to('allyson.frias@un.org')->send(new SendMailableReminderPlacement($input_course, $staff));
    //             echo $recipient;
    //             echo '<br>';
    //             echo '<br>';   
    //     }
    //     $arrDept = [];
    //     $arrHrEmails = [];
    //     $enrolments_no_hr_approval = PlacementForm::where('Term', '188')->whereNull('is_self_pay_form')->whereNull('approval_hr')->where('approval', '1')->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS'])->get()->take(1);

    //     foreach ($enrolments_no_hr_approval as $valueDept) {
                
    //             $arrDept[] = $valueDept->DEPT;
    //             $torgan = Torgan::where('Org name', $valueDept->DEPT)->first();
    //             $learning_partner = $torgan->has_learning_partner;

    //             if ($learning_partner == '1') {
    //                 $query_hr_email = FocalPoints::where('org_id', $torgan->OrgCode)->get(['email']); 
    //                 $fp_email = $query_hr_email->map(function ($val, $key) {
    //                     return $val->email;
    //                 });
    //                 $fp_email_arr = $fp_email->toArray();
    //                 $arrHrEmails[] = $fp_email_arr;

    //                 $formItems = PlacementForm::orderBy('Term', 'desc')
    //                                 ->where('INDEXID', $valueDept->INDEXID)
    //                                 ->where('Term', '188')
    //                                 ->where('L', $valueDept->L)
    //                                 ->where('eform_submit_count', $valueDept->eform_submit_count)
    //                                 ->get();
    //                 $formfirst = PlacementForm::orderBy('Term', 'desc')
    //                                 ->where('INDEXID', $valueDept->INDEXID)
    //                                 ->where('Term', '188')
    //                                 ->where('L', $valueDept->L)
    //                                 ->where('eform_submit_count', $valueDept->eform_submit_count)
    //                                 ->first();   
    //                 // $staff_name = $formfirst->users->name;
    //                 $staff_name = $formfirst->users->name;
    //                 $arr[] = $staff_name;
    //                 $mgr_email = $formfirst->mgr_email;    
    //                 $input_course = $formfirst; 
    //                 // Mail::to($fp_email_arr);
    //                 Mail::to('allyson.frias@un.org')->send(new SendReminderEmailPlacementHR($formItems, $input_course, $staff_name, $mgr_email));
    //             }
    //     }
    //     // DB::table('jobs')->truncate();
    //     // Log::info("Start sending email");
    //     // for ($i=0; $i < 2; $i++)  {
    //     //     $emailJob = (new SendEmailJob())->delay(Carbon::now()->addSeconds(10));
    //     //     dispatch($emailJob);
    //     // }
    //     //     echo 'email sent<br>';
    //     // Log::info("Finished sending email");

    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    	$students = Waitlist::all();
        return view('waitlist.index')->withStudents($students);
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
        //
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
        //
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
