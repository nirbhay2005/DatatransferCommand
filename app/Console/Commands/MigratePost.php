<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\UserPost;

class MigratePost extends BaseCommand
{
    protected $sourceField = ['id', 'post', 'user_id'];
    protected $targetField = ['id', 'post_desc', 'user_id'];
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
    protected $description = 'Transfers post data from source model to target model as per defined source and target fields';

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
        if($this->transferSourceToTarget()){
            return true;
        }else{
            return false;
        }
    }
}

