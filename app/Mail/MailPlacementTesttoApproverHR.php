<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailPlacementTesttoApproverHR extends Mailable
{
    use Queueable, SerializesModels;

    public $formItems; 
    public $input_course; 
    public $staff_name; 
    public $mgr_email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($formItems, $input_course, $staff_name, $mgr_email)
    {
        $this->formItems = $formItems; 
        $this->input_course = $input_course; 
        $this->staff_name = $staff_name; 
        $this->mgr_email = $mgr_email; 
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.placementApprovalHR')
                    ->from('clm_language@un.org', 'CLM Language')
                    ->bcc('clm_language@un.org')
                    ->priority(1)
                    ->subject('CLM Learning Partner Approval Needed: Language Placement Test - '.$this->input_course->languages->name  .' for '.$this->staff_name);
    }
}
