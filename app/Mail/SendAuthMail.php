<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAuthMail extends Mailable
{
    use Queueable, SerializesModels;

    public $sddextr_email_address;
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
        return $this->view('emails.sendAuthMail')
            ->from('do_not_reply_ltp_online@unog.ch', 'CLM Language DO NOT REPLY')
            ->bcc('clm_language@un.org')
            ->priority(1)
            ->subject("Welcome to the UNOG-CLM LTP Online Enrolment Platform - Bienvenue sur la plateforme d'inscription en ligne de ONUG-CFM");
    }
}
