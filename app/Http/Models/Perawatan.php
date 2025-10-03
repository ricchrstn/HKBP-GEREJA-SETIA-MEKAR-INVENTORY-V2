<?php
namespace App\Models; // Mendefinisikan namespace untuk kelas ini, menunjukkan bahwa ia berada di dalam direktori 'app/Models'.

use Illuminate\Database\Eloquent\Factories\HasFactory; // Mengimpor trait 'HasFactory' yang digunakan untuk membuat factory bagi model ini, berguna untuk seeding database atau pengujian.
use Illuminate\Database\Eloquent\Model; // Mengimpor kelas dasar 'Model' dari Eloquent, yang merupakan fondasi untuk semua model di Laravel.

class Perawatan extends Model // Mendefinisikan kelas 'Perawatan' yang merupakan turunan dari kelas 'Model' Eloquent.
{
    use HasFactory; // Menggunakan trait 'HasFactory' di dalam kelas 'Perawatan'.

    protected $table = 'perawatan'; // Secara eksplisit menentukan nama tabel di database yang akan dihubungkan dengan model ini adalah 'perawatan'. Ini adalah praktik yang baik untuk kejelasan.

    protected $fillable = [ // Mendefinisikan kolom-kolom tabel yang boleh diisi (mass assignable) secara massal melalui metode seperti `create()` atau `update()`.
        'barang_id', // Foreign key yang mengacu pada ID barang yang sedang dirawat.
        'tanggal_perawatan', // Tanggal kapan perawatan dilakukan.
        'jenis_perawatan', // Jenis perawatan yang dilakukan (misalnya, 'Servis Rutin', 'Perbaikan', 'Penggantian Komponen').
        'biaya', // Biaya yang dikeluarkan untuk perawatan.
        'keterangan', // Keterangan atau detail tambahan mengenai perawatan.
        'status', // Status perawatan (misalnya, 'Selesai', 'Sedang Berlangsung', 'Tertunda').
        'user_id' // Foreign key yang mengacu pada ID user (admin/petugas) yang mencatat perawatan.
    ];

    protected $casts = [ // Mendefinisikan bagaimana atribut tertentu harus di-cast (dikonversi) ke tipe data PHP tertentu saat diambil dari database.
        'tanggal_perawatan' => 'date', // Mengonversi 'tanggal_perawatan' menjadi objek Carbon Date. Ini memudahkan manipulasi tanggal.
        'biaya' => 'decimal:2' // Mengonversi 'biaya' menjadi tipe data desimal dengan 2 angka di belakang koma. Penting untuk presisi keuangan.
    ];

    public function barang() // Mendefinisikan relasi "belongsTo" (milik) dengan model 'Barang'.
    {
        return $this->belongsTo(Barang::class); // Menunjukkan bahwa setiap record 'Perawatan' milik satu 'Barang'. Ini berarti tabel 'perawatan' memiliki 'barang_id' sebagai foreign key yang merujuk ke tabel 'barang'.
    }

    public function user() // Mendefinisikan relasi "belongsTo" (milik) dengan model 'User'.
    {
        return $this->belongsTo(User::class); // Menunjukkan bahwa setiap record 'Perawatan' dicatat oleh satu 'User' (misalnya, petugas pemeliharaan). Ini berarti tabel 'perawatan' memiliki 'user_id' sebagai foreign key yang merujuk ke tabel 'users'.
    }
}