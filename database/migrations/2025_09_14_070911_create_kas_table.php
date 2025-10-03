<?php

use Illuminate\Database\Migrations\Migration; // Mengimpor kelas dasar 'Migration' untuk membuat migrasi database.
use Illuminate\Database\Schema\Blueprint; // Mengimpor kelas 'Blueprint' untuk mendefinisikan struktur kolom tabel.
use Illuminate\Support\Facades\Schema; // Mengimpor fasad 'Schema' untuk berinteraksi dengan skema database.

return new class extends Migration // Mendefinisikan sebuah kelas anonim yang merupakan turunan dari 'Migration'.
{
    /**
     * Run the migrations.
     */
    public function up() // Metode 'up' akan dijalankan saat migrasi ini diaplikasikan (misalnya, dengan perintah 'php artisan migrate').
    {
        Schema::create('kas', function (Blueprint $table) { // Membuat tabel baru di database dengan nama 'kas'.
            $table->id(); // Menambahkan kolom 'id' sebagai primary key yang auto-incrementing (BIGINT UNSIGNED).
            $table->string('kode_transaksi')->unique(); // Menambahkan kolom 'kode_transaksi' dengan tipe string (VARCHAR) dan memastikan setiap nilai di kolom ini adalah unik.
            $table->enum('jenis', ['masuk', 'keluar']); // Menambahkan kolom 'jenis' dengan tipe ENUM, hanya dapat berisi 'masuk' atau 'keluar'.
            $table->decimal('jumlah', 15, 2); // Menambahkan kolom 'jumlah' dengan tipe DECIMAL. Total 15 digit, dengan 2 digit di belakang koma (misal: 1234567890123.45).
            $table->date('tanggal'); // Menambahkan kolom 'tanggal' dengan tipe data DATE.
            $table->string('keterangan'); // Menambahkan kolom 'keterangan' dengan tipe string (VARCHAR) untuk deskripsi transaksi.
            $table->string('sumber')->nullable(); // Menambahkan kolom 'sumber' dengan tipe string (VARCHAR). Digunakan untuk pemasukan (misal: 'persembahan', 'donasi'). Kolom ini boleh kosong ('nullable').
            $table->string('tujuan')->nullable(); // Menambahkan kolom 'tujuan' dengan tipe string (VARCHAR). Digunakan untuk pengeluaran (misal: 'pengadaan', 'operasional'). Kolom ini boleh kosong ('nullable').
            $table->string('bukti_transaksi')->nullable(); // Menambahkan kolom 'bukti_transaksi' dengan tipe string (VARCHAR). Bisa menyimpan path file atau nama file bukti transaksi. Kolom ini boleh kosong ('nullable').
            $table->foreignId('user_id')->constrained('users'); // Menambahkan kolom 'user_id' sebagai foreign key yang merujuk ke tabel 'users'.
            $table->timestamps(); // Menambahkan dua kolom TIMESTAMP: 'created_at' dan 'updated_at' untuk melacak waktu pembuatan dan pembaruan record.
            $table->softDeletes(); // Menambahkan kolom 'deleted_at' dengan tipe TIMESTAMP. Ini adalah fitur "soft delete" Laravel, di mana record tidak benar-benar dihapus dari database, melainkan ditandai sebagai terhapus.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void // Metode 'down' akan dijalankan saat migrasi ini di-rollback (misalnya, dengan perintah 'php artisan migrate:rollback').
    {
        Schema::dropIfExists('kas'); // Menghapus tabel 'kas' dari database, tetapi hanya jika tabel tersebut ada.
    }
};