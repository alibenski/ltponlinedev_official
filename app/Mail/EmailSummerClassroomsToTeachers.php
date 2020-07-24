<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailSummerClassroomsToTeachers extends Mailable
{
    use Queueable, SerializesModels;

    public $languages;
    public $queryTeachers;
    public $selectedTerm;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($languages, $queryTeachers, $selectedTerm)
    {
        $this->languages = $languages;
        $this->queryTeachers = $queryTeachers;
        $this->selectedTerm = $selectedTerm;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.emailSummerClassroomsToTeachers')
                    ->from('clm_language@unog.ch', 'CLM Language')
                    ->cc(['clm_language@un.org', 'virginie.ferre@un.org'])
                    ->priority(1)
                    ->subject($this->selectedTerm->Comments.' Courses Information');
    }
}
