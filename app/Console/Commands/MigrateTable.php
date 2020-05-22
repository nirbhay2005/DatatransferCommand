<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use function foo\func;
use Illuminate\Support\Arr;

class MigrateTable extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run commands in sequence';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $commandSequence = ['migrate:userpost', 'migrate:post', 'migrate:comment'];

        foreach ($commandSequence as $command) {
            if ($this->call($command) == 1) {
                continue;

            } else {
                exit();
            }
        }
    }
}
