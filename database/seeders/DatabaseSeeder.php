<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CouponsSeeder::class);
        //$this->call(PermissionsSeeder::class);
        $this->call(LangsSeeder::class);
        $this->call(PaymentPlanSeeder::class);
        //$this->call(RoleSeeder::class);
        //$this->call(UserSeeder::class);
        //\App\Models\Users\User::factory(10)->create();
    }
}
