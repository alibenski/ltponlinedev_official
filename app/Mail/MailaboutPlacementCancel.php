<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Support\Facades\Crypt;

class MailaboutPlacementCancel extends Mailable
{
    use Queueable, SerializesModels;

    public $forms;
    public $display_language;
    public $staff_member_name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($forms, $display_language, $staff_member_name)
    {
        $this->forms = $forms;
        $this->display_language = $display_language;
        $this->staff_member_name = $staff_member_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.cancellationPlacement')
                    ->from('clm_language@unog.ch', 'CLM Language')
                    ->bcc('clm_language@unog.ch')
                    ->priority(1)
                    ->subject('Cancellation: '.$this->staff_member_name.' Cancelled Placement Test for '.$this->display_language->languages->name);
    }
}
