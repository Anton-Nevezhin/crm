<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'type' => $this->faker->randomElement(['call', 'meeting', 'email']),
            'contact_date' => $this->faker->date(),
            'comment' => $this->faker->sentence(),
        ];
    }
}