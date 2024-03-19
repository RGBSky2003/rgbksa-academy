<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $services = [
            [
                'name'  => 'Translation',
                'price' =>  99.99
            ],

            [
                'name'  => 'Language Courses',
                'price' =>  99.99
            ],

            [
                'name'  => 'Integration Courses',
                'price' =>  99.99
            ],

            [
                'name'  => 'Studying Abroad',
                'price' =>  99.99
            ],

            [
                'name'  => 'Working Abroad',
                'price' =>  99.99
            ],

            [
                'name'  => 'Tutorial Courses',
                'price' =>  99.99
            ],
        ];

        DB::table('services')->insert($services);
    }
}
