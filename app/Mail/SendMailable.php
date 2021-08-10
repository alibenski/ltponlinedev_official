<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $input_course;
    public $staff;
    public $input_schedules;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($input_course, $input_schedules, $staff)
    {
        $this->input_course = $input_course;
        $this->input_schedules = $input_schedules;
        $this->staff = $staff;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.approval')
                    ->subject('Reminder - Supervisor Approval Needed for: '.$this->staff->name.' on Language Course Enrolment '.$this->input_course->courses->Description)
                    ->from('do_not_reply_ltp_online@unog.ch', 'CLM Language')
                    ->bcc('clm_language@un.org')
                    ->priority(1);
    }
}
