<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kriteria;

class KriteriaSeeder extends Seeder
{
    // database/seeders/KriteriaSeeder.php
    public function run()
    {
        $kriterias = [
            [
                'nama' => 'Tingkat Urgensi Barang',
                'bobot' => 0.3,
                'tipe' => 'benefit'
            ],
            [
                'nama' => 'Ketersediaan Stok Barang',
                'bobot' => 0.25,
                'tipe' => 'cost'
            ],
            [
                'nama' => 'Ketersediaan Dana Pengadaan',
                'bobot' => 0.45,
                'tipe' => 'benefit'
            ]
        ];

        foreach ($kriterias as $kriteria) {
            Kriteria::updateOrCreate(
                ['nama' => $kriteria['nama']],
                [
                    'bobot' => $kriteria['bobot'],
                    'tipe' => $kriteria['tipe']
                ]
            );
        }
    }
}
