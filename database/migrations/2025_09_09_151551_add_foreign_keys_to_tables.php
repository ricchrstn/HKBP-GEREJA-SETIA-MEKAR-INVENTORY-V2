<?php
use Illuminate\Database\Migrations\Migration; // Mengimpor kelas dasar Migration.
use Illuminate\Database\Schema\Blueprint; // Mengimpor kelas Blueprint untuk mendefinisikan struktur tabel.
use Illuminate\Support\Facades\Schema; // Mengimpor Facade Schema untuk berinteraksi dengan database.

return new class extends Migration // Mendefinisikan kelas migrasi anonim yang mewarisi dari Migration.
{
    /**
     * Run the migrations. // PHPDoc comment untuk metode up().
     */
    public function up(): void // Metode `up()` dijalankan ketika migrasi diterapkan. Di sini kita menambahkan batasan (constraints) ke tabel yang sudah ada.
    {
        // Tambahkan constraint ke tabel barang
        Schema::table('barang', function (Blueprint $table) { // Menggunakan `Schema::table()` untuk memodifikasi tabel 'barang' yang sudah ada.
            // Foreign key ke tabel kategori
            $table->foreign('kategori_id', 'fk_barang_kategori_id') // Mendefinisikan kolom 'kategori_id' sebagai foreign key.
                  ->references('id') // Menentukan kolom 'id' di tabel referensi.
                  ->on('kategori') // Menentukan tabel referensi adalah 'kategori'.
                  ->onDelete('cascade'); // Menentukan aksi `onDelete`: jika sebuah kategori dihapus, maka semua barang yang terkait dengan kategori itu juga akan ikut terhapus.

            // Unique constraint untuk kode_barang
            $table->unique('kode_barang', 'uniq_barang_kode_barang'); // Menambahkan batasan unik pada kolom 'kode_barang'.
                                                                    // 'uniq_barang_kode_barang' adalah nama kustom untuk indeks unik.
        });

        // Tambahkan constraint ke tabel barang_masuk
        Schema::table('barang_masuk', function (Blueprint $table) { // Memodifikasi tabel 'barang_masuk'.
            // Foreign key ke tabel barang
            $table->foreign('barang_id', 'fk_barang_masuk_barang_id') // Mendefinisikan 'barang_id' sebagai foreign key.
                  ->references('id')
                  ->on('barang') // Merujuk ke tabel 'barang'.
                  ->onDelete('cascade'); // Jika sebuah barang dihapus, maka catatan barang masuk yang terkait juga terhapus.

            // Foreign key ke tabel users
            $table->foreign('user_id', 'fk_barang_masuk_user_id') // Mendefinisikan 'user_id' sebagai foreign key.
                  ->references('id')
                  ->on('users') // Merujuk ke tabel 'users'.
                  ->onDelete('cascade'); // Jika sebuah user dihapus, maka catatan barang masuk yang terkait juga terhapus.
        });

        // Tambahkan constraint ke tabel barang_keluar
        Schema::table('barang_keluar', function (Blueprint $table) { // Memodifikasi tabel 'barang_keluar'.
            // Foreign key ke tabel barang
            $table->foreign('barang_id', 'fk_barang_keluar_barang_id') // Mendefinisikan 'barang_id' sebagai foreign key.
                  ->references('id')
                  ->on('barang') // Merujuk ke tabel 'barang'.
                  ->onDelete('cascade'); // Jika sebuah barang dihapus, maka catatan barang keluar yang terkait juga terhapus.

            // Foreign key ke tabel users
            $table->foreign('user_id', 'fk_barang_keluar_user_id') // Mendefinisikan 'user_id' sebagai foreign key.
                  ->references('id')
                  ->on('users') // Merujuk ke tabel 'users'.
                  ->onDelete('cascade'); // Jika sebuah user dihapus, maka catatan barang keluar yang terkait juga terhapus.
        });
    }

    /**
     * Reverse the migrations. // PHPDoc comment untuk metode down().
     */
    public function down(): void // Metode `down()` dijalankan ketika migrasi di-rollback. Di sinilah kita menghapus batasan yang telah ditambahkan.
    {
        // Hapus constraint dari tabel barang_keluar
        Schema::table('barang_keluar', function (Blueprint $table) {
            $table->dropForeign('fk_barang_keluar_barang_id'); // Menghapus foreign key dengan nama kustom yang telah dibuat.
            $table->dropForeign('fk_barang_keluar_user_id'); // Menghapus foreign key dengan nama kustom yang telah dibuat.
        });

        // Hapus constraint dari tabel barang_masuk
        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->dropForeign('fk_barang_masuk_barang_id'); // Menghapus foreign key.
            $table->dropForeign('fk_barang_masuk_user_id'); // Menghapus foreign key.
        });

        // Hapus constraint dari tabel barang
        Schema::table('barang', function (Blueprint $table) {
            $table->dropForeign('fk_barang_kategori_id'); // Menghapus foreign key.
            $table->dropUnique('uniq_barang_kode_barang'); // Menghapus unique constraint.
        });
    }
};