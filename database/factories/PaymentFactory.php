<?php

namespace Database\Factories;

use App\Models\PaymentOption;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'reservation_id' =>
                Reservation::factory(),

            'payment_option_id' =>
                PaymentOption::query()
                    ->inRandomOrder()
                    ->value('id'),

            'amount' =>
                fake()->numberBetween(
                    50000,
                    500000
                ),

            'status' =>
                fake()->randomElement([
                    'pending',
                    'paid',
                    'failed',
                ]),

            'transaction_id' =>
                'TRX-' . strtoupper(
                    Str::random(12)
                ),

            'paid_at' =>
                fake()->optional()->dateTime(),
        ];
    }
}