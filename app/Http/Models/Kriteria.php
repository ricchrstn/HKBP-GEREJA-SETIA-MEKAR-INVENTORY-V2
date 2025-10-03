<?php

namespace App\Models; // Mendefinisikan namespace untuk kelas ini, menunjukkan bahwa ia berada di dalam direktori 'app/Models'.

use Illuminate\Database\Eloquent\Factories\HasFactory; // Mengimpor trait 'HasFactory' yang digunakan untuk membuat factory bagi model ini, berguna untuk seeding database atau pengujian.
use Illuminate\Database\Eloquent\Model; // Mengimpor kelas dasar 'Model' dari Eloquent, yang merupakan fondasi untuk semua model di Laravel.

class Kriteria extends Model // Mendefinisikan kelas 'Kriteria' yang merupakan turunan dari kelas 'Model' Eloquent.
{
    use HasFactory; // Menggunakan trait 'HasFactory' di dalam kelas 'Kriteria'.

    // protected $table = 'kriterias'; // Tidak ada definisi $table secara eksplisit. Laravel akan secara otomatis mengasumsikan nama tabel adalah 'kriterias' (plural dari 'Kriteria').

    protected $fillable = [ // Mendefinisikan kolom-kolom tabel yang boleh diisi (mass assignable) secara massal melalui metode seperti `create()` atau `update()`.
        'nama', // Nama kriteria (misalnya, 'Harga', 'Kualitas', 'Waktu Pengiriman').
        'bobot', // Bobot atau prioritas kriteria dalam suatu perhitungan (misalnya, untuk metode TOPSIS atau SAW).
        'tipe' // Tipe kriteria (misalnya, 'benefit' jika nilai lebih besar lebih baik, atau 'cost' jika nilai lebih kecil lebih baik).
    ];

    protected $casts = [ // Mendefinisikan bagaimana atribut tertentu harus di-cast (dikonversi) ke tipe data PHP tertentu saat diambil dari database.
        'bobot' => 'float' // Menginstruksikan Laravel untuk mengonversi kolom 'bobot' menjadi tipe data float. Ini penting untuk perhitungan matematis.
    ];

    public function nilaiPengadaanKriterias() // Mendefinisikan relasi "one-to-many" (hasMany) dengan model 'NilaiPengadaanKriteria'.
    {
        return $this->hasMany(NilaiPengadaanKriteria::class); // Menunjukkan bahwa satu instance 'Kriteria' bisa memiliki banyak instance 'NilaiPengadaanKriteria'.
                                                             // Ini berarti tabel 'nilai_pengadaan_kriterias' (atau nama tabel terkait) memiliki foreign key 'kriteria_id' yang merujuk kembali ke tabel 'kriterias'.
    }
}