<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Laravel\Models\UserPost;

class CreatePostdataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 10000; $i++) {
            $faker->seed($i);
            DB::table('user_post')
                ->insert([
                    'user_id' => $faker->numberBetween(1,10000),
                    'name' => $faker->name(),
                    'email' => 'name'.$faker->email,
                    'post' => $faker->text(50),
                    'comment' => $faker->text(30),
                    'comment_user' => $faker->numberBetween(1, 10000)
                ]);
            //
        }
    }
}

