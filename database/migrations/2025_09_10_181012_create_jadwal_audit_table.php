<?php

// Menggunakan namespace untuk kelas Migration dan Blueprint dari Laravel
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema; // Menggunakan facade Schema untuk berinteraksi dengan database

// Mendefinisikan kelas migrasi baru yang meng-extend Migration
return new class extends Migration
{
    /**
     * Metode `up` akan dijalankan saat migrasi di-jalankan (run).
     * Ini adalah tempat di mana kita mendefinisikan perubahan skema database (misalnya, membuat tabel).
     */
    public function up(): void
    {
        // Membuat tabel baru di database dengan nama 'jadwal_audit'
        Schema::create('jadwal_audit', function (Blueprint $table) {
            // Kolom 'id' sebagai primary key dengan auto-increment
            $table->id(); 
            // Kolom 'judul' untuk menyimpan string (judul jadwal audit)
            $table->string('judul'); 
            // Kolom 'deskripsi' untuk menyimpan teks panjang, boleh kosong (nullable)
            $table->text('deskripsi')->nullable(); 
            // Kolom 'tanggal_audit' untuk menyimpan tanggal
            $table->date('tanggal_audit'); 
            // Kolom 'status' dengan pilihan nilai 'terjadwal', 'diproses', 'selesai', 'ditunda'
            // Nilai defaultnya adalah 'terjadwal'
            $table->enum('status', ['terjadwal', 'diproses', 'selesai', 'ditunda'])->default('terjadwal'); 
            // Kolom 'barang_id' sebagai foreign key yang merujuk ke tabel 'barang'
            // Jika data di tabel 'barang' yang terkait dihapus, data di 'jadwal_audit' ini juga akan dihapus (onDelete('cascade'))
            $table->foreignId('barang_id')->constrained('barang')->onDelete('cascade'); 
            // Kolom 'user_id' sebagai foreign key yang merujuk ke tabel 'users'
            // Jika data di tabel 'users' yang terkait dihapus, data di 'jadwal_audit' ini juga akan dihapus (onDelete('cascade'))
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            // Kolom 'created_at' dan 'updated_at' otomatis terisi oleh Laravel untuk timestamp
            $table->timestamps(); 
        });
    }

    /**
     * Metode `down` akan dijalankan saat migrasi di-rollback.
     * Ini adalah tempat di mana kita membatalkan perubahan yang dilakukan di metode `up`
     * (misalnya, menghapus tabel yang telah dibuat).
     */
    public function down(): void
    {
        // Menghapus tabel 'jadwal_audit' jika tabel tersebut ada
        Schema::dropIfExists('jadwal_audit');
    }
};