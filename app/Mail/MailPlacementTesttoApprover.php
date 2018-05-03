<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Support\Facades\Crypt;

class MailPlacementTesttoApprover extends Mailable
{
    use Queueable, SerializesModels;

    public $input_course;
    public $staff;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($input_course, $staff)
    {
        $this->input_course = $input_course;
        $this->staff = $staff;
         
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.placementApproval')
                    ->from('clm_language@un.org', 'CLM Language')
                    ->bcc('clm_language@un.org')
                    ->priority(1)
                    ->subject('Manager/Supervisor Approval Needed: Language Placement Test - '.$this->input_course->languages->name .' for '.$this->staff->sddextr->FIRSTNAME.' '.$this->staff->sddextr->LASTNAME);
    }
}