<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class SendDefaultWaitlistEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $term;
    public $firstDayMonth;
    public $lastDayMonth;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($term, $firstDayMonth, $lastDayMonth)
    {
        $this->term = $term;
        $this->firstDayMonth = $firstDayMonth; 
        $this->lastDayMonth = $lastDayMonth;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('texts.view-default-email-waitlist-text')
                    ->from('clm_language@unog.ch', 'CLM Language')
                    ->priority(1)
                    ->subject('Waiting List Notification '. $this->term->Comments.' '. date('Y', strtotime($this->term->Term_Begin)) );
    }
}
