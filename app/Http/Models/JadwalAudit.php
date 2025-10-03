<?php

namespace App\Models; // Mendefinisikan namespace untuk kelas ini, menunjukkan bahwa ia berada di dalam direktori 'app/Models'.

use Illuminate\Database\Eloquent\Factories\HasFactory; // Mengimpor trait 'HasFactory' yang digunakan untuk membuat factory bagi model ini, berguna untuk seeding database atau pengujian.
use Illuminate\Database\Eloquent\Model; // Mengimpor kelas dasar 'Model' dari Eloquent, yang merupakan fondasi untuk semua model di Laravel.

class JadwalAudit extends Model // Mendefinisikan kelas 'JadwalAudit' yang merupakan turunan dari kelas 'Model' Eloquent.
{
    use HasFactory; // Menggunakan trait 'HasFactory' di dalam kelas 'JadwalAudit'.

    protected $table = 'jadwal_audit'; // Secara eksplisit menentukan nama tabel di database yang akan dihubungkan dengan model ini adalah 'jadwal_audit'. Ini adalah praktik yang baik untuk kejelasan.

    protected $fillable = [ // Mendefinisikan kolom-kolom tabel yang boleh diisi (mass assignable) secara massal melalui metode seperti `create()` atau `update()`.
        'judul', // Judul atau nama dari jadwal audit.
        'deskripsi', // Deskripsi detail mengenai audit yang akan dilakukan.
        'tanggal_audit', // Tanggal yang dijadwalkan untuk audit.
        'status', // Status dari jadwal audit (misalnya, 'Terjadwal', 'Selesai', 'Dibatalkan', 'Revisi').
        'barang_id', // Foreign key yang mengacu pada ID barang yang akan diaudit (jika audit spesifik untuk satu barang).
        'user_id', // Foreign key yang mengacu pada ID user yang bertanggung jawab atau akan melakukan audit.
    ];

    protected $casts = [ // Mendefinisikan bagaimana atribut tertentu harus di-cast (dikonversi) ke tipe data PHP tertentu saat diambil dari database.
        'tanggal_audit' => 'date', // Menginstruksikan Laravel untuk mengonversi kolom 'tanggal_audit' menjadi objek Carbon Date (tipe data tanggal/waktu PHP) saat diakses. Ini memudahkan manipulasi tanggal.
    ];

    public function barang() // Mendefinisikan relasi "belongsTo" (milik) dengan model 'Barang'.
    {
        return $this->belongsTo(Barang::class); // Menunjukkan bahwa setiap 'JadwalAudit' bisa terkait dengan satu 'Barang'. Ini berarti tabel 'jadwal_audit' memiliki 'barang_id' sebagai foreign key yang merujuk ke tabel 'barang'.
    }

    public function user() // Mendefinisikan relasi "belongsTo" (milik) dengan model 'User'.
    {
        return $this->belongsTo(User::class); // Menunjukkan bahwa setiap 'JadwalAudit' dibuat atau ditugaskan kepada satu 'User'. Ini berarti tabel 'jadwal_audit' memiliki 'user_id' sebagai foreign key yang merujuk ke tabel 'users'.
    }
}