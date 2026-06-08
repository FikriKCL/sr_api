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
        FranchiseLocation::factory()
            ->count(10)
            ->has(Court::factory()->count(4), 'courts')
            ->create();

        User::factory(10)->create();

        Reservation::factory()
            ->count(10)
            ->existingRelations()
            ->create();

        $this->call([
            PaymentOptionSeeder::class,
            UserSeeder::class,
        ]);

        Payment::factory(10)->create();

        WaitingList::factory(10)->create();


        
    }
}