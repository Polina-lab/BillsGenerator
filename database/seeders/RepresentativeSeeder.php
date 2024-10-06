<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RepresentativeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('representatives')->insert([
            'name' => 'bailiff',
        ]);

        DB::table('representatives')->insert([
            'name' => 'physician',
        ]);

        DB::table('representatives')->insert([
            'name' => 'CEO',
        ]);

        DB::table('representatives')->insert([
            'name' => 'notary',
        ]);

        DB::table('representatives')->insert([
            'name' => 'advocate'
        ]);

    }
}
