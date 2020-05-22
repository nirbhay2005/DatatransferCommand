<?php

namespace App\Mail;

use App\Console\Commands\BaseCommand;
use App\Models\ProcessCommand;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Console\Commands\MigrateUserPost;
use Illuminate\Support\Facades\App;

class ProcessCommandExceptionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $exception;
    public $lastId;
    public $commandName;

    /**
     * Create a new message instance.
     * @param ProcessCommand $processCommand
     */
    public function __construct(ProcessCommand $processCommand)
    {
        $this->lastId = $processCommand->last_processed_id;;
        $this->exception = $processCommand->exception;
        $this->commandName = $processCommand->command_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //App::setLocale('en');
        return $this->markdown('emails.datatransferexception')
            ->subject('Exception during data transfer ('. $this->commandName.')');
         //->subject(__('Exception occurred during command', ['command' => $this->commandName]));
    }
}
