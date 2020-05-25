<?php

namespace App\Console\Commands;

use App\Events\ProcessCommandException;
use App\Events\ProcessCommandSuccess;
use App\Models\MailRecipient;
use App\Models\ProcessCommand;
use Illuminate\Console\Command;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BaseCommand extends Command implements ShouldQueue
{
    protected $sourceModel;
    protected $targetModel;
    protected $sourceField;
    protected $targetField;
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
            if ($this->migrateFromId < $this->getLastIdTarget()) {
                $this->bar = $this->output->createProgressBar($this->getCountSource() - ($this->getCountTarget() + $this->getCountBelowMigrateFromID()));
            } else {
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

    public function shutdown()
    {
        abort(403, 'Keyboard interrupt detected during execution');
    }

    protected function transferSourceToTarget()
    {
        try {
            pcntl_async_signals(true);
            pcntl_signal(SIGINT, [$this, 'shutdown']); // Call $this->shutdown() on SIGINT
            pcntl_signal(SIGTERM, [$this, 'shutdown']); // Call $this->shutdown() on SIGTERM
            $this->executionStartTime = date("Y-m-d H:i:s");
            $this->createBar();
            $this->sourceModel::where('id', '>', $this->getLastIdTarget())
                ->orderBy('id', 'ASC')
                ->chunk($this->defaultChunkSize, function ($models) {
                    DB::beginTransaction();
                    foreach ($models as $model) {
                        if (count($this->sourceField) == count($this->targetField)) {
                            $insertArray = [];
                            for ($i = 0; $i < count($this->sourceField); $i++) {
                                $insertArray = Arr::add($insertArray, $this->targetField[$i], $model->{$this->sourceField[$i]});
                            }
                        } else {
                            $this->error('Count of source and target fields not matching.');
                            exit();
                        }
                        $this->targetModel::insert($insertArray);
                        $this->advanceBar();
                    }
                    DB::commit();
                });
            $this->finishBar();
            $this->lastProcessedId = $this->getLastIdTarget();
            Log::info('Data in (' . $this->targetModel->table . ') table saved upto', [$this->getLastIdTarget()]);
            $this->executionEndTime = date("Y-m-d H:i:s");
            $this->commandStatus = true;
            $this->exception = '';
            $this->getExecutionStatus();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            $this->lastProcessedId = $this->getLastIdTarget();
            $this->commandStatus = false;
            $this->executionEndTime = date("Y-m-d H:i:s");
            $this->exception = '(' . $exception->getMessage() . ') at line ' . $exception->getLine() . ' in ' . $exception->getFile();
            $this->getExecutionStatus();
            return false;
        }
    }
}
