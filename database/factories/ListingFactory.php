<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Network;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ListingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'network_id' => Network::factory(),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'type' => 'offer',
            'status' => 'open',
            'expires_at' => now()->addDays(3),
        ];
    }
}
