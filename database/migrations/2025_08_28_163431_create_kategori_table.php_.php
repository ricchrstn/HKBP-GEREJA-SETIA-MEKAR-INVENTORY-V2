<?php

use Illuminate\Database\Migrations\Migration; // Mengimpor kelas dasar Migration, yang harus diwarisi oleh setiap file migrasi.
use Illuminate\Database\Schema\Blueprint; // Mengimpor kelas Blueprint, yang menyediakan metode untuk memanipulasi skema tabel database.
use Illuminate\Support\Facades\Schema; // Mengimpor Facade Schema, yang digunakan untuk berinteraksi dengan skema database (misalnya, membuat, mengubah, menghapus tabel).

return new class extends Migration // Ini adalah definisi kelas migrasi anonim. Laravel menggunakan sintaks ini sejak versi PHP 7.4+. Kelas ini mewarisi semua fungsionalitas dari kelas `Migration`.
{
    /**
     * Run the migrations. // PHPDoc comment yang menjelaskan tujuan dari metode `up()`.
     *
     * @return void
     */
    public function up(): void // Metode `up()` adalah metode yang akan dijalankan ketika Anda menjalankan perintah `php artisan migrate`.
                               // Di sinilah Anda mendefinisikan perubahan yang ingin Anda terapkan pada skema database Anda, seperti membuat tabel baru atau menambahkan kolom.
    {
        Schema::create('kategori', function (Blueprint $table) { // Menggunakan Facade `Schema` untuk membuat tabel baru di database.
                                                          // Argumen pertama adalah nama tabel (`'kategori'`).
                                                          // Argumen kedua adalah sebuah callback function yang menerima objek `Blueprint` (`$table`).
                                                          // Objek `$table` ini memungkinkan Anda untuk mendefinisikan kolom-kolom tabel.
            $table->id(); // Ini adalah shorthand (singkatan) untuk `$table->bigIncrements('id')`.
                          // Ini akan membuat kolom `id` sebagai primary key, auto-incrementing, dan tipe data BIGINT UNSIGNED.
            $table->string('nama')->unique(); // Membuat kolom `nama` dengan tipe data VARCHAR (string) dan panjang default 255 karakter.
                                            // `->unique()` menambahkan batasan (constraint) unik pada kolom ini, memastikan tidak ada dua kategori yang memiliki nama yang sama.
            $table->text('deskripsi')->nullable(); // Membuat kolom `deskripsi` dengan tipe data TEXT, yang dapat menampung teks yang lebih panjang.
                                                  // `->nullable()` berarti kolom ini opsional dan bisa berisi nilai NULL.
            $table->timestamps(); // Ini adalah shorthand untuk membuat dua kolom TIMESTAMP: `created_at` dan `updated_at`.
                                  // Laravel secara otomatis akan mengisi kolom-kolom ini dengan timestamp saat record dibuat dan diperbarui.
        });
    }

    /**
     * Reverse the migrations. // PHPDoc comment yang menjelaskan tujuan dari metode `down()`.
     *
     * @return void
     */
    public function down(): void // Metode `down()` adalah metode yang akan dijalankan ketika Anda menjalankan perintah `php artisan migrate:rollback`.
                               // Di sinilah Anda mendefinisikan cara untuk membalikkan perubahan yang dilakukan di metode `up()`.
    {
        Schema::dropIfExists('kategori'); // Menggunakan Facade `Schema` untuk menghapus tabel `kategori`.
                                          // `dropIfExists` memastikan bahwa tabel hanya dihapus jika tabel tersebut memang ada, menghindari error jika tabel sudah tidak ada.
    }
};