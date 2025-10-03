<?php

use Illuminate\Database\Migrations\Migration; // Mengimpor kelas dasar Migration.
use Illuminate\Database\Schema\Blueprint; // Mengimpor kelas Blueprint untuk mendefinisikan struktur tabel.
use Illuminate\Support\Facades\Schema; // Mengimpor Facade Schema untuk berinteraksi dengan database.

return new class extends Migration // Mendefinisikan kelas migrasi anonim yang mewarisi dari Migration.
{
    /**
     * Run the migrations. // PHPDoc comment untuk metode up().
     *
     * @return void
     */
    public function up(): void // Metode `up()` dijalankan ketika migrasi diterapkan.
    {
        Schema::create('barang_keluar', function (Blueprint $table) { // Membuat tabel baru bernama 'barang_keluar'.
            $table->id(); // Membuat kolom 'id' sebagai primary key auto-incrementing.
            $table->foreignId('barang_id')->constrained('barang'); // Membuat kolom 'barang_id' (BIGINT UNSIGNED) sebagai foreign key.
                                                                // `->constrained('barang')` secara otomatis membuat batasan foreign key
                                                                // yang merujuk ke kolom 'id' di tabel 'barang'.
            $table->dateTime('tanggal'); // Membuat kolom 'tanggal' dengan tipe data DATETIME untuk mencatat tanggal dan waktu barang keluar.
            $table->integer('jumlah'); // Membuat kolom 'jumlah' (INTEGER) untuk mencatat kuantitas barang yang keluar.
            $table->text('keterangan'); // Membuat kolom 'keterangan' (TEXT) untuk detail atau catatan tambahan mengenai tujuan, penerima, dll.
            $table->foreignId('user_id')->constrained('users'); // Membuat kolom 'user_id' (BIGINT UNSIGNED) sebagai foreign key.
                                                              // `->constrained('users')` secara otomatis membuat batasan foreign key
                                                              // yang merujuk ke kolom 'id' di tabel 'users' (user yang mencatat transaksi keluar).
            $table->timestamps(); // Menambahkan kolom 'created_at' dan 'updated_at' (TIMESTAMP).
        });
    }

    /**
     * Reverse the migrations. // PHPDoc comment untuk metode down().
     *
     * @return void
     */
    public function down(): void // Metode `down()` dijalankan ketika migrasi di-rollback.
    {
        Schema::dropIfExists('barang_keluar'); // Menghapus tabel 'barang_keluar' jika ada.
    }
};