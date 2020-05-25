<?php

namespace App\Notifications;

use App\Models\ProcessCommand;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Notification;

class ProcessCommandSuccessfulNotification extends Notification implements ShouldQueue
{
    use Queueable;
    protected $lastId;
    protected $commandName;

    /**
     * Create a new notification instance.
     *
     * @param $processCommand
     */
    public function __construct(ProcessCommand $processCommand)
    {
        $this->lastId = $processCommand->last_processed_id;
        $this->commandName = $processCommand->command_name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [/*'mail', 'nexmo'*/ 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $command = $this->commandName;
        $id = $this->lastId;
        return (new MailMessage)
            //->greeting('Hello!')
            //->subject('Command Success'. $this->processCommand->command_name)
            //->line('Command Successful');
            ->markdown('mail.command.success', ['command' => $command, 'id' => $id]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $command = $this->commandName;
        $id = $this->lastId;
        return [
            'command' => $command,
            'last_id' => $id,
        ];
    }

    public function toNexmo($notifiable)
    {
        $command = $this->commandName;
        $id = $this->lastId;
        return (new NexmoMessage)
            ->content($command.' is successful. Data transferred till id: '.$id);
    }
}
