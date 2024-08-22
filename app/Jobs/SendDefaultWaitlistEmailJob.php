<?php

namespace App\Jobs;

use App\Mail\SendDefaultWaitlistEmail;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendDefaultWaitlistEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $emailchunkedStudent;
    private $term;
    private $firstDayMonth;
    private $lastDayMonth;

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
    public function __construct($emailchunkedStudent, $term, $firstDayMonth, $lastDayMonth)
    {
        $this->emailchunkedStudent = $emailchunkedStudent;
        $this->term = $term;
        $this->firstDayMonth = $firstDayMonth;
        $this->lastDayMonth = $lastDayMonth;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $emailError = [];
        foreach ($this->emailchunkedStudent as $value) {
            try {
                Mail::to($value->users->email)->send(new SendDefaultWaitlistEmail($this->term, $this->firstDayMonth, $this->lastDayMonth, $value->users->name, $value->courses->Description));
            } catch (\Exception $e) {
                $emailError[] = $value->users->email;
            }
            $value->users->email = null;
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
        Mail::raw("SendDefaultWaitlistEmail Delivery Job Failure. Something is wrong with the code in App\Jobs\SendDefaultWaitlistEmailJob. There was an error executing SendDefaultWaitlistEmail.php. Stop cron/queue:work, fix the code, do queue:restart, then do queue:work or restart CRON", function ($message) {
            $message->from('do_not_reply_ltp_online@unog.ch', 'CLM Language DO NOT REPLY');
            $message->to('allyson.frias@un.org')->subject('Alert: Mail Delivery Failure on SendDefaultWaitlistEmail Function');
            // $message->text('There was an error executing SendDefaultWaitlistEmail.php');
        });
    }
}
