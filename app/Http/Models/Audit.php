<?php
namespace App\Models; // Mendefinisikan namespace untuk kelas ini, menunjukkan bahwa ia berada di dalam direktori 'app/Models'.

use Illuminate\Database\Eloquent\Factories\HasFactory; // Mengimpor trait 'HasFactory' yang digunakan untuk membuat factory bagi model ini, berguna untuk seeding database atau pengujian.
use Illuminate\Database\Eloquent\Model; // Mengimpor kelas dasar 'Model' dari Eloquent, yang merupakan fondasi untuk semua model di Laravel.

class Audit extends Model // Mendefinisikan kelas 'Audit' yang merupakan turunan dari kelas 'Model' Eloquent.
{
    use HasFactory; // Menggunakan trait 'HasFactory' di dalam kelas 'Audit'.

    protected $table = 'audit'; // Secara eksplisit menentukan nama tabel di database yang akan dihubungkan dengan model ini adalah 'audit'. Meskipun Laravel akan secara otomatis mengasumsikan nama ini dari nama model, ini adalah praktik yang baik untuk kejelasan.

    protected $fillable = [ // Mendefinisikan kolom-kolom tabel yang boleh diisi (mass assignable) secara massal melalui metode seperti `create()` atau `update()`.
        'barang_id', // Foreign key yang mengacu pada ID barang yang diaudit.
        'user_id', // Foreign key yang mengacu pada ID user (auditor) yang melakukan audit.
        'tanggal_audit', // Tanggal kapan audit dilakukan.
        'kondisi', // Kondisi barang saat diaudit (misalnya, 'Baik', 'Rusak Ringan', 'Rusak Berat').
        'keterangan', // Informasi atau detail tambahan mengenai kondisi atau hasil audit.
        'status', // Status audit (misalnya, 'Selesai', 'Pending', 'Revisi').
        'foto', // Nama file atau path foto yang diambil saat audit.
    ];

    protected $casts = [ // Mendefinisikan bagaimana atribut tertentu harus di-cast (dikonversi) ke tipe data PHP tertentu saat diambil dari database.
        'tanggal_audit' => 'date', // Menginstruksikan Laravel untuk mengonversi kolom 'tanggal_audit' menjadi objek Carbon Date (tipe data tanggal/waktu PHP) saat diakses. Ini memudahkan manipulasi tanggal.
    ];

    public function barang() // Mendefinisikan relasi "belongsTo" (milik) dengan model 'Barang'.
    {
        return $this->belongsTo(Barang::class); // Menunjukkan bahwa setiap record 'Audit' milik satu 'Barang'. Ini berarti tabel 'audit' memiliki 'barang_id' sebagai foreign key yang merujuk ke tabel 'barang'.
    }

    public function user() // Mendefinisikan relasi "belongsTo" (milik) dengan model 'User'.
    {
        return $this->belongsTo(User::class); // Menunjukkan bahwa setiap record 'Audit' milik satu 'User'. Ini berarti tabel 'audit' memiliki 'user_id' sebagai foreign key yang merujuk ke tabel 'users'.
    }
}