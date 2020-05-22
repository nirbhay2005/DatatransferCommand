<?php

namespace App\Events;

use App\Models\MailRecipient;
use App\Models\ProcessCommand;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProcessCommandSuccess
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $processCommand;
    public $commandStatusRecipients;

    /**
     * Create a new event instance.
     *
     * @param ProcessCommand $processCommand
     * @param $commandStatusRecipients
     */
    public function __construct(ProcessCommand $processCommand, $commandStatusRecipients)
    {
        $this->processCommand = $processCommand;
        $this->commandStatusRecipients = $commandStatusRecipients;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
