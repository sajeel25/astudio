<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'first_name' => 'Test',
                'last_name'  => 'User',
                'email'      => 'test@example.com',
                'email_verified_at' => Carbon::now(),
                'password'   => Hash::make('Password123'),
            ],
            [
                'first_name' => 'Fatima',
                'last_name'  => 'Ali',
                'email'      => 'fatima@example.com',
                'email_verified_at' => Carbon::now(),
                'password'   => Hash::make('Password123'),
            ],
            [
                'first_name' => 'Mohammed',
                'last_name'  => 'Hassan',
                'email'      => 'mohammed@example.com',
                'email_verified_at' => Carbon::now(),
                'password'   => Hash::make('Password123'),
            ],
            [
                'first_name' => 'Aisha',
                'last_name'  => 'Rahman',
                'email'      => 'aisha@example.com',
                'email_verified_at' => Carbon::now(),
                'password'   => Hash::make('Password123'),
            ],
            [
                'first_name' => 'Omar',
                'last_name'  => 'Farooq',
                'email'      => 'omar@example.com',
                'email_verified_at' => Carbon::now(),
                'password'   => Hash::make('Password123'),
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(['email' => $user['email']], $user);
        }
    }
}

