<?php

namespace App\Models; // Ini mendefinisikan namespace untuk kelas ini, menunjukkan lokasinya di dalam struktur direktori aplikasi (App/Models).

use Illuminate\Database\Eloquent\Factories\HasFactory; // Mengimpor trait HasFactory dari Eloquent untuk memungkinkan pembuatan instance model dengan factory.
use Illuminate\Database\Eloquent\Model; // Mengimpor kelas dasar Model dari Eloquent, yang merupakan fondasi untuk semua model di Laravel.

class AnalisisTopsis extends Model // Mendefinisikan kelas 'AnalisisTopsis' yang mewarisi semua fungsionalitas dari kelas 'Model' Eloquent.
{
    use HasFactory; // Menggunakan trait HasFactory di dalam kelas ini.

    protected $table = 'analisis_topsis'; // Ini mendefinisikan nama tabel di database yang akan dihubungkan dengan model ini. Secara default, Laravel akan mengasumsikan nama tabel adalah bentuk plural dari nama model (misalnya, 'analisis_topsis' untuk 'AnalisisTopsis'). Baris ini secara eksplisit mengaturnya.

    protected $fillable = [ // Ini adalah array yang mendefinisikan kolom-kolom tabel yang boleh diisi (mass assignable) secara massal melalui metode create atau update Eloquent.
        'pengajuan_id', // Kolom untuk menyimpan ID pengajuan terkait.
        'nilai_preferensi', // Kolom untuk menyimpan nilai preferensi hasil perhitungan Topsis.
        'ranking' // Kolom untuk menyimpan ranking dari hasil analisis Topsis.
    ];

    protected $casts = [ // Ini adalah array yang mendefinisikan bagaimana atribut tertentu harus di-cast ke tipe data tertentu saat diambil dari database atau disimpan.
        'nilai_preferensi' => 'float', // Menginstruksikan Laravel untuk mengonversi nilai 'nilai_preferensi' menjadi tipe data float.
        'ranking' => 'integer' // Menginstruksikan Laravel untuk mengonversi nilai 'ranking' menjadi tipe data integer.
    ];

    public function pengajuan() // Mendefinisikan relasi antar model. Ini adalah metode yang mendefinisikan relasi "many-to-one" (belongsTo).
    {
        return $this->belongsTo(Pengajuan::class); // Mengatakan bahwa setiap instance AnalisisTopsis 'milik' satu instance Pengajuan. 'Pengajuan::class' merujuk pada nama kelas model Pengajuan.
    }
}