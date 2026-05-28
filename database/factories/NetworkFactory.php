<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Network;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NetworkFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->company();
        return [
            'name' => $name,
            'slug' => \Illuminate\Support\Str::slug($name),
            'invite_code' => \Illuminate\Support\Str::random(10),
            'owner_id' => User::factory(),
        ];
    }
}
