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
        Schema::create('nilai_pengadaan_kriterias', function (Blueprint $table) { // Membuat tabel baru di database dengan nama 'nilai_pengadaan_kriterias'.
            $table->id(); // Menambahkan kolom 'id' sebagai primary key yang auto-incrementing (BIGINT UNSIGNED).
            $table->foreignId('pengajuan_id')->constrained('pengajuan')->onDelete('cascade'); // Menambahkan kolom 'pengajuan_id' sebagai foreign key yang merujuk ke tabel 'pengajuan'. Jika record pengajuan yang terkait dihapus, record di tabel ini juga akan dihapus.
            $table->foreignId('kriteria_id')->constrained('kriterias')->onDelete('cascade'); // Menambahkan kolom 'kriteria_id' sebagai foreign key yang merujuk ke tabel 'kriterias'. Jika record kriteria yang terkait dihapus, record di tabel ini juga akan dihapus.
            $table->float('nilai'); // Menambahkan kolom 'nilai' dengan tipe float (FLOAT), yang bisa menyimpan angka desimal. Ini akan menyimpan nilai kriteria untuk suatu pengajuan.
            $table->timestamps(); // Menambahkan dua kolom TIMESTAMP: 'created_at' dan 'updated_at' untuk melacak waktu pembuatan dan pembaruan record.

            $table->unique(['pengajuan_id', 'kriteria_id']); // Menambahkan batasan UNIQUE untuk kombinasi kolom 'pengajuan_id' dan 'kriteria_id'. Ini berarti untuk setiap pengajuan, hanya boleh ada satu nilai untuk setiap kriteria.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void // Metode 'down' akan dijalankan saat migrasi ini di-rollback (misalnya, dengan perintah 'php artisan migrate:rollback').
    {
        Schema::dropIfExists('nilai_pengadaan_kriterias'); // Menghapus tabel 'nilai_pengadaan_kriterias' dari database, tetapi hanya jika tabel tersebut ada.
    }
};