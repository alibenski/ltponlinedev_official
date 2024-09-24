<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailtoStudentSelfpay extends Mailable
{
    use Queueable, SerializesModels;

    public $request;
    public $term_season_en;
    public $term_year;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request, $term_season_en, $term_year)
    {
        $this->request = $request;
        $this->term_season_en = $term_season_en;
        $this->term_year = $term_year;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.notifyStudentSelfpay')
            ->from('clm_language@unog.ch', 'CLM Language')
            ->bcc('clm_language@un.org')
            ->priority(1)
            ->subject('Notification: Your enrolment to ' . $this->request->course_show . ' for ' . $this->term_season_en . ' ' . $this->term_year . ' has been received and is under consideration');
    }
}
