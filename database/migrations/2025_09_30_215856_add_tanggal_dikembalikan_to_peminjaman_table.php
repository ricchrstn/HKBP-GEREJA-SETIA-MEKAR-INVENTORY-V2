<?php

use Illuminate\Database\Migrations\Migration; // Mengimpor kelas dasar 'Migration' yang digunakan untuk membuat migrasi database.
use Illuminate\Database\Schema\Blueprint; // Mengimpor kelas 'Blueprint' untuk mendefinisikan struktur kolom tabel.
use Illuminate\Support\Facades\Schema; // Mengimpor fasad 'Schema' untuk berinteraksi dengan skema database.

return new class extends Migration // Mendefinisikan sebuah kelas anonim yang merupakan turunan dari 'Migration'.
{
    public function up() // Metode 'up' akan dijalankan saat migrasi ini diaplikasikan (misalnya, dengan perintah 'php artisan migrate').
    {
        Schema::table('peminjaman', function (Blueprint $table) { // Mengubah (bukan membuat) tabel yang sudah ada, yaitu 'peminjaman'.
            $table->timestamp('tanggal_dikembalikan')->nullable()->after('tanggal_kembali'); // Menambahkan kolom baru bernama 'tanggal_dikembalikan' dengan tipe TIMESTAMP.
                                                                                              // '.nullable()' berarti kolom ini boleh kosong (NULL). Ini penting karena saat peminjaman baru dibuat, barang belum dikembalikan.
                                                                                              // '.after('tanggal_kembali')' berarti kolom ini akan ditempatkan setelah kolom 'tanggal_kembali' di struktur tabel.
        });
    }

    public function down() // Metode 'down' akan dijalankan saat migrasi ini di-rollback (misalnya, dengan perintah 'php artisan migrate:rollback').
    {
        Schema::table('peminjaman', function (Blueprint $table) { // Mengubah tabel 'peminjaman' untuk mengembalikan ke kondisi sebelumnya.
            $table->dropColumn('tanggal_dikembalikan'); // Menghapus kolom 'tanggal_dikembalikan' dari tabel 'peminjaman'.
        });
    }
};