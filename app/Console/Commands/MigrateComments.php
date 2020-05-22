<?php

namespace App\Console\Commands;


use App\Models\Comment;
use App\Models\UserPost;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MigrateComments extends BaseCommand
{
   // protected $migrateFromId = 3001;
    //protected $commandStatusRecipients = ['nirbhay95m@gmail.com'];
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:comment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfers comments data from source model to target model';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->sourceModel = new UserPost();
        $this->targetModel = new Comment();
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
            parent::createBar();
            //$this->sourceModel::select('comment', 'comment_user',DB::Raw('COUNT(*) as `count`'))
              //  ->groupBy('comment', 'comment_user')
                //->havingRaw('COUNT(*) >= 1')
            $this->sourceModel::where('id', '>', $this->getLastIdTarget())->orderBy('id', 'ASC')->chunk($this->defaultChunkSize, function ($models) {
                DB::beginTransaction();
                foreach ($models as $model) {
                    $this->targetModel::insert(['id' => $model->id, 'comment' => $model->comment, 'comment_user' => $model->comment_user]);
                    parent::advanceBar();
                    //if($this->getLastIdTarget()==10000){
                    //throw new Exception('oops');}
                }
                DB::commit();
            });

            Parent::finishBar();
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
