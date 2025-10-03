<?php

namespace App\Models; // Mendefinisikan namespace untuk kelas ini, menunjukkan lokasinya di dalam struktur direktori aplikasi (App/Models).

use Illuminate\Database\Eloquent\Factories\HasFactory; // Mengimpor trait HasFactory untuk memungkinkan pembuatan instance model dengan factory (berguna untuk seeding atau pengujian).
use Illuminate\Database\Eloquent\Model; // Mengimpor kelas dasar Model dari Eloquent, yang merupakan fondasi untuk semua model di Laravel.
use Illuminate\Database\Eloquent\SoftDeletes; // Mengimpor trait SoftDeletes, yang memungkinkan fitur "soft delete" pada model ini.

class Barang extends Model // Mendefinisikan kelas 'Barang' yang mewarisi semua fungsionalitas dari kelas 'Model' Eloquent.
{
    use HasFactory, SoftDeletes; // Menggunakan trait HasFactory (untuk factory) dan SoftDeletes (untuk soft delete) di dalam kelas ini.

    protected $table = 'barang'; // Secara eksplisit menentukan nama tabel di database yang akan dihubungkan dengan model ini adalah 'barang'. Ini adalah praktik yang baik untuk kejelasan.

    protected $fillable = [ // Ini adalah array yang mendefinisikan kolom-kolom tabel yang boleh diisi (mass assignable) secara massal melalui metode create atau update Eloquent.
        'kode_barang', // Kolom untuk menyimpan kode unik barang.
        'nama', // Kolom untuk menyimpan nama barang.
        'deskripsi', // Kolom untuk menyimpan deskripsi atau keterangan barang.
        'kategori_id', // Foreign key yang mengacu pada ID kategori barang.
        'satuan', // Kolom untuk menyimpan satuan unit barang (misalnya, 'pcs', 'kg', 'liter').
        'stok', // Kolom untuk menyimpan jumlah stok barang yang tersedia.
        'status', // Kolom untuk menyimpan status barang (misalnya, 'Tersedia', 'Tidak Tersedia', 'Rusak').
        'harga', // Kolom untuk menyimpan harga barang.
        'gambar' // Kolom untuk menyimpan nama file atau path gambar barang.
    ];

    protected $casts = [ // Ini adalah array yang mendefinisikan bagaimana atribut tertentu harus di-cast ke tipe data tertentu saat diambil dari database atau disimpan.
        'harga' => 'decimal:2', // Menginstruksikan Laravel untuk mengonversi nilai 'harga' menjadi tipe data desimal dengan 2 angka di belakang koma.
        'stok' => 'integer' // Menginstruksikan Laravel untuk mengonversi nilai 'stok' menjadi tipe data integer.
    ];

    /**
     * Get the kategori that owns the barang.
     */
    public function kategori() // Mendefinisikan relasi antar model. Ini adalah metode yang mendefinisikan relasi "many-to-one" (belongsTo).
    {
        return $this->belongsTo(Kategori::class, 'kategori_id'); // Mengatakan bahwa setiap instance Barang 'milik' satu instance Kategori. 'Kategori::class' merujuk pada nama kelas model Kategori. 'kategori_id' adalah nama kolom foreign key di tabel 'barang'.
    }

    public function barangMasuk() // Mendefinisikan relasi "one-to-many" (hasMany) dengan model 'BarangMasuk'.
    {
        return $this->hasMany(BarangMasuk::class); // Mengatakan bahwa satu instance Barang bisa memiliki banyak instance BarangMasuk. Ini berarti tabel 'barang_masuk' memiliki foreign key 'barang_id' yang merujuk ke tabel 'barang'.
    }

    public function barangKeluar() // Mendefinisikan relasi "one-to-many" (hasMany) dengan model 'BarangKeluar'.
    {
        return $this->hasMany(BarangKeluar::class); // Mengatakan bahwa satu instance Barang bisa memiliki banyak instance BarangKeluar. Ini berarti tabel 'barang_keluar' memiliki foreign key 'barang_id' yang merujuk ke tabel 'barang'.
    }

    public function jadwalAudits() // Mendefinisikan relasi "one-to-many" (hasMany) dengan model 'JadwalAudit'.
    {
        return $this->hasMany(JadwalAudit::class); // Mengatakan bahwa satu instance Barang bisa memiliki banyak instance JadwalAudit. Ini berarti tabel 'jadwal_audits' memiliki foreign key 'barang_id' yang merujuk ke tabel 'barang'.
    }
}