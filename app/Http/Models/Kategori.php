<?php

namespace App\Models; // Mendefinisikan namespace untuk kelas ini, menunjukkan bahwa ia berada di dalam direktori 'app/Models'.

use Illuminate\Database\Eloquent\Factories\HasFactory; // Mengimpor trait 'HasFactory' yang digunakan untuk membuat factory bagi model ini, berguna untuk seeding database atau pengujian.
use Illuminate\Database\Eloquent\Model; // Mengimpor kelas dasar 'Model' dari Eloquent, yang merupakan fondasi untuk semua model di Laravel.

class Kategori extends Model // Mendefinisikan kelas 'Kategori' yang merupakan turunan dari kelas 'Model' Eloquent.
{
    use HasFactory; // Menggunakan trait 'HasFactory' di dalam kelas 'Kategori'.

    protected $table = 'kategori'; // Secara eksplisit menentukan nama tabel di database yang akan dihubungkan dengan model ini adalah 'kategori'. Ini adalah praktik yang baik untuk kejelasan.

    protected $fillable = ['nama', 'deskripsi']; // Mendefinisikan kolom-kolom tabel yang boleh diisi (mass assignable) secara massal melalui metode seperti `create()` atau `update()`.
                                              // 'nama': Kolom untuk menyimpan nama kategori (misalnya, 'Elektronik', 'Pakaian', 'Alat Tulis').
                                              // 'deskripsi': Kolom untuk menyimpan deskripsi atau keterangan tambahan mengenai kategori.

    /**
     * Get the barangs for the kategori. // Ini adalah PHPDoc comment yang menjelaskan fungsi di bawahnya.
     */
    public function barangs() // Mendefinisikan relasi "one-to-many" (hasMany) dengan model 'Barang'.
    {
        return $this->hasMany(Barang::class, 'kategori_id'); // Menunjukkan bahwa satu instance 'Kategori' bisa memiliki banyak instance 'Barang'.
                                                            // 'Barang::class' merujuk pada nama kelas model Barang.
                                                            // 'kategori_id' adalah nama foreign key di tabel 'barang' yang merujuk kembali ke tabel 'kategori'.
    }
}