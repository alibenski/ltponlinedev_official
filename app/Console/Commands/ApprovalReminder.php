<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ApprovalReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ApprovalReminder:approvalreminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to remind manager and HR partner for their approval';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        

        Mail::raw("This is a test automated message - sends every hour", function($message){

           $message->from('clm_language@unog.ch', 'CLM Language');
           $message->to('allyson.frias@un.org')->subject('This is a test automated message');
        });
        
        $this->info('Reminder approval email has been sent successfully'); 
    }
}
