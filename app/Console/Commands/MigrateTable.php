<?php

namespace App\Console\Commands;

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
    protected $description = 'Run migration commands in sequence';

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
        $commandSequence = ['migrate:users', 'migrate:post', 'migrate:comment'];

        foreach ($commandSequence as $command) {
            if ($this->call($command) == 1) {
                continue;

            } else {
                exit();
            }
        }
    }
}
