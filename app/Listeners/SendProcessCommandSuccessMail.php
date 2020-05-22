<?php

namespace App\Listeners;

use App\Events\ProcessCommandSuccess;
use App\Jobs\SendEmail;
use App\Mail\ProcessCommandExceptionMail;
use App\Mail\ProcessCommandSuccessfulMail;
use App\Mail\ProcessCommandSuccessMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendProcessCommandSuccessMail implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  ProcessCommandSuccess  $event
     * @return void
     */
    public function handle(ProcessCommandSuccess $event)
    {
        foreach ($event->commandStatusRecipients as $recipient) {
            Mail::to($recipient)->send(new ProcessCommandSuccessfulMail($event->processCommand));
        }
    }
}

