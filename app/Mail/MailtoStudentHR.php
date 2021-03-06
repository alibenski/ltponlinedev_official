<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailtoStudentHR extends Mailable
{
    use Queueable, SerializesModels;

    public $formItems;
    public $input_course;
    public $staff_name;
    public $request;
    public $term_season_en; 
    public $term_year;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($formItems, $input_course, $staff_name, $request, $term_season_en, $term_year)
    {
        $this->formItems = $formItems;
        $this->input_course = $input_course;
        $this->staff_name = $staff_name;
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
        return $this->view('emails.notifystudenthr')
                    ->from('clm_language@unog.ch', 'CLM Language')
                    // ->bcc('clm_language@un.org')
                    ->priority(1)
                    ->subject('Notification: CLM Learning Partner Decision Made on CLM Language Course Enrolment '.$this->input_course->courses->Description.' of '.$this->staff_name.' ('.$this->term_season_en.' '.$this->term_year.')');
    }
}
