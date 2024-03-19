<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TransIndustriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $industries = [
            ['name' => 'Legal'],
            ['name' => 'Finance'],
            ['name' => 'Medical'],
            ['name' => 'Academic'],
            ['name' => 'Political'],
        ];

        DB::table('trans_industries')->insert($industries);
    }
}
