<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Laravel\Models\ClassSectionSubject;

class ClassTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        for ($i=0; $i < 2; $i++) {
            DB::table('class_section_subjects')
                ->insert([
                    'class' => $faker->numberBetween(1,10),
                'section' => $faker->randomElement(['A']),
                    'subject' => $faker->colorName
                ]);
        }
    }
}
