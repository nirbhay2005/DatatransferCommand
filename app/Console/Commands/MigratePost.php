<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\UserPost;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MigratePost extends BaseCommand
{
   protected $migrateFromId = 2001;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:post';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->sourceModel = new UserPost();
        $this->targetModel = new Post();
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
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
                        $this->targetModel::insert(['id' => $model->id, 'post_desc' => $model->post, 'user_id' => $model->user_id]);
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
            $this->getExecutionTime();
            return true;

        } catch (\Exception $exception) {
            DB::rollBack();
            $this->lastProcessedId = $this->getLastIdTarget();
            $this->commandStatus = false;
            $this->executionEndTime = date("Y-m-d H:i:s");
            $this->exception = '(' . $exception->getMessage() . ') at line ' . $exception->getLine() . ' in ' . $exception->getFile() . $exception->getTraceAsString();
            $this->getExecutionTime();
            return false;
        }

    }
}

