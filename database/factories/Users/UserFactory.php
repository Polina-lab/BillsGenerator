<?php

namespace Database\Factories\Users;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class   UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
/*            'firstname' => $this->faker->firstName(),
            'lastname' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            "roles_id" => 1,*/
            'email' => $this->faker->unique()->safeEmail,
            'remember_token' => Str::random(10),
            "email_verified_at" => now(),
            "has_buyer" => $this->faker->boolean ,
            "is_enabled" => $this->faker->boolean ,
            "local_id" => null
        ];
    }
}
