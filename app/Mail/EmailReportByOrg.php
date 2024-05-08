<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailReportByOrg extends Mailable
{
    use Queueable, SerializesModels;

    public $term;
    public $org;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($term, $org)
    {
        $this->term = $term;
        $this->org = $org;
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
