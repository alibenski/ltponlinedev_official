<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailtoApprover extends Mailable
{
    use Queueable, SerializesModels;

    public $input;
    public $staff;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($input, $staff)
    {
        $this->input = $input;
        $this->staff = $staff;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.approval')
                    ->from('clm_language@un.org')
                    ->subject('Approval Needed: Language Course Enrolment '. $this->input->courses->Description  .' for '.$this->staff->name);
    }
}
