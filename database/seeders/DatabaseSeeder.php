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
        FranchiseLocation::factory(5)->create();

        Court::factory(20)->create();

        User::factory(50)->create();

        Reservation::factory(100)->create();

        Payment::factory(100)->create();

        WaitingList::factory(50)->create();
    }
}