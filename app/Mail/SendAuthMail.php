<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAuthMail extends Mailable
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
        return $this->view('emails.sendAuthMail')
                    ->from('clm_language@unog.ch', 'CLM Language')
                    ->bcc('clm_language@unog.ch')
                    ->priority(1)
                    ->subject("Information concernant le nouveau système d'inscription en ligne - Information concerning the new online registration system");
    }
}
