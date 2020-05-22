<?php

namespace App\Console\Commands;


use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\UserPost;
use Illuminate\Support\Facades\Log;


class MigrateUserPost extends BaseCommand
{
   // protected $migrateFromId = 8001;
    //protected $commandStatusRecipients = ['nirbhay95m@gmail.com'];
    /**
     *
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:userpost';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfers user data from source model to target model';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->sourceModel = new UserPost();
        $this->targetModel = new User();
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param
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
            //$this->sourceModel::select('id','user_id','name','emails', DB::raw('COUNT(*) as `count`'))
            //  ->groupBy('user_id','name','emails')
            //->havingRaw('COUNT(*) >= 1')
            $this->sourceModel::where('id', '>', $this->getLastIdTarget())
                ->orderBy('id', 'ASC')
                ->chunk($this->defaultChunkSize, function ($models) {
                    DB::beginTransaction();
                    foreach ($models as $model) {
                        $this->targetModel::insert(['id' => $model->id, 'user_id' => $model->user_id, 'name' => $model->name, 'email' => $model->email]);
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
