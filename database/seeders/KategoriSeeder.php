<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    public function run()
    {
        $kategoris = [
            [
                'nama' => 'Peralatan Ibadah',
                'deskripsi' => 'Peralatan yang digunakan untuk keperluan ibadah dan pelayanan',
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

        foreach ($kategoris as $kategori) {
            Kategori::create($kategori);
        }
    }
}
