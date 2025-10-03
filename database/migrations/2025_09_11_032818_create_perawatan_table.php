<?php

use Illuminate\Database\Migrations\Migration; // Mengimpor kelas dasar untuk migrasi database.
use Illuminate\Database\Schema\Blueprint; // Mengimpor kelas Blueprint untuk membangun struktur tabel.
use Illuminate\Support\Facades\Schema; // Mengimpor fasad Schema untuk berinteraksi dengan database.

return new class extends Migration // Mendefinisikan kelas migrasi anonim.
{
    /**
     * Run the migrations.
     */
    public function up(): void // Metode 'up' akan dijalankan saat migrasi diterapkan (misalnya, 'php artisan migrate').
    {
        Schema::create('perawatan', function (Blueprint $table) { // Membuat tabel baru di database dengan nama 'perawatan'.
            $table->id(); // Menambahkan kolom 'id' sebagai primary key (auto-incrementing, BIGINT UNSIGNED).
            $table->foreignId('barang_id')->constrained('barang')->onDelete('cascade'); // Menambahkan kolom 'barang_id' sebagai foreign key yang merujuk ke tabel 'barang'. Jika barang dihapus, record perawatan yang terkait juga akan dihapus.
            $table->date('tanggal_perawatan'); // Menambahkan kolom 'tanggal_perawatan' dengan tipe data DATE.
            $table->string('jenis_perawatan'); // Menambahkan kolom 'jenis_perawatan' dengan tipe data VARCHAR (string).
            $table->decimal('biaya', 10, 2)->default(0); // Menambahkan kolom 'biaya' dengan tipe data DECIMAL, total 10 digit dengan 2 di belakang koma, nilai defaultnya 0.
            $table->text('keterangan')->nullable(); // Menambahkan kolom 'keterangan' dengan tipe data TEXT, yang bisa bernilai NULL.
            $table->enum('status', ['proses', 'selesai', 'dibatalkan'])->default('proses'); // Menambahkan kolom 'status' dengan tipe data ENUM (hanya bisa salah satu dari 'proses', 'selesai', 'dibatalkan') dan nilai default 'proses'.
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Menambahkan kolom 'user_id' sebagai foreign key yang merujuk ke tabel 'users'. Jika user dihapus, record perawatan yang terkait juga akan dihapus.
            $table->timestamps(); // Menambahkan dua kolom TIMESTAMP: 'created_at' dan 'updated_at' untuk melacak waktu pembuatan dan pembaruan record.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void // Metode 'down' akan dijalankan saat migrasi ini di-rollback (misalnya, 'php artisan migrate:rollback').
    {
        Schema::dropIfExists('perawatan'); // Menghapus tabel 'perawatan' jika tabel tersebut ada di database.
    }
};