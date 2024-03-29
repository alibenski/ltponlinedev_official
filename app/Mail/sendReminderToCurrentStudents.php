<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class sendReminderToCurrentStudents extends Mailable
{
    use Queueable, SerializesModels;

    public $sddextr_email_address ;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($sddextr_email_address)
    {
        $this->sddextr_email_address = $sddextr_email_address;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.sendBroadcastEnrolmentIsOpen')
                    ->from('do_not_reply_ltp_online@unog.ch', 'CLM Language DO NOT REPLY')
                    ->priority(1)
                    ->subject("Reminder - Language Training Programme: Enrolment Period Open / Rappel - Programme de formation linguistique : Période d'inscription Ouverte");
    }
}
