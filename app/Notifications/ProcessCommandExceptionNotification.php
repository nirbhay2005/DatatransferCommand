<?php

namespace App\Notifications;

use App\Models\ProcessCommand;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Notification;

class ProcessCommandExceptionNotification extends Notification implements ShouldQueue
{
    use Queueable;
    protected $exception;
    protected $lastId;
    protected $commandName;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ProcessCommand $processCommand)
    {
        $this->lastId = $processCommand->last_processed_id;;
        $this->exception = $processCommand->exception;
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
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $command = $this->commandName;
        $exception = $this->exception;
        $id = $this->lastId;
        return (new MailMessage)
                    ->markdown('mail.command.exception', ['command' => $command, 'id' => $id, 'exception' => $exception]);
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
        $exception = $this->exception;
        return [
            'command' => $command,
            'last_id' => $id,
            'exception' => $exception,
        ];
    }

    public function toNexmo($notifiable)
    {
        $command = $this->commandName;
        $id = $this->lastId;
        return (new NexmoMessage())
            ->content('Exception occurred in '.$command.' command. Data transferred till id: '.$id);
    }
}
