<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class sendConvocation extends Mailable
{
    use Queueable, SerializesModels;

    public $staff_name; 
    public $course_name_en; 
    public $course_name_fr;  
    public $classrooms;
    public $teacher;
    public $teacher_email;
    public $term_en;
    public $term_fr;
    public $schedule;
    public $term_season_en;
    public $term_season_fr;
    public $term_year;
    public $cancel_date_limit_string;
    public $cancel_date_limit_string_fr;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($staff_name,$course_name_en, $course_name_fr, $classrooms, $teacher, $teacher_email, $term_en, $term_fr,$schedule, $term_season_en, $term_season_fr, $term_year, $cancel_date_limit_string, $cancel_date_limit_string_fr)
    {
        $this->staff_name = $staff_name;
        $this->course_name_en = $course_name_en;
        $this->course_name_fr = $course_name_fr;
        $this->classrooms = $classrooms;
        $this->teacher = $teacher;
        $this->teacher_email = $teacher_email;
        $this->term_en = $term_en;
        $this->term_fr = $term_fr;
        $this->schedule = $schedule;
        $this->term_season_en = $term_season_en;
        $this->term_season_fr = $term_season_fr;
        $this->term_year = $term_year;
        $this->cancel_date_limit_string = $cancel_date_limit_string;
        $this->cancel_date_limit_string_fr = $cancel_date_limit_string_fr;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.emailConvocation')
                ->subject('Your Language Training Course : '.$this->term_season_en.' '.$this->term_year.' - '.$this->course_name_en.' / Votre cours de langue : '.$this->term_season_fr.' '.$this->term_year.' - '.$this->course_name_fr )
                ->from('do_not_reply_ltp_online@unog.ch', 'CLM Language')
                ->replyTo('do_not_reply_ltp_online@unog.ch')
                ->priority(1);
    }
}
