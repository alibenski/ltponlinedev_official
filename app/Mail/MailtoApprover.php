<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Support\Facades\Crypt;

class MailtoApprover extends Mailable
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
                    ->from('clm_language@un.org')
                    ->priority(1)
                    ->subject('Manager/Supervisor Approval Needed: Language Course Enrolment '. $this->input_course->courses->Description  .' for '.$this->staff->name);
    }
}
