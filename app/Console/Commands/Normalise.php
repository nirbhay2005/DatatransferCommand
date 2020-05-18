<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Console\Commands\ProcessCommand;

class Normalise extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'normalise {db1} {db2}';
   // protected $signature = 'normalise {model}';
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
        parent::__construct();
    }


    public function dumpIntoPostTable($source,$target,$Id=0){
        if($Id!=0){
            return $Id;
        }else{
            $Id = DB::connection('mysql2')
                ->table('log')->find(1)->last_post_id;
        }

        DB::connection('mysql')->table($source)->where('id','>=',$Id+1)->orderBy('id')
            ->chunk(20, function ($rows) use ($target) {
                foreach ($rows as $row) {
                    DB::connection('mysql2')->table($target)->insert(['post' => $row->post, 'user_id' => $row->user_id]);
                }
                $Id= DB::connection('mysql2')
                    ->table($target)->orderBy('id','Desc')->first()->id;
                DB::connection('mysql2')->table('log')->where('id',1)->update(['last_post_id'=>$Id]);
            });

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $connection1=$this->argument('db1');
        $connection2=$this->argument('db2');
        $table1=$this->ask("What is table1 name?");
        $table2=$this->ask("What is table2 name?");

        $this->dumpIntoPostTable($table1,$table2);
        /*
        DB::connection($connection1)->table($table1)->orderBy('id')
            ->chunk(50, function ($posts) use ($connection1, $table2, $connection2) {
            foreach ($posts as $post) {
                DB::connection($connection2)->table($table2)->insert(['post' => $post->post, 'user_id' => $post->user_id]);
            }
            $Id= DB::connection($connection2)
                ->table($table2)->orderBy('id','Desc')->first()->id;
            //dd($Id);
            DB::connection($connection2)->table('log')->where('id',1)->update(['last_post_id'=>$Id]);
        });*/





    }
}

