<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;


class DatabaseBillsSeeder extends Seeder
{

    public function run()
    {
        $this->call(LangsSeeder::class);
        $this->call(PermissionsSeeder::class);
        $this->call(RepresentativeSeeder::class);
        $this->call(RoleSeeder::class);
        //$this->call(UserSeeder::class);
        //\App\Models\Users\User::factory(10)->create();
    }

}
