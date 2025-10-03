<?php
namespace App\Models; // Mendefinisikan namespace untuk kelas ini, menunjukkan bahwa ia berada di dalam direktori 'app/Models'.

use Illuminate\Database\Eloquent\Factories\HasFactory; // Mengimpor trait 'HasFactory' yang digunakan untuk membuat factory bagi model ini, berguna untuk seeding database atau pengujian.
use Illuminate\Foundation\Auth\User as Authenticatable; // Mengimpor kelas dasar 'Authenticatable' dari Laravel, yang memberikan fungsionalitas otentikasi (login/logout).
use Illuminate\Notifications\Notifiable; // Mengimpor trait 'Notifiable' untuk memungkinkan pengiriman notifikasi ke user ini.

class User extends Authenticatable // Mendefinisikan kelas 'User' yang merupakan turunan dari kelas 'Authenticatable' Eloquent. Ini adalah model default untuk otentikasi di Laravel.
{
    use HasFactory, Notifiable; // Menggunakan trait 'HasFactory' dan 'Notifiable' di dalam kelas 'User'.

    // protected $table = 'users'; // Tidak ada definisi $table secara eksplisit. Laravel akan secara otomatis mengasumsikan nama tabel adalah 'users' (plural dari 'User').

    protected $fillable = [ // Mendefinisikan kolom-kolom tabel yang boleh diisi (mass assignable) secara massal melalui metode seperti `create()` atau `update()`.
        'name', // Nama lengkap user.
        'email', // Alamat email user (biasanya unik dan digunakan untuk login).
        'password', // Kata sandi user (akan di-hash).
        'role', // Peran atau hak akses user (misalnya, 'admin', 'pengurus', 'bendahara', 'user_biasa').
        'is_active', // Status apakah akun user aktif atau tidak (boolean).
        'phone', // Nomor telepon user.
        'address', // Alamat user.
    ];

    protected $hidden = [ // Mendefinisikan atribut yang harus disembunyikan dari representasi array atau JSON model.
        'password', // Kata sandi tidak boleh ditampilkan secara langsung.
        'remember_token', // Token "remember me" juga disembunyikan.
    ];

    protected $casts = [ // Mendefinisikan bagaimana atribut tertentu harus di-cast (dikonversi) ke tipe data PHP tertentu saat diambil dari database.
        'email_verified_at' => 'datetime', // Mengonversi 'email_verified_at' menjadi objek Carbon DateTime.
        'password' => 'hashed', // Laravel akan secara otomatis meng-hash password saat disimpan dan membandingkannya saat otentikasi.
        'is_active' => 'boolean', // Mengonversi 'is_active' menjadi tipe data boolean.
    ];

    /**
     * Relasi dengan BarangMasuk // PHPDoc comment untuk relasi.
     */
    public function barangMasuk() // Mendefinisikan relasi "one-to-many" (hasMany) dengan model 'BarangMasuk'.
    {
        return $this->hasMany(BarangMasuk::class); // Menunjukkan bahwa satu 'User' dapat mencatat banyak 'BarangMasuk'. Ini berarti tabel 'barang_masuk' memiliki foreign key 'user_id' yang merujuk kembali ke tabel 'users'.
    }

    /**
     * Relasi dengan BarangKeluar // PHPDoc comment untuk relasi.
     */
    public function barangKeluar() // Mendefinisikan relasi "one-to-many" (hasMany) dengan model 'BarangKeluar'.
    {
        return $this->hasMany(BarangKeluar::class); // Menunjukkan bahwa satu 'User' dapat mencatat banyak 'BarangKeluar'. Ini berarti tabel 'barang_keluar' memiliki foreign key 'user_id' yang merujuk kembali ke tabel 'users'.
    }

    /**
     * Cek apakah user adalah admin // PHPDoc comment untuk helper method.
     */
    public function isAdmin() // Metode helper untuk memeriksa peran user.
    {
        return $this->role === 'admin'; // Mengembalikan true jika peran user adalah 'admin'.
    }

    /**
     * Cek apakah user adalah pengurus // PHPDoc comment untuk helper method.
     */
    public function isPengurus() // Metode helper untuk memeriksa peran user.
    {
        return $this->role === 'pengurus'; // Mengembalikan true jika peran user adalah 'pengurus'.
    }

    /**
     * Cek apakah user adalah bendahara // PHPDoc comment untuk helper method.
     */
    public function isBendahara() // Metode helper untuk memeriksa peran user.
    {
        return $this->role === 'bendahara'; // Mengembalikan true jika peran user adalah 'bendahara'.
    }

    /**
     * Scope untuk mendapatkan user yang aktif // PHPDoc comment untuk scope lokal.
     */
    public function scopeActive($query) // Mendefinisikan scope lokal bernama 'active'.
    {
        return $query->where('is_active', true); // Mengembalikan kueri yang sudah ditambahkan kondisi WHERE is_active = true. Bisa digunakan seperti `User::active()->get()`.
    }
}