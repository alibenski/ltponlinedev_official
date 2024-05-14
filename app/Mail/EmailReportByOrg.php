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
    public $term_name_string;
    public $term_year_string;
    public $cancel_date_limit_string;
    public $deadline;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($param, $org, $term, $year, $term_name_string, $term_year_string, $cancel_date_limit_string, $deadline)
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

        $this->term_name_string = $term_name_string;
        $this->term_year_string = $term_year_string;
        $this->cancel_date_limit_string = $cancel_date_limit_string;
        $this->deadline = $deadline;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.emailReportByOrgByTerm')
            ->from('clm_language@unog.ch', 'CLM Language')
            ->cc('clm_language@un.org')
            ->priority(1)
            ->subject('Final check for billing - Language courses ' . $this->term_name_string . ' term ' . $this->term_year_string . ' - Deadline ' . $this->deadline . ' - ' . $this->org);
    }
}
