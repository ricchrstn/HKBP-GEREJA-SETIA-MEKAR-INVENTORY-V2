<?php
namespace App\Models; // Mendefinisikan namespace untuk kelas ini, menunjukkan bahwa ia berada di dalam direktori 'app/Models'.

use Illuminate\Database\Eloquent\Factories\HasFactory; // Mengimpor trait 'HasFactory' yang digunakan untuk membuat factory bagi model ini, berguna untuk seeding database atau pengujian.
use Illuminate\Database\Eloquent\Model; // Mengimpor kelas dasar 'Model' dari Eloquent, yang merupakan fondasi untuk semua model di Laravel.

class BarangMasuk extends Model // Mendefinisikan kelas 'BarangMasuk' yang merupakan turunan dari kelas 'Model' Eloquent.
{
    use HasFactory; // Menggunakan trait 'HasFactory' di dalam kelas 'BarangMasuk'.

    protected $table = 'barang_masuk'; // Secara eksplisit menentukan nama tabel di database yang akan dihubungkan dengan model ini adalah 'barang_masuk'. Ini adalah praktik yang baik untuk kejelasan.

    protected $fillable = [ // Mendefinisikan kolom-kolom tabel yang boleh diisi (mass assignable) secara massal melalui metode seperti `create()` atau `update()`.
        'barang_id', // Foreign key yang mengacu pada ID barang yang masuk.
        'tanggal', // Tanggal kapan barang masuk dicatat.
        'jumlah', // Jumlah barang yang masuk.
        'keterangan', // Informasi atau detail tambahan mengenai transaksi barang masuk (misalnya, dari supplier mana, nomor PO).
        'user_id' // Foreign key yang mengacu pada ID user yang mencatat barang masuk.
    ];

    protected $casts = [ // Mendefinisikan bagaimana atribut tertentu harus di-cast (dikonversi) ke tipe data PHP tertentu saat diambil dari database.
        'tanggal' => 'date', // Menginstruksikan Laravel untuk mengonversi kolom 'tanggal' menjadi objek Carbon Date (tipe data tanggal/waktu PHP) saat diakses. Ini memudahkan manipulasi tanggal.
        'jumlah' => 'integer' // Menginstruksikan Laravel untuk mengonversi kolom 'jumlah' menjadi tipe data integer.
    ];

    public function barang() // Mendefinisikan relasi "belongsTo" (milik) dengan model 'Barang'.
    {
        return $this->belongsTo(Barang::class); // Menunjukkan bahwa setiap record 'BarangMasuk' milik satu 'Barang'. Ini berarti tabel 'barang_masuk' memiliki 'barang_id' sebagai foreign key yang merujuk ke tabel 'barang'.
    }

    public function user() // Mendefinisikan relasi "belongsTo" (milik) dengan model 'User'.
    {
        return $this->belongsTo(User::class); // Menunjukkan bahwa setiap record 'BarangMasuk' dicatat oleh satu 'User'. Ini berarti tabel 'barang_masuk' memiliki 'user_id' sebagai foreign key yang merujuk ke tabel 'users'.
    }
}