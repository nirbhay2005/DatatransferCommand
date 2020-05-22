<?php

namespace App\Listeners;

use App\Events\ProcessCommandException;
use App\Jobs\SendEmail;
use App\Mail\ProcessCommandExceptionMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendProcessCommandExceptionMail implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ProcessCommandException  $event
     * @return void
     */
    public function handle(ProcessCommandException $event)
    {
        foreach ($event->commandStatusRecipients as $recipient){
            Mail::to($recipient)->send(new ProcessCommandExceptionMail($event->processCommand));
        }
    }
}
