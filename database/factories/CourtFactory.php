<?php

namespace Database\Factories;

use App\Models\Court;
use App\Models\FranchiseLocation;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourtFactory extends Factory
{
    protected $model = Court::class;

    public function definition(): array
    {
        return [
            'location_id' => FranchiseLocation::factory(),

            'court_name' => 'Court '.$this->faker->numberBetween(1, 20),

            'court_type' => 'Padel',

            'price_per_hour' => $this->faker->numberBetween(
                100000,
                300000
            ),

            'picture' => $this->faker->randomElement([
                'courts/court1.jpeg',
                'courts/court2.jpeg',
                'courts/court3.jpeg',
                'courts/court4.jpeg',
                'courts/court5.jpeg',
            ]),

            'rating' => $this->faker->randomFloat(
                1,
                4.0,
                5.0
            ),

            'description' => $this->faker->sentence(15),

            'status' => 'active',
        ];
    }
}