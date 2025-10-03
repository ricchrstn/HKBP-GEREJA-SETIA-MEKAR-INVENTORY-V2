<?php

use Illuminate\Database\Migrations\Migration; // Mengimpor kelas Migration yang merupakan dasar untuk membuat migrasi database.
use Illuminate\Database\Schema\Blueprint; // Mengimpor kelas Blueprint untuk mendefinisikan struktur tabel.
use Illuminate\Support\Facades\Schema; // Mengimpor fasad Schema untuk berinteraksi dengan skema database.

return new class extends Migration // Mendefinisikan sebuah kelas anonim yang merupakan turunan dari Migration.
{
    /**
     * Run the migrations.
     */
    public function up(): void // Metode `up` dijalankan ketika migrasi ini diterapkan (misalnya, saat `php artisan migrate`).
    {
        Schema::create('peminjaman', function (Blueprint $table) { // Membuat tabel baru bernama 'peminjaman'.
            $table->id(); // Membuat kolom 'id' sebagai primary key auto-incrementing (BIGINT UNSIGNED AUTO_INCREMENT).
            $table->foreignId('barang_id')->constrained('barang')->onDelete('cascade'); // Membuat kolom 'barang_id' sebagai foreign key yang merujuk ke tabel 'barang' dan akan menghapus peminjaman jika barang dihapus.
            $table->date('tanggal_pinjam'); // Membuat kolom 'tanggal_pinjam' dengan tipe data DATE.
            $table->date('tanggal_kembali'); // Membuat kolom 'tanggal_kembali' dengan tipe data DATE.
            $table->integer('jumlah'); // Membuat kolom 'jumlah' dengan tipe data INTEGER.
            $table->string('peminjam'); // Membuat kolom 'peminjam' dengan tipe data VARCHAR (string).
            $table->string('kontak'); // Membuat kolom 'kontak' dengan tipe data VARCHAR (string).
            $table->text('keperluan'); // Membuat kolom 'keperluan' dengan tipe data TEXT.
            $table->text('keterangan')->nullable(); // Membuat kolom 'keterangan' dengan tipe data TEXT dan mengizinkan nilai NULL.
            $table->enum('status', ['dipinjam', 'dikembalikan', 'terlambat'])->default('dipinjam'); // Membuat kolom 'status' dengan tipe data ENUM (hanya bisa salah satu dari nilai yang ditentukan) dan nilai default 'dipinjam'.
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Membuat kolom 'user_id' sebagai foreign key yang merujuk ke tabel 'users' dan akan menghapus peminjaman jika user dihapus.
            $table->timestamps(); // Membuat dua kolom: 'created_at' dan 'updated_at' dengan tipe data TIMESTAMP. Ini secara otomatis mengelola waktu pembuatan dan pembaruan record.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void // Metode `down` dijalankan ketika migrasi ini di-rollback (misalnya, saat `php artisan migrate:rollback`).
    {
        Schema::dropIfExists('peminjaman'); // Menghapus tabel 'peminjaman' jika tabel tersebut ada.
    }
};