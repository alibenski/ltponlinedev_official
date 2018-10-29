<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailtoStudentSelfpayPlacement extends Mailable
{
    use Queueable, SerializesModels;

    public $request;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request; 
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.notifyStudentSelfpayPlacement')
                    ->from('clm_language@unog.ch', 'CLM Language')
                    ->bcc('clm_language@unog.ch')
                    ->priority(1)
                    ->subject('Notification: CLM Language Secretariat Decision Made on Your Placement Form Request to Language: '.$this->request->Lstring);
    }
}
