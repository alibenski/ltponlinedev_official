<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Support\Facades\Crypt;

class MailtoStudent extends Mailable
{
    use Queueable, SerializesModels;

    public $formItems;
    public $input_course;
    public $staff_name;
    public $mgr_comment;
    public $request;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($formItems, $input_course, $staff_name, $mgr_comment, $request)
    {
        $this->formItems = $formItems;
        $this->input_course = $input_course;
        $this->staff_name = $staff_name;
        $this->mgr_comment = $mgr_comment;
        $this->request = $request; 
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.notifystudent')
                    ->from('clm_language@un.org', 'CLM Language')
                    ->bcc('clm_language@un.org')
                    ->priority(1)
                    ->subject('Notification: Manager/Supervisor Decision Made for CLM Language Course Enrolment '.$this->input_course->courses->Description);
    }
}
