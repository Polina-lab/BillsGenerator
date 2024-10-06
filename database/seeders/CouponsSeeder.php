<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CouponsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 0 ; $i< 3; $i++ ) {
            DB::table('coupons')->insert([
                    'code' => Str::random(6),
                    'amount_percent' => null,
                    'amount_eur' =>  rand(1, 9) - 0. . '.' . rand(1, 9) ,
                    'start_date' => Carbon::now(),
                    'end_date' => Carbon::now()->addDays(7),
                    'usages_remaining' => rand(1, 3),
                    'user_id' => 1
                ]);
        }
    }
}
