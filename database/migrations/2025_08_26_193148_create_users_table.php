<?php

use Illuminate\Database\Migrations\Migration; // Mengimpor kelas dasar Migration, yang harus diwarisi oleh setiap file migrasi.
use Illuminate\Database\Schema\Blueprint; // Mengimpor kelas Blueprint, yang menyediakan metode untuk memanipulasi skema tabel.
use Illuminate\Support\Facades\Schema; // Mengimpor Facade Schema, yang digunakan untuk berinteraksi dengan skema database.

return new class extends Migration // Mendefinisikan kelas anonim yang mewarisi dari Migration. Ini adalah cara standar Laravel untuk menulis migrasi.
{
    /**
     * Run the migrations. // PHPDoc comment yang menjelaskan metode up().
     *
     * @return void
     */
    public function up(): void // Metode `up()` dijalankan ketika migrasi diterapkan (misalnya, `php artisan migrate`). Di sinilah Anda mendefinisikan perubahan pada skema database.
    {
        Schema::create('users', function (Blueprint $table) { // Menggunakan Facade Schema untuk membuat tabel baru bernama 'users'.
                                                          // Callback function menerima instance Blueprint ($table) yang memungkinkan Anda mendefinisikan kolom.
            $table->id(); // Membuat kolom 'id' yang merupakan primary key auto-incrementing (BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY).
            $table->string('name'); // Membuat kolom 'name' dengan tipe data VARCHAR.
            $table->string('email')->unique(); // Membuat kolom 'email' dengan tipe data VARCHAR dan memastikan nilainya unik di seluruh tabel.
            $table->timestamp('email_verified_at')->nullable(); // Membuat kolom 'email_verified_at' dengan tipe data TIMESTAMP. `nullable()` berarti kolom ini bisa kosong.
            $table->string('password'); // Membuat kolom 'password' dengan tipe data VARCHAR.
            $table->enum('role', ['admin', 'pengurus', 'bendahara'])->default('pengurus'); // Membuat kolom 'role' dengan tipe ENUM yang hanya menerima nilai 'admin', 'pengurus', atau 'bendahara'. `default('pengurus')` menetapkan nilai default jika tidak ada yang diberikan.
            $table->boolean('is_active')->default(true); // Membuat kolom 'is_active' dengan tipe BOOLEAN. `default(true)` menetapkan nilai default true.
            $table->string('phone')->nullable(); // Membuat kolom 'phone' dengan tipe VARCHAR. `nullable()` berarti kolom ini bisa kosong.
            $table->text('address')->nullable(); // Membuat kolom 'address' dengan tipe TEXT. `nullable()` berarti kolom ini bisa kosong.
            $table->rememberToken(); // Membuat kolom 'remember_token' (VARCHAR(100) NULL) yang digunakan untuk fitur "remember me" saat login.
            $table->timestamps(); // Membuat dua kolom TIMESTAMP secara otomatis: `created_at` dan `updated_at`, yang akan otomatis diisi oleh Eloquent.
        });
    }

    /**
     * Reverse the migrations. // PHPDoc comment yang menjelaskan metode down().
     *
     * @return void
     */
    public function down(): void // Metode `down()` dijalankan ketika migrasi di-rollback (misalnya, `php artisan migrate:rollback`). Di sinilah Anda membalikkan perubahan yang dilakukan di metode `up()`.
    {
        Schema::dropIfExists('users'); // Menghapus tabel 'users' jika tabel tersebut ada di database. Ini adalah tindakan kebalikan dari `Schema::create()`.
    }
};