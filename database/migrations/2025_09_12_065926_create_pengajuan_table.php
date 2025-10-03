<?php
use Illuminate\Database\Migrations\Migration; // Mengimpor kelas dasar 'Migration' yang wajib untuk setiap file migrasi.
use Illuminate\Database\Schema\Blueprint; // Mengimpor kelas 'Blueprint' untuk mendefinisikan struktur kolom tabel.
use Illuminate\Support\Facades\Schema; // Mengimpor fasad 'Schema' untuk berinteraksi dengan skema database (misalnya, membuat, mengubah, menghapus tabel).

class CreatePengajuanTable extends Migration // Mendefinisikan kelas migrasi baru dengan nama 'CreatePengajuanTable' yang merupakan turunan dari 'Migration'.
{
    public function up() // Metode 'up' akan dieksekusi ketika migrasi ini dijalankan (misalnya, dengan perintah 'php artisan migrate').
    {
        Schema::create('pengajuan', function (Blueprint $table) { // Membuat tabel baru di database dengan nama 'pengajuan'.
            $table->id(); // Menambahkan kolom 'id' sebagai primary key yang otomatis bertambah (auto-incrementing BIGINT UNSIGNED).
            $table->string('kode_pengajuan')->unique(); // Menambahkan kolom 'kode_pengajuan' dengan tipe string (VARCHAR) dan memastikan setiap nilai di kolom ini adalah unik.
            $table->string('nama_barang'); // Menambahkan kolom 'nama_barang' dengan tipe string (VARCHAR).
            $table->text('spesifikasi')->nullable(); // Menambahkan kolom 'spesifikasi' dengan tipe teks (TEXT) untuk deskripsi panjang, dan '.nullable()' berarti kolom ini boleh kosong.
            $table->integer('jumlah')->unsigned(); // Menambahkan kolom 'jumlah' dengan tipe integer (INTEGER) dan '.unsigned()' berarti nilai tidak bisa negatif (selalu nol atau positif).
            $table->string('satuan'); // Menambahkan kolom 'satuan' dengan tipe string (VARCHAR).
            $table->text('alasan'); // Menambahkan kolom 'alasan' dengan tipe teks (TEXT).
            $table->date('kebutuhan'); // Menambahkan kolom 'kebutuhan' dengan tipe data DATE.
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Menambahkan kolom 'user_id' sebagai foreign key yang merujuk ke kolom 'id' di tabel 'users'. Jika user yang terkait dihapus, record pengajuan ini juga akan dihapus.
            $table->enum('status', ['pending', 'disetujui', 'ditolak', 'proses'])->default('pending'); // Menambahkan kolom 'status' dengan tipe ENUM, yang hanya dapat berisi salah satu dari nilai yang ditentukan ('pending', 'disetujui', 'ditolak', 'proses'), dengan nilai default 'pending'.
            $table->text('keterangan')->nullable(); // Menambahkan kolom 'keterangan' dengan tipe teks (TEXT), yang boleh kosong.
            $table->string('file_pengajuan')->nullable(); // Menambahkan kolom 'file_pengajuan' dengan tipe string (VARCHAR), kemungkinan untuk menyimpan nama file atau path, dan boleh kosong.
            $table->timestamps(); // Menambahkan dua kolom TIMESTAMP: 'created_at' dan 'updated_at'. Laravel secara otomatis mengelola tanggal dan waktu pembuatan serta pembaruan record.
        });
    }

    public function down() // Metode 'down' akan dieksekusi ketika migrasi ini di-rollback (misalnya, dengan perintah 'php artisan migrate:rollback').
    {
        Schema::dropIfExists('pengajuan'); // Menghapus tabel 'pengajuan' dari database, tetapi hanya jika tabel tersebut ada.
    }
}