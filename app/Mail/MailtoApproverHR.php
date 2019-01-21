<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Support\Facades\Crypt;

class MailtoApproverHR extends Mailable
{
    use Queueable, SerializesModels;

    public $formItems; 
    public $input_course; 
    public $staff_name; 
    public $mgr_email;
    public $term_en; 
    public $term_fr;
    public $term_season_en; 
    public $term_season_fr;
    public $term_year;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($formItems, $input_course, $staff_name, $mgr_email,$term_en, $term_fr,$term_season_en, $term_season_fr,$term_year)
    {
        $this->formItems = $formItems; 
        $this->input_course = $input_course; 
        $this->staff_name = $staff_name; 
        $this->mgr_email = $mgr_email; 
        $this->term_en = $term_en; 
        $this->term_fr = $term_fr;
        $this->term_season_en = $term_season_en;
        $this->term_season_fr = $term_season_fr;
        $this->term_year = $term_year;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.approvalhr')
                    ->from('clm_language@unog.ch', 'CLM Language')
                    // ->bcc('clm_language@un.org')
                    ->priority(1)
                    ->subject('CLM Learning Partner Approval Needed for: '.$this->staff_name.' on Language Course Enrolment '.$this->input_course->courses->Description.' ('.$this->term_season_en.' '.$this->term_year.')');
    }
}
