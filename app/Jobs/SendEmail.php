<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $commandStatusRecipients;

    /**
     * Create a new job instance.
     *
     * @param Mailable $mailable
     * @param $commandStatusRecipients
     */
    public function __construct(Mailable $mailable, $commandStatusRecipients)
    {
        $this->email =  $mailable;
        $this->commandStatusRecipients = $commandStatusRecipients;
    }

    /**
     * Execute the job.
     *
     *
     * @return void
     */
    public function handle()
    {
            Mail::to($this->commandStatusRecipients)->send($this->email);
    }
}
