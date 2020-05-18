<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateUsersTable extends BaseCommand
{
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
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->sourceModel = new User();
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->sourceModel::where('id', '>', $this->getLastIdTarget())->orderBy('id', 'ASC')->chunk($this->defaultChunkSize, function ($users) {
            try {
                DB::beginTransaction();
                foreach ($users  as $user) {

                }
                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();
            }
        });
    }
}
