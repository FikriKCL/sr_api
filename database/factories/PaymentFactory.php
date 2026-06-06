<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Reservation;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
        public function definition(): array
        {
            return [
                'reservation_id' => Reservation::factory(),

                'payment_method' => fake()->randomElement([
                    'Transfer',
                    'OVO',
                    'GoPay',
                    'Dana',
                    'Credit Card'
                ]),

                'amount' => fake()->numberBetween(
                    100000,
                    400000
                ),

                'status' => fake()->randomElement([
                    'paid',
                    'pending',
                    'failed'
                ]),

                'transaction_id' => fake()->uuid(),

                'paid_at' => now(),
            ];
        }
}
