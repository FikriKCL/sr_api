<?php

namespace Database\Factories;

use App\Models\FranchiseLocation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FranchiseLocation>
 */
class FranchiseLocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
        {
            return [
                'name' => fake()->company(),
                'address' => fake()->address(),
                'city' => fake()->city(),
                'latitude' => fake()->latitude(),
                'longitude' => fake()->longitude(),
                'open_hour' => '08:00:00',
                'close_hour' => '22:00:00',
            ];
        }
}
