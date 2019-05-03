<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Text;

class sendBroadcastEnrolmentIsOpen extends Mailable
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
        $text = Text::find(1);
        
        return $this->view('emails.sendBroadcastEnrolmentIsOpen', compact('text'))
                    ->from('clm_language@unog.ch', 'CLM Language')
                    ->priority(1)
                    ->subject("Language Training Programme: Enrolment Period Open / Programme de formation linguistique : Période d'inscription Ouverte");
    }
}
