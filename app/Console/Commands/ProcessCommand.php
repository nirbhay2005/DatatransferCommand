<?php


namespace App\Console\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Laravel\Models\UserPost;

class ProcessCommand
{
    public $connection;

    public $table;

    public function getLastId(){

            $Id= DB::connection($this->connection)
                ->table($this->table)->orderBy('id','Desc')->first();

        DB::connection($this->connection)->table('log')->insert(['latest_id'=>$Id]);
       // DB::connection($this->connection)->table($this->table)->where('id',100)->update(['user_id'=>11]);
    }

}
