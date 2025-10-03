<?php

namespace App\Models; // Mendefinisikan namespace untuk kelas ini, menunjukkan bahwa ia berada di dalam direktori 'app/Models'.

use Illuminate\Database\Eloquent\Factories\HasFactory; // Mengimpor trait 'HasFactory' yang digunakan untuk membuat factory bagi model ini, berguna untuk seeding database atau pengujian.
use Illuminate\Database\Eloquent\Model; // Mengimpor kelas dasar 'Model' dari Eloquent, yang merupakan fondasi untuk semua model di Laravel.

class NilaiPengadaanKriteria extends Model // Mendefinisikan kelas 'NilaiPengadaanKriteria' yang merupakan turunan dari kelas 'Model' Eloquent.
{
    use HasFactory; // Menggunakan trait 'HasFactory' di dalam kelas 'NilaiPengadaanKriteria'.

    // protected $table = 'nilai_pengadaan_kriterias'; // Tidak ada definisi $table secara eksplisit. Laravel akan secara otomatis mengasumsikan nama tabel adalah 'nilai_pengadaan_kriterias' (plural dari 'NilaiPengadaanKriteria').

    protected $fillable = [ // Mendefinisikan kolom-kolom tabel yang boleh diisi (mass assignable) secara massal melalui metode seperti `create()` atau `update()`.
        'pengajuan_id', // Foreign key yang mengacu pada ID pengajuan barang atau proyek yang sedang dievaluasi.
        'kriteria_id', // Foreign key yang mengacu pada ID kriteria yang digunakan dalam evaluasi.
        'nilai' // Nilai aktual yang diberikan untuk pengajuan tertentu berdasarkan kriteria tertentu.
    ];

    protected $casts = [ // Mendefinisikan bagaimana atribut tertentu harus di-cast (dikonversi) ke tipe data PHP tertentu saat diambil dari database.
        'nilai' => 'float' // Menginstruksikan Laravel untuk mengonversi kolom 'nilai' menjadi tipe data float. Ini penting untuk perhitungan matematis dalam metode seperti TOPSIS/SAW.
    ];

    public function pengajuan() // Mendefinisikan relasi "belongsTo" (milik) dengan model 'Pengajuan'.
    {
        return $this->belongsTo(Pengajuan::class); // Menunjukkan bahwa setiap record 'NilaiPengadaanKriteria' milik satu 'Pengajuan'. Ini berarti tabel ini memiliki 'pengajuan_id' sebagai foreign key yang merujuk ke tabel 'pengajuans'.
    }

    public function kriteria() // Mendefinisikan relasi "belongsTo" (milik) dengan model 'Kriteria'.
    {
        return $this->belongsTo(Kriteria::class); // Menunjukkan bahwa setiap record 'NilaiPengadaanKriteria' terkait dengan satu 'Kriteria'. Ini berarti tabel ini memiliki 'kriteria_id' sebagai foreign key yang merujuk ke tabel 'kriterias'.
    }
}