<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class updateEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($student)
    {
        $this->student = $student;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.notifyProfileUpdate')
                    ->from('clm_language@unog.ch', 'CLM Language')
                    ->bcc('clm_language@unog.ch')
                    ->priority(1)
                    ->subject('Confirmation Needed: CLM Online Profile Update for '.$this->student['name']);
    }
}
