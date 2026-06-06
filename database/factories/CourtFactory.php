<?php

namespace Database\Factories;

use App\Models\Courts;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\FranchiseLocation;

/**
 * @extends Factory<Courts>
 */
class CourtFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
  public function definition(): array
    {
        return [
            'location_id' => FranchiseLocation::factory(),
            'court_name' => 'Court '.fake()->numberBetween(1, 20),
            'court_type' => fake()->randomElement([
                'Padel'
            ]),
            'price_per_hour' => fake()->numberBetween(
                50000,
                200000
            ),
            'status' => 'active',
        ];
    }
}
