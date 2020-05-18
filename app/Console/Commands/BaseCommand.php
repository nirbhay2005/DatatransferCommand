<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BaseCommand extends Command
{

    protected $sourceModel;
    protected $sourceField;
    protected $targetModel;
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
    protected $lastProcessedCount;
    protected $query;
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
    public $model;

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
     * logs the command status data into command_log table.
     */
    protected function getExecutionTime()
    {
        $this->executionTime = strtotime($this->executionEndTime) - strtotime($this->executionStartTime);
        DB::connection('mysql')->table('command_log')
            ->insert(['command_name' => $this->signature,
                'start_time' => $this->executionStartTime,
                'end_time' => $this->executionEndTime,
                'exec_time' => $this->executionTime,
                'status' => $this->commandStatus,
                'last_processed_id' => $this->lastProcessedId,
                'exception' => $this->exception]);
    }

    /**
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

    /**
     * create and start a progress bar.
     */
    protected function createBar()
    {
        if($this->getCountSource() - $this->getCountBelowMigrateFromID() - $this->getCountTarget() != 0){
            $this->bar = $this->output->createProgressBar($this->getCountSource() - $this->getCountBelowMigrateFromID() - $this->getCountTarget());
            $this->bar->setFormat('%current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% -- %message%');
            $this->bar->setMessage('Data is being transferred to '.$this->targetModel->table.' table' );
        }else{
            $this->bar = $this->output->createProgressBar();
            $this->bar->setFormat('[%bar%] -- %message%');
            $this->bar->setMessage('No data to transfer');
        }
        $this->bar->start();
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
        throw new \Exception('Keyboard Interrupt detected');
    }

    protected function createInsertArray($model)
    {
        for($i=0;$i<count($this->sourceField);$i++) {
           $this->sourceField[$i] = ($model->{$this->sourceField[$i]});
        }

        $arr = array_combine($this->targetField,$this->sourceField);
        return $arr;
    }
}
