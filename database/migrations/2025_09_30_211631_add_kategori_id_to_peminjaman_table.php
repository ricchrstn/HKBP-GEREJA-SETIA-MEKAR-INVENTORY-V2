<?php

use Illuminate\Database\Migrations\Migration; // Mengimpor kelas dasar 'Migration' yang digunakan untuk membuat migrasi database.
use Illuminate\Database\Schema\Blueprint; // Mengimpor kelas 'Blueprint' untuk mendefinisikan struktur kolom tabel.
use Illuminate\Support\Facades\Schema; // Mengimpor fasad 'Schema' untuk berinteraksi dengan skema database.

return new class extends Migration // Mendefinisikan sebuah kelas anonim yang merupakan turunan dari 'Migration'.
{
    /**
     * Run the migrations.
     */
    public function up(): void // Metode 'up' akan dijalankan saat migrasi ini diaplikasikan (misalnya, dengan perintah 'php artisan migrate').
    {
        Schema::table('peminjaman', function (Blueprint $table) { // Mengubah (bukan membuat) tabel yang sudah ada, yaitu 'peminjaman'.
            // Tambahkan kolom kategori_id sebagai foreign key
            $table->foreignId('kategori_id')->nullable()->constrained('kategori')->onDelete('set null'); // Menambahkan kolom baru bernama 'kategori_id' sebagai foreign key.
                                                                                                      // '.nullable()' berarti kolom ini boleh kosong (NULL).
                                                                                                      // '.constrained('kategori')' berarti ini merujuk ke tabel 'kategori' (kolom 'id' secara default).
                                                                                                      // '.onDelete('set null')' berarti jika record kategori yang terkait dihapus, nilai 'kategori_id' di tabel 'peminjaman' akan diatur menjadi NULL (bukan menghapus record peminjaman).
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void // Metode 'down' akan dijalankan saat migrasi ini di-rollback (misalnya, dengan perintah 'php artisan migrate:rollback').
    {
        Schema::table('peminjaman', function (Blueprint $table) { // Mengubah tabel 'peminjaman' untuk mengembalikan ke kondisi sebelumnya.
            $table->dropForeign(['kategori_id']); // Menghapus batasan foreign key yang bernama 'peminjaman_kategori_id_foreign' (nama default yang dibuat Laravel).
            $table->dropColumn('kategori_id'); // Menghapus kolom 'kategori_id' dari tabel 'peminjaman'.
        });
    }
};