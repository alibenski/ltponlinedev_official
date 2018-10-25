<?php

namespace App\Console\Commands;

use App\Term;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ApprovalReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ApprovalReminder:approvalreminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to remind manager and HR partner for their approval';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(\App\Http\Controllers\PreenrolmentController $email, \App\Http\Controllers\PlacementFormController $placement_email)
    {
        parent::__construct();

        $this->email = $email;
        $this->placement_email = $placement_email;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {       
        // check if today is between enrolment date begin and approval limit date
        $now_date = Carbon::now();
        $now_year = Carbon::now()->year; 
        // get the correct enrolment term object
        $enrolment_term = Term::whereYear('Enrol_Date_Begin', $now_year)
                        ->orderBy('Term_Code', 'desc')
                        ->where('Enrol_Date_Begin', '<=', $now_date)
                        ->where('Approval_Date_Limit_HR', '>=', $now_date)
                        ->get()->min();
            if ($enrolment_term) {
                Log::info("ApprovalReminder.php excuted in Enrolment Term : ".$enrolment_term);

                // execute method function to send reminder emails found in the controller from construct()
                $this->email->sendReminderEmails(); 
                $this->placement_email->sendReminderEmailsPlacement(); 

                // notify admin by email
                Mail::raw("ApprovalReminder.php excuted in Enrolment Term : ".$enrolment_term, function($message) {
                $message->from('clm_language@unog.ch', 'CLM Language Web Admin');
                $message->to('allyson.frias@un.org')->subject("ApprovalReminder.php excuted in Enrolment Term : ".$enrolment_term);
                });
            } else {
                Log::info("Enrolment Term ".$enrolment_term." is false in ApprovalReminder.php");

                // notify admin by email
                Mail::raw("Enrolment Term ".$enrolment_term." is false in ApprovalReminder.php", function($message) {
                $message->from('clm_language@unog.ch', 'CLM Language Web Admin');
                $message->to('allyson.frias@un.org')->subject("Enrolment Term ".$enrolment_term." is false in ApprovalReminder.php");
                });
            }
    }
}
