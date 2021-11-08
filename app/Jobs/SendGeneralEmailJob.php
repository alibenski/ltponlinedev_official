<?php

namespace App\Jobs;

use App\Mail\sendGeneralEmail;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendGeneralEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $unique_email_address; 

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    public $timeout = 60;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($unique_email_address)
    {
        $this->unique_email_address = $unique_email_address; 
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $emailError = [];
        foreach ($this->unique_email_address as $sddextr_email_address) {
            try {
                Mail::to($sddextr_email_address)->send(new sendGeneralEmail($sddextr_email_address));
            }  catch ( \Exception $e) {
                $emailError[] = $sddextr_email_address;
            }
            $sddextr_email_address = null;
        }
        return $emailError;
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed()
    {
        Mail::raw("General Email Delivery Job Failure. Something is wrong with the code in App\Jobs\SendGeneralEmailJob. Stop cron/queue:work, fix the code, do queue:restart, then do queue:work or restart CRON", function($message) {
            $message->from('do_not_reply_ltp_online@unog.ch', 'CLM Language DO NOT REPLY');
            $message->to('allyson.frias@un.org')->subject('Alert: Mail Delivery Failure on Send General Email Function');
            $message->text('There was an error executing sendGeneralEmail.php');
        });
    }
}
