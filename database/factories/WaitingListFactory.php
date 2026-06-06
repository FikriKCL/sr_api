<?php

namespace Database\Factories;

use App\Models\WaitingList;
use Illuminate\Database\Eloquent\Factories\Factory;

namespace Database\Factories;

use App\Models\User;
use App\Models\Court;
use Illuminate\Database\Eloquent\Factories\Factory;

class WaitingListFactory extends Factory
    {
    public function definition(): array
        {
            return [
                'user_id' => User::inRandomOrder()->first()?->id
                    ?? User::factory(),

                'court_id' => Court::inRandomOrder()->first()?->id
                    ?? Court::factory(),

                'reservation_date' => fake()
                    ->dateTimeBetween('now', '+1 month')
                    ->format('Y-m-d'),

                'requested_time' => fake()->time(),

                'position' => fake()->numberBetween(1, 10),
            ];
        }
}
