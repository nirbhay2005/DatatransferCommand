<?php

namespace App\Mail;

use App\Models\ProcessCommand;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class ProcessCommandSuccessfulMail extends Mailable
{
    use Queueable, SerializesModels;
    public $lastId;
    public $commandName;

    /**
     * Create a new message instance.
     *
     * @param ProcessCommand $processCommand
     */
    public function __construct(ProcessCommand $processCommand)
    {
        $this->lastId = $processCommand->last_processed_id;
        $this->commandName = $processCommand->command_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //App::setLocale('fr');
        return $this->markdown('emails.successMail')
        ->subject('Data transfer successful for '. $this->commandName.'.');
        //->subject(__('Data transfer successful for', ['command' => $this->commandName]));
    }
}
