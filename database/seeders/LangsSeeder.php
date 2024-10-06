<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class LangsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('langs')->insert([
            /*1*/
            'sys_name' => 'ee',
            'name' => 'Estonian',
            'is_enabled' =>1
        ]);

        DB::table('langs')->insert([
            /*2*/
            'sys_name' => 'en',
            'name' => 'English',
            'is_enabled' =>1
        ]);

        DB::table('langs')->insert([
            /*3*/
            'sys_name' => 'ru',
            'name' => 'Russian',
            'is_enabled' =>1
        ]);
    }
}
