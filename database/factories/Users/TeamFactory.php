<?php

namespace Database\Factories\Users;

use Carbon\Carbon;
use App\Models\Users\Team;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TeamFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Team::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
           "database" => (string) "A_". uniqid()."_db" ,
           "active_until" => Carbon::now()->addDays(3),
           "payment_plan_id" => 0 , // by default
           "key" => Str::uuid(),
        ];
    }
}
