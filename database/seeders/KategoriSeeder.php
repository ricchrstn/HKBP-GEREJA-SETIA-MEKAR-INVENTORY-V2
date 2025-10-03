<?php

namespace Database\Seeders; // Mendefinisikan namespace untuk kelas ini, menunjukkan lokasinya dalam struktur proyek.

use Illuminate\Database\Seeder; // Mengimpor kelas dasar 'Seeder' yang harus diwarisi oleh setiap seeder.
use App\Models\Kategori; // Mengimpor model 'Kategori' yang akan digunakan untuk berinteraksi dengan tabel 'kategoris' di database.

class KategoriSeeder extends Seeder // Mendefinisikan kelas 'KategoriSeeder' yang merupakan turunan dari 'Seeder'.
{
    public function run() // Metode 'run' akan dieksekusi ketika seeder ini dipanggil (misalnya, dari DatabaseSeeder atau 'php artisan db:seed --class=KategoriSeeder').
    {
        $kategoris = [ // Mendefinisikan sebuah array yang berisi data kategori yang akan dimasukkan ke database.
            [
                'nama' => 'Peralatan Ibadah', // Nilai untuk kolom 'nama'.
                'deskripsi' => 'Peralatan yang digunakan untuk keperluan ibadah dan pelayanan', // Nilai untuk kolom 'deskripsi'.
            ],
            [
                'nama' => 'Peralatan Elektronik',
                'deskripsi' => 'Peralatan elektronik, sound system, dan multimedia',
            ],
            [
                'nama' => 'Furniture',
                'deskripsi' => 'Meja, kursi, lemari, dan perabotan gereja',
            ],
            [
                'nama' => 'Peralatan Dapur',
                'deskripsi' => 'Peralatan untuk dapur dan keperluan konsumsi',
            ],
            [
                'nama' => 'Peralatan Kebersihan',
                'deskripsi' => 'Peralatan untuk menjaga kebersihan gereja',
            ],
            [
                'nama' => 'Alat Tulis Kantor',
                'deskripsi' => 'Keperluan administrasi dan tulis menulis kantor gereja',
            ],
            [
                'nama' => 'Dekorasi & Perlengkapan',
                'deskripsi' => 'Dekorasi, perlengkapan acara, dan bunga',
            ],
            [
                'nama' => 'Pakaian & Seragam',
                'deskripsi' => 'Pakaian liturgis, seragam pelayan, dan perlengkapan',
            ],
            [
                'nama' => 'Alat Musik',
                'deskripsi' => 'Alat musik untuk pujian dan pujian',
            ],
            [
                'nama' => 'Perlengkapan Maintenance',
                'deskripsi' => 'Peralatan untuk perawatan dan perbaikan gedung',
            ],
        ];

        foreach ($kategoris as $kategori) { // Melakukan iterasi (loop) pada setiap item dalam array '$kategoris'.
            Kategori::create($kategori); // Untuk setiap item (array assosiatif) di '$kategoris', membuat record baru di tabel 'kategoris' menggunakan model 'Kategori'.
        }
    }
}