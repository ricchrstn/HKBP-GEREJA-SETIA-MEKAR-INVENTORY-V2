<?php

use Illuminate\Database\Migrations\Migration; // Mengimpor kelas dasar 'Migration' yang digunakan untuk membuat migrasi database.
use Illuminate\Database\Schema\Blueprint; // Mengimpor kelas 'Blueprint' untuk mendefinisikan struktur kolom tabel.
use Illuminate\Support\Facades\Schema; // Mengimpor fasad 'Schema' untuk berinteraksi dengan skema database (misalnya, membuat, mengubah, menghapus tabel).

return new class extends Migration // Mendefinisikan sebuah kelas anonim yang merupakan turunan dari 'Migration'.
{
    /**
     * Run the migrations.
     */
    public function up() // Metode 'up' akan dijalankan saat migrasi ini diaplikasikan (misalnya, dengan perintah 'php artisan migrate').
    {
        Schema::create('kriterias', function (Blueprint $table) { // Membuat tabel baru di database dengan nama 'kriterias'.
            $table->id(); // Menambahkan kolom 'id' sebagai primary key yang auto-incrementing (BIGINT UNSIGNED).
            $table->string('nama'); // Menambahkan kolom 'nama' dengan tipe string (VARCHAR).
            $table->float('bobot'); // Menambahkan kolom 'bobot' dengan tipe float (FLOAT), yang bisa menyimpan angka desimal.
            $table->enum('tipe', ['benefit', 'cost']); // Menambahkan kolom 'tipe' dengan tipe ENUM, yang hanya dapat berisi salah satu dari nilai yang ditentukan ('benefit' atau 'cost').
            $table->timestamps(); // Menambahkan dua kolom TIMESTAMP: 'created_at' dan 'updated_at'. Laravel secara otomatis mengelola tanggal dan waktu pembuatan serta pembaruan record.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() // Metode 'down' akan dijalankan saat migrasi ini di-rollback (misalnya, dengan perintah 'php artisan migrate:rollback').
    {
        Schema::dropIfExists('kriterias'); // Menghapus tabel 'kriterias' dari database, tetapi hanya jika tabel tersebut ada.
    }
};