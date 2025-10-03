<?php

namespace Database\Seeders; // Mendefinisikan namespace untuk kelas ini, menunjukkan lokasinya dalam struktur proyek.

use Illuminate\Database\Seeder; // Mengimpor kelas dasar 'Seeder' yang harus diwarisi oleh setiap seeder.
use App\Models\User; // Mengimpor model 'User' yang akan digunakan untuk berinteraksi dengan tabel 'users' di database.
use Illuminate\Support\Facades\Hash; // Mengimpor fasad 'Hash' untuk mengenkripsi (hash) password.

class UserSeeder extends Seeder // Mendefinisikan kelas 'UserSeeder' yang merupakan turunan dari 'Seeder'.
{
    public function run() // Metode 'run' akan dieksekusi ketika seeder ini dipanggil (misalnya, dari DatabaseSeeder atau 'php artisan db:seed --class=UserSeeder').
    {
        // Admin User
        User::create([ // Membuat record user baru di tabel 'users' menggunakan model 'User'.
            'name' => 'Administrator', // Nilai untuk kolom 'name'.
            'email' => 'admin@gmail.com', // Nilai untuk kolom 'email'.
            'password' => Hash::make('admin123'), // Mengenkripsi password 'admin123' sebelum disimpan ke database. Ini adalah praktik keamanan standar.
            'role' => 'admin', // Nilai untuk kolom 'role', mengidentifikasi user ini sebagai administrator.
            'email_verified_at' => now(), // Mengatur kolom 'email_verified_at' ke waktu saat ini, menandakan email sudah diverifikasi.
        ]);

        // Pengurus User
        User::create([ // Membuat record user baru di tabel 'users'.
            'name' => 'Pengurus Gereja',
            'email' => 'pengurus@mail.com',
            'password' => Hash::make('pengurus123'), // Mengenkripsi password 'pengurus123'.
            'role' => 'pengurus', // Mengidentifikasi user ini sebagai pengurus.
            'email_verified_at' => now(),
        ]);

        // Bendahara User
        User::create([ // Membuat record user baru di tabel 'users'.
            'name' => 'Bendahara Gereja',
            'email' => 'bendahara@mail.com',
            'password' => Hash::make('bendahara123'), // Mengenkripsi password 'bendahara123'.
            'role' => 'bendahara', // Mengidentifikasi user ini sebagai bendahara.
            'email_verified_at' => now(),
        ]);
    }
}