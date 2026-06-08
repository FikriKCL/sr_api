<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentOption;

class PaymentOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          PaymentOption::insert([
                [
                    'label' => 'QRIS',
                    'value' => 'qris',
                    'icon' => 'qr',
                    'status' => 'active',
                ],
                [
                    'label' => 'Cash',
                    'value' => 'cash',
                    'icon' => 'cash',
                    'status' => 'active',
                ],
                [
                    'label' => 'GO-Pay',
                    'value' => 'gopay',
                    'icon' => 'wallet',
                    'status' => 'active',
                ],
            ]);
    }
}
