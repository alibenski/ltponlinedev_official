<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailReportByOrg extends Mailable
{
    use Queueable, SerializesModels;

    public $param;
    public $org;
    public $term;
    public $year;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($param, $org, $term, $year)
    {
        $this->param = $param;
        $this->org = $org;
        if ($param == 'year') {
            $this->year = $year;
            $this->term = 0;
        }
        if ($param == 'Term') {
            $this->term = $term;
            $this->year = 0;
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.emailReportByOrg')
            ->from('clm_language@unog.ch', 'CLM Language')
            ->bcc('clm_language@un.org')
            ->priority(1)
            ->subject('CLM Report for: ' . $this->org . ' Students');
    }
}
