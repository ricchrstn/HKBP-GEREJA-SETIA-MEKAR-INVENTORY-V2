<?php
namespace App\Models; // Mendefinisikan namespace untuk kelas ini, menunjukkan bahwa ia berada di dalam direktori 'app/Models'.

use Illuminate\Database\Eloquent\Factories\HasFactory; // Mengimpor trait 'HasFactory' yang digunakan untuk membuat factory bagi model ini, berguna untuk seeding database atau pengujian.
use Illuminate\Database\Eloquent\Model; // Mengimpor kelas dasar 'Model' dari Eloquent, yang merupakan fondasi untuk semua model di Laravel.

class BarangKeluar extends Model // Mendefinisikan kelas 'BarangKeluar' yang merupakan turunan dari kelas 'Model' Eloquent.
{
    use HasFactory; // Menggunakan trait 'HasFactory' di dalam kelas 'BarangKeluar'.

    protected $table = 'barang_keluar'; // Secara eksplisit menentukan nama tabel di database yang akan dihubungkan dengan model ini adalah 'barang_keluar'. Ini adalah praktik yang baik untuk kejelasan.

    protected $fillable = [ // Mendefinisikan kolom-kolom tabel yang boleh diisi (mass assignable) secara massal melalui metode seperti `create()` atau `update()`.
        'barang_id', // Foreign key yang mengacu pada ID barang yang keluar.
        'tanggal', // Tanggal kapan barang keluar dicatat.
        'jumlah', // Jumlah barang yang keluar.
        'tujuan', // Tujuan atau pihak yang menerima barang keluar.
        'keterangan', // Informasi atau detail tambahan mengenai transaksi barang keluar.
        'user_id' // Foreign key yang mengacu pada ID user yang mencatat barang keluar.
    ];

    protected $casts = [ // Mendefinisikan bagaimana atribut tertentu harus di-cast (dikonversi) ke tipe data PHP tertentu saat diambil dari database.
        'tanggal' => 'date', // Menginstruksikan Laravel untuk mengonversi kolom 'tanggal' menjadi objek Carbon Date (tipe data tanggal/waktu PHP) saat diakses. Ini memudahkan manipulasi tanggal.
        'jumlah' => 'integer' // Menginstruksikan Laravel untuk mengonversi kolom 'jumlah' menjadi tipe data integer.
    ];

    public function barang() // Mendefinisikan relasi "belongsTo" (milik) dengan model 'Barang'.
    {
        return $this->belongsTo(Barang::class); // Menunjukkan bahwa setiap record 'BarangKeluar' milik satu 'Barang'. Ini berarti tabel 'barang_keluar' memiliki 'barang_id' sebagai foreign key yang merujuk ke tabel 'barang'.
    }

    public function user() // Mendefinisikan relasi "belongsTo" (milik) dengan model 'User'.
    {
        return $this->belongsTo(User::class); // Menunjukkan bahwa setiap record 'BarangKeluar' dicatat oleh satu 'User'. Ini berarti tabel 'barang_keluar' memiliki 'user_id' sebagai foreign key yang merujuk ke tabel 'users'.
    }
}