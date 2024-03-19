<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LangCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lang_courses = [
            ['name' => 'English Course' , 'price' => 11.54],
            ['name' => 'Italian Course' , 'price' => 24.31],
            ['name' => 'Spanish Course' , 'price' => 89.12],
            ['name' => 'Turkish Course' , 'price' => 14.23],
            ['name' => 'German Course' , 'price' => 75.24],
            ['name' => 'Arabic Course' , 'price' => 98.21],
            ['name' => 'Russian Course' , 'price' => 100.00]
        ];

        DB::table('lang_courses')->insert($lang_courses);
    }
}
