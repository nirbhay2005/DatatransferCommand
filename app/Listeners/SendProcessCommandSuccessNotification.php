<?php

namespace App\Listeners;

use App\Events\ProcessCommandSuccess;
use App\Notifications\ProcessCommandSuccessfulNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendProcessCommandSuccessNotification
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
     * @param  ProcessCommandSuccess  $event
     * @return void
     */
    public function handle(ProcessCommandSuccess $event)
    {
        Notification::send($event->commandStatusRecipients, new ProcessCommandSuccessfulNotification($event->processCommand));
    }
}
