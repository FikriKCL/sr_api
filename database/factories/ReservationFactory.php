<?php

namespace Database\Factories;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Court;

/**
 * @extends Factory<Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
        public function definition(): array
        {
            return [
                'user_id' => User::factory(),
                'court_id' => Court::factory(),

                'reservation_date' => fake()->dateTimeBetween(
                    'now',
                    '+1 month'
                ),

                'start_time' => '09:00:00',
                'end_time' => '11:00:00',

                'duration' => 2,

                'total_price' => fake()->numberBetween(
                    100000,
                    400000
                ),

                'status' => fake()->randomElement([
                    'pending',
                    'approved',
                    'completed'
                ]),
            ];
        }
}
