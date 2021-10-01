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
    public $subject;
    public $type;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($staff_name, $display_language_fr, $display_language_en, $schedule, $subject, $type)
    {
        $this->staff_name = $staff_name;
        $this->display_language_fr = $display_language_fr;
        $this->display_language_en = $display_language_en;
        $this->schedule = $schedule;
        $this->subject = $subject;
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.cancelConvocation')
                ->subject($this->subject)
                ->from('do_not_reply_ltp_online@unog.ch', 'CLM Language DO NOT REPLY')
                ->bcc('clm_language@unog.ch')
                ->replyTo('do_not_reply_ltp_online@unog.ch')
                ->priority(1);
    }
}
