<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailPlacementHRApprovaltoStudent extends Mailable
{
    use Queueable, SerializesModels;

    public $formItems;
    public $input_course;
    public $staff_name;
    public $request;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($formItems, $input_course, $staff_name, $request)
    {
        $this->formItems = $formItems;
        $this->input_course = $input_course;
        $this->staff_name = $staff_name;
        $this->request = $request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.notifyPlacementHRApprovaltoStudent')
                    ->from('clm_language@unog.ch', 'CLM Language')
                    ->bcc('clm_language@un.org')
                    ->priority(1)
                    ->subject('Notification: CLM Learning Partner Decision Made on CLM Language Placement Test - '.$this->input_course->languages->name.' for '.$this->staff_name);
    }
}
