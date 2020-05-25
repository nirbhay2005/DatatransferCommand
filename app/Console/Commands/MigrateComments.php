<?php

namespace App\Console\Commands;


use App\Models\Comment;
use App\Models\UserPost;

class MigrateComments extends BaseCommand
{
    protected $sourceField = ['id', 'comment', 'comment_user'];
    protected $targetField = ['id', 'comment', 'comment_user'];

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
    protected $description = 'Transfers comments data from source model to target model as per defined source and target fields';

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
        if($this->transferSourceToTarget()){
            return true;
        }else{
            return false;
        }
    }
}
