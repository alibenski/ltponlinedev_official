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
    public function __construct(\App\Http\Controllers\WaitlistController $email)
    {
        parent::__construct();

        $this->email = $email;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {       
        // execute method function to send reminder emails found in the controller from construct()
        // $this->email->testQuery(); 
    }
}
