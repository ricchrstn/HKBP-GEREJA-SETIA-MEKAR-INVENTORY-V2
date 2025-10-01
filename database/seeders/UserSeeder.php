<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin User
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Pengurus User
        User::create([
            'name' => 'Pengurus Gereja',
            'email' => 'pengurus@mail.com',
            'password' => Hash::make('pengurus123'),
            'role' => 'pengurus',
            'email_verified_at' => now(),
        ]);

        // Bendahara User
        User::create([
            'name' => 'Bendahara Gereja',
            'email' => 'bendahara@mail.com',
            'password' => Hash::make('bendahara123'),
            'role' => 'bendahara',
            'email_verified_at' => now(),
        ]);
    }
}
