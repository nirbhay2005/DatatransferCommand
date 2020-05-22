<?php

namespace App\Console\Commands;

use App\Events\ProcessCommandException;
use App\Events\ProcessCommandSuccess;
use App\Models\MailRecipient;
use App\Models\ProcessCommand;
use Exception;
use http\Exception\RuntimeException;
use Illuminate\Console\Command;
use Illuminate\Contracts\Queue\ShouldQueue;

class BaseCommand extends Command implements ShouldQueue
{
    protected $sourceModel;
    protected $targetModel;
    protected $defaultChunkSize = 200;
    protected $migrateFromId = 0;
    protected $executionStartTime;
    protected $executionEndTime;
    protected $executionTime;
    protected $lastProcessedId;
    protected $exception;
    protected $commandStatus;
    protected $bar;
    protected $commandStatusRecipients;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @return int the last id of the target table.
     *
     */
    protected function getLastIdTarget()
    {
        $id = $this->targetModel::select(['id'])->orderBy('id', 'desc')->first();
        if ($id != null) {
            if ($this->migrateFromId > $id->id) {
                return $this->migrateFromId - 1;
            } else {
                return $id->id;
            }
        } elseif ($this->migrateFromId > 0) {
            return $this->migrateFromId - 1;
        } else {
            return 0;
        }
    }

    /**
     *
     * @return int the last id of the source table.
     */
    protected function getLastIdSource()
    {
        $id = $this->sourceModel::select(['id'])->orderBy('id', 'desc')->first();
        if ($id != null) {
            return $id->id;
        } else {
            return 0;
        }
    }

    protected function getCountSource()
    {
        return $this->sourceModel::all()->count();
    }

    protected function getCountTarget()
    {
        return $this->targetModel::all()->count();
    }

    protected function getCountBelowMigrateFromID()
    {
        $count = $this->sourceModel::select(['id'])->where('id', '<', $this->migrateFromId)->count();
        return $count;
    }

    /**
     * logs the command status data into process_commands table.
     */
    protected function getExecutionStatus()
    {
        $this->executionTime = strtotime($this->executionEndTime) - strtotime($this->executionStartTime);
        $commandStatus = ProcessCommand::insert(['command_name' => $this->signature,
            'start_time' => $this->executionStartTime,
            'end_time' => $this->executionEndTime,
            'exec_time' => $this->executionTime,
            'status' => $this->commandStatus,
            'last_processed_id' => $this->lastProcessedId,
            'exception' => $this->exception]);
        $this->commandStatusRecipients = MailRecipient::all();
        if ($commandStatus) {
            $processCommand = ProcessCommand::select()->orderBy('id', 'desc')->first();
            if ($processCommand->status == 1) {
                event(new ProcessCommandSuccess($processCommand, $this->commandStatusRecipients));//For sending mail through events
                //SendEmail::dispatch(new ProcessCommandSuccessfulMail($processCommand), $this->commandStatusRecipients);//For sending mail through jobs
            } else {
                event(new ProcessCommandException($processCommand, $this->commandStatusRecipients));
                //SendEmail::dispatch(new ProcessCommandExceptionMail($processCommand), $this->commandStatusRecipients);
            }
        }
    }

    /**
     * create and start a progress bar.
     */
    protected function createBar()
    {
        if ($this->getCountSource() - $this->getCountBelowMigrateFromID() - $this->getCountTarget() != 0) {
            if($this->migrateFromId < $this->getLastIdTarget()){
                $this->bar = $this->output->createProgressBar($this->getCountSource() - $this->getCountTarget());
            }else{
                $this->bar = $this->output->createProgressBar($this->getCountSource() - $this->getCountBelowMigrateFromID());
            }
            $this->bar->setFormat('%current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% -- %message%');
            $this->bar->setMessage('Data is being transferred to ' . $this->targetModel->table . ' table');
            $this->bar->start();
        } else {
            $this->bar = $this->output->createProgressBar();
            $this->bar->setFormat('-- %message%');
            $this->bar->setMessage('No data to transfer');
        }
    }

    /**
     * Advance the progress bar.
     * @param int $step
     * @return
     */
    protected function advanceBar($step = 1)
    {
        return $this->bar->advance($step);
    }

    /**
     * Finish the progress bar.
     */
    protected function finishBar()
    {
        return $this->bar->finish();
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    public function shutdown()
    {
        throw new Exception(RuntimeException::class);
    }
}
