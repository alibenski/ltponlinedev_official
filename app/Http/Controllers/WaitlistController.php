<?php

namespace App\Http\Controllers;

use App\Classroom;
use App\Course;
use App\FocalPoints;
use App\Jobs\SendEmailJob;
use App\Language;
use App\Mail\MailtoApprover;
use App\Mail\SendMailable;
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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Session;

class WaitlistController extends Controller
{
    public function testQuery()
    {
        // DB::table('jobs')->truncate();
        // Log::info("Start sending email");
        // for ($i=0; $i < 2; $i++)  {
        //     $emailJob = (new SendEmailJob())->delay(Carbon::now()->addSeconds(10));
        //     dispatch($emailJob);
        // }
        //     echo 'email sent<br>';
        // Log::info("Finished sending email");
        //get current year and date
        $now_date = Carbon::now();
        $now_year = Carbon::now()->year; 
        $enrolment_term = Term::whereYear('Term_End', $now_year)
                        ->orderBy('Term_Code', 'desc')
                        ->where('Approval_Date_Limit', '>=', $now_date)
                        ->value('Term_Code');
        if (empty($enrolment_term)) {
            Log::info("Term is null. No Emails sent.");
            echo "Term is null. No Emails sent.";
            return exit();
        }

        $arr = [];
        $enrolments_no_mgr_approval = Preenrolment::where('Term', $enrolment_term)->whereNull('is_self_pay_form')->whereNull('approval')->get();

        if ($enrolments_no_mgr_approval->isEmpty()) {
            Log::info("No email addresses to pick up. No Emails sent.");
            echo $enrolment_term;
            echo  $enrolments_no_mgr_approval;
            return exit();
        }
        foreach ($enrolments_no_mgr_approval as  $valueMgrEmails) 
        {
            $arr[] = $valueMgrEmails->mgr_email; 
            $recipient = $valueMgrEmails->mgr_email;
            $staff = User::where('indexno', $valueMgrEmails->INDEXID)->first();
            
            $input_course = Preenrolment::orderBy('Term', 'desc')->orderBy('id', 'desc')->where('INDEXID', $valueMgrEmails->INDEXID)->where('Term', $enrolment_term)->first();
            $input_schedules = Preenrolment::orderBy('Term', 'desc')
                                ->where('INDEXID', $valueMgrEmails->INDEXID)
                                ->where('Term', $enrolment_term)
                                ->where('Te_Code', $valueMgrEmails->Te_Code)
                                ->where('form_counter', $valueMgrEmails->form_counter)
                                ->get();
            echo $recipient;
            echo '<br>';
            echo '<br>';

            // Mail::to($recipient)->send(new SendMailable($input_course, $input_schedules, $staff));
            // Mail::raw("This is a test automated message", function($message) use ($recipient){
            //     $message->from('clm_language@unog.ch', 'CLM Language');
            //     $message->to($recipient)->subject('MGR - This is a test automated message');
            // });
        }
        dd($arr);
        // $arrDept = [];
        // $arrHrEmails = [];
        // $enrolments_no_hr_approval = Preenrolment::where('Term', '188')->whereNull('is_self_pay_form')->whereNull('approval_hr')->where('approval', '1')->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS'])->get();

        // foreach ($enrolments_no_hr_approval as $valueDept) {
        //     $arrDept[] = $valueDept->DEPT;
        //     $torgan = Torgan::where('Org name', $valueDept->DEPT)->first();
        //     $learning_partner = $torgan->has_learning_partner;

        //     if ($learning_partner == '1') {
        //         $query_hr_email = FocalPoints::where('org_id', $torgan->OrgCode)->get(['email']); 
        //         $fp_email = $query_hr_email->map(function ($val, $key) {
        //             return $val->email;
        //         });
        //         $fp_email_arr = $fp_email->toArray();
        //         $arrHrEmails[] = $fp_email_arr;

        //         // Mail::to($fp_email_arr);
        //         Mail::raw("This is a test automated message", function($message) use ($fp_email_arr) {
        //         Log::info("Start sending email");
        //         $message->from('clm_language@unog.ch', 'CLM Language');
        //         $message->to($fp_email_arr)->subject('HR - This is a test automated message');
        //         Log::info("Finished sending email");
        //     });
        //     }
        // }
        return 'task done';
        // dd($enrolments_no_mgr_approval, $arr, $enrolment_term,$arrDept,$arrHrEmails);
    }

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
