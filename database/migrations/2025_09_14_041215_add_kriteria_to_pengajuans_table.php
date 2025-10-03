<?php

use Illuminate\Database\Migrations\Migration; // Mengimpor kelas dasar 'Migration' yang digunakan untuk membuat migrasi database.
use Illuminate\Database\Schema\Blueprint; // Mengimpor kelas 'Blueprint' untuk mendefinisikan struktur kolom tabel.
use Illuminate\Support\Facades\Schema; // Mengimpor fasad 'Schema' untuk berinteraksi dengan skema database.

return new class extends Migration // Mendefinisikan sebuah kelas anonim yang merupakan turunan dari 'Migration'.
{
    public function up() // Metode 'up' akan dijalankan saat migrasi ini diaplikasikan (misalnya, dengan perintah 'php artisan migrate').
    {
        Schema::table('pengajuan', function (Blueprint $table) { // Mengubah (bukan membuat) tabel yang sudah ada, yaitu 'pengajuan'.
            $table->integer('urgensi')->nullable()->after('file_pengajuan'); // Menambahkan kolom baru bernama 'urgensi' dengan tipe integer. Kolom ini boleh kosong ('nullable') dan akan ditempatkan setelah kolom 'file_pengajuan'.
            $table->integer('ketersediaan_stok')->nullable()->after('urgensi'); // Menambahkan kolom baru bernama 'ketersediaan_stok' dengan tipe integer. Kolom ini boleh kosong ('nullable') dan akan ditempatkan setelah kolom 'urgensi'.
            $table->integer('ketersediaan_dana')->nullable()->after('ketersediaan_stok'); // Menambahkan kolom baru bernama 'ketersediaan_dana' dengan tipe integer. Kolom ini boleh kosong ('nullable') dan akan ditempatkan setelah kolom 'ketersediaan_stok'.
        });
    }

    public function down() // Metode 'down' akan dijalankan saat migrasi ini di-rollback (misalnya, dengan perintah 'php artisan migrate:rollback').
    {
        Schema::table('pengajuan', function (Blueprint $table) { // Mengubah tabel 'pengajuan' untuk mengembalikan ke kondisi sebelumnya.
            $table->dropColumn(['urgensi', 'ketersediaan_stok', 'ketersediaan_dana']); // Menghapus kolom-kolom 'urgensi', 'ketersediaan_stok', dan 'ketersediaan_dana' dari tabel 'pengajuan'.
        });
    }
};