<?php

namespace App\Jobs;

use App\Mail\sendWritingTip;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $drupalEmailRecords; 
    private $writingTip;

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
    public function __construct($drupalEmailRecords, $writingTip)
    {
        $this->drupalEmailRecords = $drupalEmailRecords; 
        $this->writingTip = $writingTip;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->drupalEmailRecords as $key => $emailAddress) {

            // Mail::to($emailAddress->data)
                // ->queue(new sendWritingTip($this->writingTip));
            Mail::to($emailAddress)
                ->send(new sendWritingTip($this->writingTip));
        }
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed()
    {
        Mail::raw("Mail Delivery Job Failure. Something is wrong with the code in App\Jobs\sendEmailJob. Stop cron/queue:work, fix the code, do queue:restart, then do queue:work", function($message) {
            $message->from('do_not_reply_ltp_online@unog.ch', 'CLM Language DO NOT REPLY');
            $message->to('allyson.frias@un.org')->subject('Alert: Mail Delivery Failure Writing Tips');
            // $message->text($exception);
        });
    }
}
