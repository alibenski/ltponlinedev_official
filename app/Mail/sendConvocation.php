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
    public $term_en;
    public $term_fr;
    public $schedule;
    public $term_season_en;
    public $term_season_fr;
    public $term_year;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($staff_name,$course_name_en, $course_name_fr, $classrooms, $teacher, $term_en, $term_fr,$schedule, $term_season_en, $term_season_fr, $term_year)
    {
        $this->staff_name = $staff_name;
        $this->course_name_en = $course_name_en;
        $this->course_name_fr = $course_name_fr;
        $this->classrooms = $classrooms;
        $this->teacher = $teacher;
        $this->term_en = $term_en;
        $this->term_fr = $term_fr;
        $this->schedule = $schedule;
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
        return $this->view('emails.emailConvocation')
                ->subject('Your Language Training Course : '.$this->term_season_en.' '.$this->term_year.' - '.$this->course_name_en.' / Votre cours de langue : '.$this->term_season_fr.' '.$this->term_year.' - '.$this->course_name_fr )
                ->from('clm_language@unog.ch', 'CLM Language')
                ->replyTo('clm_language@un.org')
                ->priority(1);
    }
}
