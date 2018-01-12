<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Support\Facades\Crypt;

class MailaboutCancel extends Mailable
{
    use Queueable, SerializesModels;

    public $display_language;
    public $staff_member_name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($display_language, $staff_member_name)
    {
        $this->display_language = $display_language;
        $this->staff_member_name = $staff_member_name;
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.cancellation')
                    ->from('clm_language@un.org')
                    ->priority(1)
                    ->subject('Notification of Cancellation: Language Course Enrolment '. $this->display_language->courses->EDescription .' for '. $this->staff_member_name);
    }
}
