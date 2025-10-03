<?php

namespace App\Models; // Mendefinisikan namespace untuk kelas ini, menunjukkan bahwa ia berada di dalam direktori 'app/Models'.

use Illuminate\Database\Eloquent\Factories\HasFactory; // Mengimpor trait 'HasFactory' yang digunakan untuk membuat factory bagi model ini, berguna untuk seeding database atau pengujian.
use Illuminate\Database\Eloquent\Model; // Mengimpor kelas dasar 'Model' dari Eloquent, yang merupakan fondasi untuk semua model di Laravel.

class Pengajuan extends Model // Mendefinisikan kelas 'Pengajuan' yang merupakan turunan dari kelas 'Model' Eloquent.
{
    use HasFactory; // Menggunakan trait 'HasFactory' di dalam kelas 'Pengajuan'.

    protected $table = 'pengajuan'; // Secara eksplisit menentukan nama tabel di database yang akan dihubungkan dengan model ini adalah 'pengajuan'. Ini adalah praktik yang baik untuk kejelasan.

    protected $fillable = [ // Mendefinisikan kolom-kolom tabel yang boleh diisi (mass assignable) secara massal melalui metode seperti `create()` atau `update()`.
        'kode_pengajuan', // Kode unik untuk setiap pengajuan (misalnya, PNG20231026001).
        'nama_barang', // Nama barang yang diajukan.
        'spesifikasi', // Detail spesifikasi barang yang diajukan.
        'jumlah', // Jumlah barang yang diajukan.
        'satuan', // Satuan unit barang (misalnya, 'pcs', 'unit').
        'alasan', // Alasan pengajuan barang.
        'kebutuhan', // Tanggal kapan barang dibutuhkan (bisa berupa target tanggal).
        'user_id', // Foreign key yang mengacu pada ID user yang mengajukan.
        'status', // Status pengajuan (misalnya, 'Pending', 'Disetujui', 'Ditolak', 'Diproses').
        'keterangan', // Keterangan tambahan mengenai pengajuan.
        'file_pengajuan', // Nama file atau path dokumen pendukung pengajuan.
        // Tambahkan field untuk kriteria TOPSIS
        'urgensi', // K1 - Tingkat Urgensi Barang (Benefit) - Kolom ini menyimpan nilai urgensi untuk perhitungan TOPSIS.
        'ketersediaan_stok', // K2 - Ketersediaan Stok Barang (Cost) - Kolom ini menyimpan nilai ketersediaan stok untuk perhitungan TOPSIS.
        'ketersediaan_dana', // K3 - Ketersediaan Dana Pengadaan (Benefit) - Kolom ini menyimpan nilai ketersediaan dana untuk perhitungan TOPSIS.
    ];

    protected $casts = [ // Mendefinisikan bagaimana atribut tertentu harus di-cast (dikonversi) ke tipe data PHP tertentu saat diambil dari database.
        'kebutuhan' => 'date', // Mengonversi 'kebutuhan' menjadi objek Carbon Date.
    ];

    public function user() // Mendefinisikan relasi "belongsTo" (milik) dengan model 'User'.
    {
        return $this->belongsTo(User::class); // Menunjukkan bahwa setiap record 'Pengajuan' diajukan oleh satu 'User'. Ini berarti tabel 'pengajuan' memiliki 'user_id' sebagai foreign key yang merujuk ke tabel 'users'.
    }

    public function analisisTopsis() // Mendefinisikan relasi "one-to-one" (hasOne) dengan model 'AnalisisTopsis'.
    {
        return $this->hasOne(AnalisisTopsis::class); // Menunjukkan bahwa satu 'Pengajuan' bisa memiliki satu hasil 'AnalisisTopsis' terkait. Ini berarti tabel 'analisis_topsis' memiliki foreign key 'pengajuan_id' yang merujuk kembali ke tabel 'pengajuan'.
    }

    public function nilaiPengadaanKriterias() // Mendefinisikan relasi "one-to-many" (hasMany) dengan model 'NilaiPengadaanKriteria'.
    {
        return $this->hasMany(NilaiPengadaanKriteria::class); // Menunjukkan bahwa satu 'Pengajuan' bisa memiliki banyak 'NilaiPengadaanKriteria' (satu untuk setiap kriteria evaluasi). Ini berarti tabel 'nilai_pengadaan_kriterias' memiliki foreign key 'pengajuan_id' yang merujuk kembali ke tabel 'pengajuan'.
    }

    // Generate kode pengajuan otomatis
    public static function generateKode() // Mendefinisikan metode statis untuk menghasilkan kode pengajuan otomatis.
    {
        $prefix = 'PNG'; // Prefix untuk kode pengajuan.
        $date = now()->format('Ymd'); // Mengambil tanggal hari ini dalam format YYYYMMDD.
        $last = self::whereDate('created_at', now())->count(); // Menghitung berapa banyak pengajuan yang dibuat pada hari ini.
        $number = str_pad($last + 1, 3, '0', STR_PAD_LEFT); // Menambahkan 1 ke hitungan terakhir dan memformatnya menjadi 3 digit dengan nol di depan (misal: 001, 010).
        return $prefix . $date . $number; // Menggabungkan prefix, tanggal, dan nomor urut menjadi kode pengajuan lengkap.
    }

    /**
     * Get the kategori that owns the pengajuan. // PHPDoc comment untuk relasi.
     */
    public function kategori() // Mendefinisikan relasi "belongsTo" (milik) dengan model 'Kategori'.
    {
        // Catatan: Jika tidak ada 'kategori_id' di tabel 'pengajuan' atau di `$fillable`, relasi ini mungkin tidak berfungsi dengan baik tanpa modifikasi.
        // Asumsi: Ada kolom `kategori_id` di tabel 'pengajuan'.
        return $this->belongsTo(Kategori::class); // Menunjukkan bahwa setiap record 'Pengajuan' bisa terkait dengan satu 'Kategori'. Ini berarti tabel 'pengajuan' memiliki 'kategori_id' sebagai foreign key yang merujuk ke tabel 'kategori'.
    }
}