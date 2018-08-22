<?php

namespace App\Http\Controllers;

use App\FocalPoints;
use App\Mail\SendMailable;
use App\Mail\SendReminderEmailHR;
use App\Preenrolment;
use App\Repo;
use App\Term;
use App\Torgan;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PreenrolmentController extends Controller
{
    /**
     * Send reminder emails to manager and HR focalpoints.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendReminderEmails()
    {
        //get current year and date
        $now_date = Carbon::now();
        $now_year = Carbon::now()->year; 
        // get the correct enrolment term code
        $enrolment_term = Term::whereYear('Term_End', $now_year)
                        ->orderBy('Term_Code', 'desc')
                        ->where('Enrol_Date_Begin', '<=', $now_date)
                        ->where('Approval_Date_Limit', '>=', $now_date)
                        ->min('Term_Code');
        if (empty($enrolment_term)) {
            Log::info("Term is null. No Emails sent.");
            echo "Term is null. No Emails sent.";
            return exit();
        }

        $remind_mgr_param = Term::where('Term_Code', $enrolment_term)->value('Remind_Mgr_After'); // get int value after how many days reminder email should be sent

        $arrRecipient = [];
        $enrolments_no_mgr_approval = Preenrolment::where('Term', $enrolment_term)->whereNull('is_self_pay_form')->whereNull('approval')->select('INDEXID', 'Te_Code', 'form_counter', 'mgr_email','created_at')->groupBy('INDEXID', 'Te_Code', 'form_counter', 'mgr_email', 'created_at')->get();

        if ($enrolments_no_mgr_approval->isEmpty()) {
            Log::info("No email addresses to pick up. No Emails sent.");
            echo $enrolment_term;
            echo  $enrolments_no_mgr_approval;
            return exit();
        }
        foreach ($enrolments_no_mgr_approval as  $valueMgrEmails) 
        {
            if ($now_date >= Carbon::parse($valueMgrEmails->created_at)->addDays($remind_mgr_param)) {
            
                $arrRecipient[] = $valueMgrEmails->mgr_email; 
                $recipient = $valueMgrEmails->mgr_email;

                $staff = User::where('indexno', $valueMgrEmails->INDEXID)->first();
                $input_course = Preenrolment::orderBy('Term', 'desc')->orderBy('id', 'desc')->where('INDEXID', $valueMgrEmails->INDEXID)->where('Term', $enrolment_term)->first();
                $input_schedules = Preenrolment::orderBy('Term', 'desc')
                                    ->where('INDEXID', $valueMgrEmails->INDEXID)
                                    ->where('Term', $enrolment_term)
                                    ->where('Te_Code', $valueMgrEmails->Te_Code)
                                    ->where('form_counter', $valueMgrEmails->form_counter)
                                    ->get();
                Mail::to($recipient)->send(new SendMailable($input_course, $input_schedules, $staff));
                // Mail::raw("This is a test automated message", function($message) use ($recipient){
                //     $message->from('clm_language@unog.ch', 'CLM Language');
                //     $message->to('allyson.frias@un.org')->subject('MGR - This is a test automated message');
                // });
                echo $recipient;
                echo '<br>';
                echo '<br>';
            }
        }

        $remind_hr_param = Term::where('Term_Code', $enrolment_term)->value('Remind_HR_After');

        $arrDept = [];
        $arrHrEmails = [];
        $enrolments_no_hr_approval = Preenrolment::where('Term', $enrolment_term)->whereNull('is_self_pay_form')->whereNull('approval_hr')->where('approval', '1')->whereNotIn('DEPT', ['UNOG','JIU','DDA','OIOS'])->select('INDEXID', 'Te_Code', 'form_counter', 'mgr_email', 'DEPT', 'UpdatedOn')->groupBy('INDEXID', 'Te_Code', 'form_counter', 'mgr_email', 'DEPT','UpdatedOn')->get();

        foreach ($enrolments_no_hr_approval as $valueDept) {
            if ($now_date >= Carbon::parse($valueDept->UpdatedOn)->addDays($remind_hr_param)) {
                
                $arrDept[] = $valueDept->DEPT;
                $torgan = Torgan::where('Org name', $valueDept->DEPT)->first();
                $learning_partner = $torgan->has_learning_partner;

                if ($learning_partner == '1') {
                    $query_hr_email = FocalPoints::where('org_id', $torgan->OrgCode)->get(['email']); 
                    $fp_email = $query_hr_email->map(function ($val, $key) {
                        return $val->email;
                    });
                    $fp_email_arr = $fp_email->toArray();
                    $arrHrEmails[] = $fp_email_arr;

                    $formItems = Preenrolment::orderBy('Term', 'desc')
                                    ->where('INDEXID', $valueDept->INDEXID)
                                    ->where('Term', $enrolment_term)
                                    ->where('Te_Code', $valueDept->Te_Code)
                                    ->where('form_counter', $valueDept->form_counter)
                                    ->get();
                    $formfirst = Preenrolment::orderBy('Term', 'desc')
                                    ->where('INDEXID', $valueDept->INDEXID)
                                    ->where('Term', $enrolment_term)
                                    ->where('Te_Code', $valueDept->Te_Code)
                                    ->where('form_counter', $valueDept->form_counter)
                                    ->first();   
                    $staff_name = $formfirst->users->name;
                    $mgr_email = $formfirst->mgr_email;    
                    $input_course = $formfirst; 
                    // Mail::to($fp_email_arr);
                    Mail::to($fp_email_arr)->send(new SendReminderEmailHR($formItems, $input_course, $staff_name, $mgr_email));
                }
            }
        }
        // dd($arrRecipient, $enrolments_no_mgr_approval, $arrHrEmails,$formfirst);
        return 'reminder enrolment emails sent';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $languages = DB::table('languages')->pluck("name","code")->all();
        $org = Torgan::orderBy('Org Name', 'asc')->get(['Org Name','Org Full Name']);
        $terms = Term::orderBy('Term_Code', 'desc')->get();



        if (is_null($request->Term)) {
            $enrolment_forms = null;
            return view('preenrolment.index')->withEnrolment_forms($enrolment_forms)->withLanguages($languages)->withOrg($org)->withTerms($terms);
        }

        $enrolment_forms = new Preenrolment;
        // $currentQueries = \Request::query();
        $queries = [];

        $columns = [
            'L', 'DEPT', 'Term',
        ];

        
        foreach ($columns as $column) {
            if (\Request::has($column)) {
                $enrolment_forms = $enrolment_forms->where($column, \Request::input($column) );
                $queries[$column] = \Request::input($column);
            }

        } 

            if (\Request::has('sort')) {
                $enrolment_forms = $enrolment_forms->orderBy('created_at', \Request::input('sort') );
                $queries['sort'] = \Request::input('sort');
            }

        // $allQueries = array_merge($queries, $currentQueries);
        $enrolment_forms = $enrolment_forms->paginate(10)->appends($queries);
        return view('preenrolment.index')->withEnrolment_forms($enrolment_forms)->withLanguages($languages)->withOrg($org)->withTerms($terms);
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
