<?php

namespace App\Models; // Mendefinisikan namespace untuk kelas ini, menunjukkan bahwa ia berada di dalam direktori 'app/Models'.

use Illuminate\Database\Eloquent\Factories\HasFactory; // Mengimpor trait 'HasFactory' yang digunakan untuk membuat factory bagi model ini, berguna untuk seeding database atau pengujian.
use Illuminate\Database\Eloquent\Model; // Mengimpor kelas dasar 'Model' dari Eloquent, yang merupakan fondasi untuk semua model di Laravel.

class Kas extends Model // Mendefinisikan kelas 'Kas' yang merupakan turunan dari kelas 'Model' Eloquent.
{
    use HasFactory; // Menggunakan trait 'HasFactory' di dalam kelas 'Kas'.

    // protected $table = 'kas'; // Tidak ada definisi $table secara eksplisit. Laravel akan secara otomatis mengasumsikan nama tabel adalah 'kas' (plural dari 'Kas').

    protected $fillable = [ // Mendefinisikan kolom-kolom tabel yang boleh diisi (mass assignable) secara massal melalui metode seperti `create()` atau `update()`.
        'user_id', // Foreign key yang mengacu pada ID user yang mencatat transaksi kas.
        'kode_transaksi', // Kode unik untuk setiap transaksi kas (misalnya, KM202310260001).
        'jenis', // Jenis transaksi (misalnya, 'masuk' atau 'keluar').
        'jumlah', // Jumlah uang dalam transaksi.
        'tanggal', // Tanggal transaksi kas terjadi.
        'keterangan', // Deskripsi atau keterangan tambahan mengenai transaksi.
        'sumber', // Sumber uang masuk (jika jenis 'masuk').
        'tujuan', // Tujuan pengeluaran uang (jika jenis 'keluar').
        'bukti_transaksi' // Nama file atau path bukti transaksi (misalnya, foto struk).
    ];

    protected $casts = [ // Mendefinisikan bagaimana atribut tertentu harus di-cast (dikonversi) ke tipe data PHP tertentu saat diambil dari database.
        'tanggal' => 'date', // Menginstruksikan Laravel untuk mengonversi kolom 'tanggal' menjadi objek Carbon Date (tipe data tanggal/waktu PHP) saat diakses. Ini memudahkan manipulasi tanggal.
        'jumlah' => 'decimal:2' // Menginstruksikan Laravel untuk mengonversi nilai 'jumlah' menjadi tipe data desimal dengan 2 angka di belakang koma. Penting untuk presisi keuangan.
    ];

    public function user() // Mendefinisikan relasi "belongsTo" (milik) dengan model 'User'.
    {
        return $this->belongsTo(User::class); // Menunjukkan bahwa setiap record 'Kas' dicatat oleh satu 'User'. Ini berarti tabel 'kas' memiliki 'user_id' sebagai foreign key yang merujuk ke tabel 'users'.
    }

    public function scopeMasuk($query) // Mendefinisikan scope lokal bernama 'masuk'. Scope ini adalah cara mudah untuk membungkus logika kueri yang umum digunakan.
    {
        return $query->where('jenis', 'masuk'); // Mengembalikan kueri yang sudah ditambahkan kondisi WHERE jenis = 'masuk'. Bisa digunakan seperti `Kas::masuk()->get()`.
    }

    public function scopeKeluar($query) // Mendefinisikan scope lokal bernama 'keluar'.
    {
        return $query->where('jenis', 'keluar'); // Mengembalikan kueri yang sudah ditambahkan kondisi WHERE jenis = 'keluar'. Bisa digunakan seperti `Kas::keluar()->get()`.
    }

    public static function generateKode($jenis) // Mendefinisikan metode statis untuk menghasilkan kode transaksi unik. Ini bisa dipanggil langsung dari kelas, contoh: `Kas::generateKode('masuk')`.
    {
        $prefix = $jenis == 'masuk' ? 'KM' : 'KK'; // Menentukan prefix kode: 'KM' untuk Kas Masuk, 'KK' untuk Kas Keluar.
        $date = now()->format('Ymd'); // Mengambil tanggal hari ini dalam format YYYYMMDD.

        // Mencari transaksi terakhir dari jenis yang sama yang dibuat pada hari yang sama.
        $last = self::where('jenis', $jenis)
            ->whereDate('created_at', now()) // Mencari berdasarkan tanggal pembuatan di kolom created_at.
            ->orderBy('id', 'desc') // Mengurutkan dari ID terbesar untuk mendapatkan yang terbaru.
            ->first(); // Mengambil satu record pertama.

        if ($last) { // Jika ada transaksi sebelumnya pada hari yang sama...
            $lastNumber = (int) substr($last->kode_transaksi, -4); // Mengambil 4 digit terakhir dari kode transaksi terakhir dan mengonversinya ke integer.
            $newNumber = $lastNumber + 1; // Menambahkan 1 untuk mendapatkan nomor baru.
        } else { // Jika belum ada transaksi dari jenis ini pada hari ini...
            $newNumber = 1; // Memulai nomor dengan 1.
        }

        // Menggabungkan prefix, tanggal, dan nomor urut yang sudah di-padding menjadi kode transaksi lengkap.
        return $prefix . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT); // str_pad menambahkan nol di depan sehingga nomor selalu 4 digit (misal: 0001, 0010).
    }

    public static function getSaldo() // Mendefinisikan metode statis untuk menghitung saldo kas saat ini.
    {
        $totalMasuk = self::masuk()->sum('jumlah'); // Menggunakan scope 'masuk' dan menjumlahkan semua 'jumlah' transaksi masuk.
        $totalKeluar = self::keluar()->sum('jumlah'); // Menggunakan scope 'keluar' dan menjumlahkan semua 'jumlah' transaksi keluar.
        return $totalMasuk - $totalKeluar; // Menghitung saldo dengan mengurangi total keluar dari total masuk.
    }
}