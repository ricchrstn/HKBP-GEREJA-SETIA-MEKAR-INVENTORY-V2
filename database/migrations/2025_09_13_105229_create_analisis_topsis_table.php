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
        Schema::create('analisis_topsis', function (Blueprint $table) { // Membuat tabel baru di database dengan nama 'analisis_topsis'.
            $table->id(); // Menambahkan kolom 'id' sebagai primary key yang auto-incrementing (BIGINT UNSIGNED).
            $table->foreignId('pengajuan_id')->constrained('pengajuan')->onDelete('cascade'); // Menambahkan kolom 'pengajuan_id' sebagai foreign key yang merujuk ke tabel 'pengajuan'. Jika record pengajuan yang terkait dihapus, record analisis TOPSIS ini juga akan dihapus.
            $table->float('nilai_preferensi')->nullable(); // Menambahkan kolom 'nilai_preferensi' dengan tipe float (FLOAT). Ini akan menyimpan hasil akhir perhitungan preferensi TOPSIS (biasanya nilai V). '.nullable()' berarti kolom ini boleh kosong.
            $table->integer('ranking')->nullable(); // Menambahkan kolom 'ranking' dengan tipe integer (INTEGER). Ini akan menyimpan peringkat hasil analisis TOPSIS. '.nullable()' berarti kolom ini boleh kosong.
            $table->timestamps(); // Menambahkan dua kolom TIMESTAMP: 'created_at' dan 'updated_at' untuk melacak waktu pembuatan dan pembaruan record.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void // Metode 'down' akan dijalankan saat migrasi ini di-rollback (misalnya, dengan perintah 'php artisan migrate:rollback').
    {
        Schema::dropIfExists('analisis_topsis'); // Menghapus tabel 'analisis_topsis' dari database, tetapi hanya jika tabel tersebut ada.
    }
};