<?php

namespace Database\Seeders;

use App\Models\Users\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**That created for test
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Team::factory()->count(3)->create();
    }
}
