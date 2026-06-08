<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Court;
use App\Models\Reservation;
use App\Models\Payment;
use App\Models\FranchiseLocation;
use App\Models\WaitingList;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        FranchiseLocation::factory(2)->create();

        Court::factory(10)->create();

        User::factory(10)->create();

        Reservation::factory(10)->create();

        $this->call([
            PaymentOptionSeeder::class,
        ]);

        Payment::factory(10)->create();

        WaitingList::factory(10)->create();
    }
}