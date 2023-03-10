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
    public $name;
    public $course;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($term, $firstDayMonth, $lastDayMonth, $name, $course)
    {
        $this->term = $term;
        $this->firstDayMonth = $firstDayMonth;
        $this->lastDayMonth = $lastDayMonth;
        $this->name = $name;
        $this->course = $course;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.defaultEmailWaitlist')
            ->from('clm_language@unog.ch', 'CLM Language')
            ->priority(1)
            ->subject('Waiting List Notification ' . $this->term->Comments . ' ' . date('Y', strtotime($this->term->Term_Begin)));
    }
}
