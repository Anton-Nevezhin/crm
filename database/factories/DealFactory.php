<?php

namespace Database\Factories;

use App\Models\Deal;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class DealFactory extends Factory
{
    protected $model = Deal::class;

    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'name' => $this->faker->sentence(3),
            'amount' => $this->faker->randomFloat(2, 1000, 100000),
            'status' => $this->faker->randomElement(['new', 'in_progress', 'closed', 'lost']),
            'description' => $this->faker->paragraph(),
        ];
    }
}