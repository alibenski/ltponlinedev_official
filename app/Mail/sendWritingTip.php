<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class sendWritingTip extends Mailable
{
    use Queueable, SerializesModels;
    public $writingTip;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($writingTip)
    {
        $this->writingTip = $writingTip;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.writingTip', compact('writingTip'))
                    ->from('clm_language@unog.ch', 'CLM Language')
                    ->priority(1)
                    ->subject('Writing Tip - '.$this->writingTip->subject);
    }
}
