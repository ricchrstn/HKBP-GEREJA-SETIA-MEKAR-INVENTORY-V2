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
        Schema::create('barang', function (Blueprint $table) { // Membuat tabel baru bernama 'barang'.
            $table->id(); // Membuat kolom 'id' sebagai primary key auto-incrementing.
            $table->string('kode_barang')->unique(); // Membuat kolom 'kode_barang' (VARCHAR) yang harus unik.
            $table->string('nama'); // Membuat kolom 'nama' (VARCHAR).
            $table->text('deskripsi')->nullable(); // Membuat kolom 'deskripsi' (TEXT) yang bisa kosong.
            $table->foreignId('kategori_id')->constrained('kategori'); // Membuat kolom 'kategori_id' (BIGINT UNSIGNED) sebagai foreign key.
                                                                    // `->constrained('kategori')` secara otomatis membuat batasan foreign key
                                                                    // yang merujuk ke kolom 'id' di tabel 'kategori'.
            $table->string('satuan'); // Membuat kolom 'satuan' (VARCHAR).
            $table->integer('stok')->default(0); // Membuat kolom 'stok' (INTEGER) dengan nilai default 0.
            $table->enum('status', ['aktif', 'rusak', 'hilang', 'perawatan'])->default('aktif'); // Membuat kolom 'status' dengan tipe ENUM.
                                                                                              // Hanya menerima nilai dari daftar yang diberikan dan default-nya 'aktif'.
            $table->decimal('harga', 12, 2); // Membuat kolom 'harga' dengan tipe DECIMAL.
                                           // '12' adalah total digit yang diizinkan, '2' adalah jumlah digit di belakang koma.
            $table->string('gambar')->nullable(); // Membuat kolom 'gambar' (VARCHAR) yang bisa kosong, kemungkinan menyimpan path/nama file gambar.
            $table->timestamps(); // Menambahkan kolom 'created_at' dan 'updated_at' (TIMESTAMP).
            $table->softDeletes(); // Menambahkan kolom 'deleted_at' (TIMESTAMP NULL) untuk fitur soft deletes.
        });
    }

    /**
     * Reverse the migrations. // PHPDoc comment untuk metode down().
     *
     * @return void
     */
    public function down(): void // Metode `down()` dijalankan ketika migrasi di-rollback.
    {
        Schema::dropIfExists('barang'); // Menghapus tabel 'barang' jika ada.
    }
};