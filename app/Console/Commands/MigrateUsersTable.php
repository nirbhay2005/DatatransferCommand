<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserPost;

class MigrateUsersTable extends BaseCommand
{
    protected $sourceField = ['id', 'user_id', 'name', 'email'];
    protected $targetField = ['id', 'user_id', 'name', 'email'];
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfers comments data from source model to target model as per defined source and target fields';

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
     * @return mixed
     */
    public function handle()
    {
        if($this->transferSourceToTarget()){
            return true;
        }else{
            return false;
        }
    }
}
