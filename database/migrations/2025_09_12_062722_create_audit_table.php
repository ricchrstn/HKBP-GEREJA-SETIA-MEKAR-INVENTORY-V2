<?php
use Illuminate\Database\Migrations\Migration; // Mengimpor kelas dasar untuk semua migrasi database di Laravel.
use Illuminate\Database\Schema\Blueprint; // Mengimpor kelas Blueprint, yang digunakan untuk mendefinisikan struktur tabel.
use Illuminate\Support\Facades\Schema; // Mengimpor fasad Schema, yang menyediakan metode untuk berinteraksi dengan skema database (misalnya, membuat, mengubah, menghapus tabel).

class CreateAuditTable extends Migration // Mendefinisikan kelas migrasi baru bernama 'CreateAuditTable' yang mewarisi dari 'Migration'.
{
    public function up() // Metode 'up' akan dijalankan saat migrasi ini diterapkan ke database (misalnya, dengan 'php artisan migrate').
    {
        Schema::create('audit', function (Blueprint $table) { // Membuat tabel baru di database dengan nama 'audit'.
            $table->id(); // Menambahkan kolom 'id' sebagai primary key yang auto-incrementing (BIGINT UNSIGNED).
            $table->foreignId('barang_id')->constrained('barang')->onDelete('cascade'); // Menambahkan kolom 'barang_id' sebagai foreign key yang merujuk ke tabel 'barang'. Jika record di tabel 'barang' yang terkait dihapus, record di tabel 'audit' ini juga akan dihapus.
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Menambahkan kolom 'user_id' sebagai foreign key yang merujuk ke tabel 'users'. Jika record di tabel 'users' yang terkait dihapus, record di tabel 'audit' ini juga akan dihapus.
            $table->date('tanggal_audit'); // Menambahkan kolom 'tanggal_audit' dengan tipe data DATE.
            $table->enum('kondisi', ['baik', 'rusak', 'hilang', 'tidak_terpakai']); // Menambahkan kolom 'kondisi' dengan tipe data ENUM, yang berarti nilainya harus salah satu dari daftar yang ditentukan: 'baik', 'rusak', 'hilang', atau 'tidak_terpakai'.
            $table->text('keterangan')->nullable(); // Menambahkan kolom 'keterangan' dengan tipe data TEXT, yang bisa menyimpan teks panjang. '.nullable()' berarti kolom ini boleh kosong (NULL).
            $table->string('foto')->nullable(); // Menambahkan kolom 'foto' dengan tipe data VARCHAR (string). Biasanya digunakan untuk menyimpan nama file atau path ke gambar. '.nullable()' berarti kolom ini boleh kosong (NULL).
            $table->enum('status', ['draft', 'selesai'])->default('selesai'); // Menambahkan kolom 'status' dengan tipe data ENUM (hanya bisa 'draft' atau 'selesai') dan nilai defaultnya adalah 'selesai'.
            $table->timestamps(); // Menambahkan dua kolom TIMESTAMP: 'created_at' dan 'updated_at'. Laravel akan secara otomatis mengelola nilai kolom ini untuk mencatat kapan record dibuat dan terakhir diperbarui.
        });
    }

    public function down() // Metode 'down' akan dijalankan saat migrasi ini di-rollback (misalnya, dengan 'php artisan migrate:rollback').
    {
        Schema::dropIfExists('audit'); // Menghapus tabel 'audit' dari database, tetapi hanya jika tabel tersebut ada.
    }
}