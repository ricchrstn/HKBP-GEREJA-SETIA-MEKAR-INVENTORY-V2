<?php

namespace Database\Seeders; // Mendefinisikan namespace untuk kelas ini, menunjukkan lokasinya dalam struktur proyek.

use Illuminate\Database\Seeder; // Mengimpor kelas dasar 'Seeder' yang harus diwarisi oleh setiap seeder.

class DatabaseSeeder extends Seeder // Mendefinisikan kelas 'DatabaseSeeder' yang merupakan turunan dari 'Seeder'. Ini adalah seeder utama.
{
    /**
     * Seed the application's database.
     */
    public function run(): void // Metode 'run' akan dieksekusi ketika Anda menjalankan perintah 'php artisan db:seed'.
    {
        $this->call([ // Memanggil seeder lain yang terdaftar dalam array ini.
            UserSeeder::class, // Memanggil 'UserSeeder' untuk mengisi tabel 'users' dengan data awal.
            KategoriSeeder::class, // Memanggil 'KategoriSeeder' untuk mengisi tabel 'kategoris' (atau 'kategori') dengan data awal.
            BarangSeeder::class, // Memanggil 'BarangSeeder' untuk mengisi tabel 'barang' dengan data awal.
            KriteriaSeeder::class, // Memanggil 'KriteriaSeeder' untuk mengisi tabel 'kriterias' dengan data awal.
        ]);
    }
}