<?php

namespace Database\Seeders; // Mendefinisikan namespace untuk kelas ini, menunjukkan lokasinya dalam struktur proyek.

use Illuminate\Database\Seeder; // Mengimpor kelas dasar 'Seeder' yang harus diwarisi oleh setiap seeder.
use App\Models\Kriteria; // Mengimpor model 'Kriteria' yang akan digunakan untuk berinteraksi dengan tabel 'kriterias' di database.

class KriteriaSeeder extends Seeder // Mendefinisikan kelas 'KriteriaSeeder' yang merupakan turunan dari 'Seeder'.
{
    // database/seeders/KriteriaSeeder.php
    public function run() // Metode 'run' akan dieksekusi ketika seeder ini dipanggil (misalnya, dari DatabaseSeeder atau 'php artisan db:seed --class=KriteriaSeeder').
    {
        $kriterias = [ // Mendefinisikan sebuah array yang berisi data kriteria yang akan dimasukkan atau diperbarui di database.
            [
                'nama' => 'Tingkat Urgensi Barang', // Nilai untuk kolom 'nama'. Ini akan digunakan sebagai kunci unik.
                'bobot' => 0.3, // Nilai untuk kolom 'bobot'.
                'tipe' => 'benefit' // Nilai untuk kolom 'tipe' (enum: 'benefit' atau 'cost').
            ],
            [
                'nama' => 'Ketersediaan Stok Barang',
                'bobot' => 0.25,
                'tipe' => 'cost' // Contoh 'cost' berarti nilai yang lebih rendah lebih baik (misalnya, stok sedikit = butuh segera = cost tinggi)
            ],
            [
                'nama' => 'Ketersediaan Dana Pengadaan',
                'bobot' => 0.45,
                'tipe' => 'benefit'
            ]
        ];

        foreach ($kriterias as $kriteria) { // Melakukan iterasi (loop) pada setiap item dalam array '$kriterias'.
            Kriteria::updateOrCreate( // Menggunakan metode Eloquent 'updateOrCreate'.
                ['nama' => $kriteria['nama']], // Parameter pertama: Kondisi pencarian. Jika ada kriteria dengan 'nama' ini, maka akan diupdate.
                [                               // Parameter kedua: Data yang akan digunakan untuk update atau create.
                    'bobot' => $kriteria['bobot'],
                    'tipe' => $kriteria['tipe']
                ]
            );
        }
    }
}