<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          User::insert([
                [
                    'name' => 'admin',
                    'email' => 'admin@example.com',
                    'email_verified_at' => '2026-06-08 02:57:39',
                    'password' => Hash::make('admin123'),
                    'remember_token' => 'GaGyIcpjsF',
                    'created_at' => now(),
                    'updated_at' => now(),
                    'phone' => "0896412341233",
                    'home_latitude' => '-6.90205100',
                    'home_longitude' => '107.55923700',
                ]
            ]);
    }
}
