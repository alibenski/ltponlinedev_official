<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class cancelConvocation extends Mailable
{
    use Queueable, SerializesModels;

    public $staff_name; 
    public $display_language_fr; 
    public $display_language_en; 
    public $schedule;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($staff_name, $display_language_fr, $display_language_en, $schedule)
    {
        $this->staff_name = $staff_name;
        $this->display_language_fr = $display_language_fr;
        $this->display_language_en = $display_language_en;
        $this->schedule = $schedule;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.cancelConvocation')
                ->subject('Cancelled Language Course Enrolment' )
                ->from('clm_language@unog.ch', 'CLM Language')
                ->bcc('clm_language@unog.ch')
                ->replyTo('clm_language@un.org')
                ->priority(1);
    }
}
