<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class sendConvocation extends Mailable
{
    use Queueable, SerializesModels;

    public $course_name_en; 
    public $course_name_fr; 
    public $schedule; 
    public $staff; 
    public $room; 
    public $teacher;
    public $term_en;
    public $term_fr;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($course_name_en, $course_name_fr, $schedule, $staff, $room, $teacher, $term_en, $term_fr)
    {
        $this->course_name_en = $course_name_en;
        $this->course_name_fr = $course_name_fr;
        $this->schedule = $schedule;
        $this->staff = $staff;
        $this->room = $room;
        $this->teacher = $teacher;
        $this->term_en = $term_en;
        $this->term_fr = $term_fr;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.emailConvocation')
                ->subject($this->course_name_en.': Language Training Convocation - '.$this->course_name_fr.' : Convocation aux cours de langue')
                ->from('clm_language@unog.ch', 'CLM Language')
                ->replyTo('clm_language@un.org')
                ->priority(1);
    }
}
