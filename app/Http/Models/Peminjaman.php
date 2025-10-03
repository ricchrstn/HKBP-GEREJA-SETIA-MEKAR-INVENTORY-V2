<?php

namespace App\Models; // Mendefinisikan namespace untuk kelas ini, menunjukkan bahwa ia berada di dalam direktori 'app/Models'.

use Illuminate\Database\Eloquent\Factories\HasFactory; // Mengimpor trait 'HasFactory' yang digunakan untuk membuat factory bagi model ini, berguna untuk seeding database atau pengujian.
use Illuminate\Database\Eloquent\Model; // Mengimpor kelas dasar 'Model' dari Eloquent, yang merupakan fondasi untuk semua model di Laravel.

class Peminjaman extends Model // Mendefinisikan kelas 'Peminjaman' yang merupakan turunan dari kelas 'Model' Eloquent.
{
    use HasFactory; // Menggunakan trait 'HasFactory' di dalam kelas 'Peminjaman'.

    protected $table = 'peminjaman'; // Secara eksplisit menentukan nama tabel di database yang akan dihubungkan dengan model ini adalah 'peminjaman'. Ini adalah praktik yang baik untuk kejelasan.

    // Tambahkan 'kategori_id' ke dalam $fillable
    protected $fillable = [ // Mendefinisikan kolom-kolom tabel yang boleh diisi (mass assignable) secara massal melalui metode seperti `create()` atau `update()`.
        'barang_id', // Foreign key yang mengacu pada ID barang yang dipinjam.
        'kategori_id', // Foreign key yang mengacu pada ID kategori barang yang dipinjam (bisa opsional, tergantung desain database).
        'user_id', // Foreign key yang mengacu pada ID user (admin/pencatat) yang melakukan proses pencatatan peminjaman.
        'tanggal_pinjam', // Tanggal kapan barang dipinjam.
        'tanggal_kembali', // Tanggal rencana barang akan dikembalikan.
        'tanggal_dikembalikan', // Tanggal aktual barang dikembalikan (null jika belum dikembalikan).
        'jumlah', // Jumlah barang yang dipinjam.
        'peminjam', // Nama lengkap peminjam barang.
        'kontak', // Informasi kontak peminjam (misalnya, nomor telepon atau email).
        'keperluan', // Keperluan peminjaman barang.
        'keterangan', // Keterangan atau catatan tambahan terkait peminjaman.
        'status', // Status peminjaman (misalnya, 'Dipinjam', 'Dikembalikan', 'Terlambat', 'Dibatalkan').
    ];

    protected $casts = [ // Mendefinisikan bagaimana atribut tertentu harus di-cast (dikonversi) ke tipe data PHP tertentu saat diambil dari database.
        'tanggal_pinjam' => 'date', // Mengonversi 'tanggal_pinjam' menjadi objek Carbon Date.
        'tanggal_kembali' => 'date', // Mengonversi 'tanggal_kembali' menjadi objek Carbon Date.
        'tanggal_dikembalikan' => 'datetime', // Mengonversi 'tanggal_dikembalikan' menjadi objek Carbon DateTime (karena mungkin perlu jam dan menit).
        'jumlah' => 'integer' // Mengonversi 'jumlah' menjadi tipe data integer.
    ];

    /**
     * Get the barang that owns the peminjaman. // PHPDoc comment untuk relasi.
     */
    public function barang() // Mendefinisikan relasi "belongsTo" (milik) dengan model 'Barang'.
    {
        return $this->belongsTo(Barang::class); // Menunjukkan bahwa setiap record 'Peminjaman' milik satu 'Barang'. Ini berarti tabel 'peminjaman' memiliki 'barang_id' sebagai foreign key yang merujuk ke tabel 'barang'.
    }

    /**
     * Get the user that owns the peminjaman. // PHPDoc comment untuk relasi.
     */
    public function user() // Mendefinisikan relasi "belongsTo" (milik) dengan model 'User'.
    {
        return $this->belongsTo(User::class); // Menunjukkan bahwa setiap record 'Peminjaman' dicatat/diproses oleh satu 'User' (admin/petugas). Ini berarti tabel 'peminjaman' memiliki 'user_id' sebagai foreign key yang merujuk ke tabel 'users'.
    }

    /**
     * Get the kategori that owns the peminjaman. // PHPDoc comment untuk relasi.
     */
    public function kategori() // Mendefinisikan relasi "belongsTo" (milik) dengan model 'Kategori'.
    {
        return $this->belongsTo(Kategori::class); // Menunjukkan bahwa setiap record 'Peminjaman' bisa terkait dengan satu 'Kategori'. Ini berarti tabel 'peminjaman' memiliki 'kategori_id' sebagai foreign key yang merujuk ke tabel 'kategori'.
    }
}