<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailtoStudentHR extends Mailable
{
    use Queueable, SerializesModels;

    public $input_course;
    public $staff_name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($input_course, $staff_name)
    {
        $this->input_course = $input_course;
        $this->staff_name = $staff_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.notifystudenthr')
                    ->from('clm_language@un.org')
                    ->priority(1)
                    ->subject('Notification: CLM Learning Partner Decision Made for CLM Language Course Enrolment '. $this->input_course->courses->Description);
    }
}
