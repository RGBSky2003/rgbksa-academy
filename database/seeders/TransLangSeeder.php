<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TransLangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Langs = [
            ['name' => 'Arabic'],
            ['name' => 'English'],
            ['name' => 'Spanish'],
            ['name' => 'German'],
            ['name' => 'Russian'],
            ['name' => 'Turkish'],
            ['name' => 'Itailian']
        ];

        DB::table('trans_langs')->insert($Langs);
    }
}
