<?php

namespace App\Listeners;

use App\Events\ProcessCommandException;
use App\Notifications\ProcessCommandExceptionNotification;
use App\Notifications\ProcessCommandSuccessfulNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendProcessCommandExceptionNotification
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
        Notification::send($event->commandStatusRecipients, new ProcessCommandExceptionNotification($event->processCommand));
    }
}
