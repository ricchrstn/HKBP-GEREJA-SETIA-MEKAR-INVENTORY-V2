<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Tambahkan constraint ke tabel barang
        Schema::table('barang', function (Blueprint $table) {
            // Foreign key ke tabel kategori
            $table->foreign('kategori_id', 'fk_barang_kategori_id')
                  ->references('id')
                  ->on('kategori')
                  ->onDelete('cascade');

            // Unique constraint untuk kode_barang
            $table->unique('kode_barang', 'uniq_barang_kode_barang');
        });

        // Tambahkan constraint ke tabel barang_masuk
        Schema::table('barang_masuk', function (Blueprint $table) {
            // Foreign key ke tabel barang
            $table->foreign('barang_id', 'fk_barang_masuk_barang_id')
                  ->references('id')
                  ->on('barang')
                  ->onDelete('cascade');

            // Foreign key ke tabel users
            $table->foreign('user_id', 'fk_barang_masuk_user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });

        // Tambahkan constraint ke tabel barang_keluar
        Schema::table('barang_keluar', function (Blueprint $table) {
            // Foreign key ke tabel barang
            $table->foreign('barang_id', 'fk_barang_keluar_barang_id')
                  ->references('id')
                  ->on('barang')
                  ->onDelete('cascade');

            // Foreign key ke tabel users
            $table->foreign('user_id', 'fk_barang_keluar_user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus constraint dari tabel barang_keluar
        Schema::table('barang_keluar', function (Blueprint $table) {
            $table->dropForeign('fk_barang_keluar_barang_id');
            $table->dropForeign('fk_barang_keluar_user_id');
        });

        // Hapus constraint dari tabel barang_masuk
        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->dropForeign('fk_barang_masuk_barang_id');
            $table->dropForeign('fk_barang_masuk_user_id');
        });

        // Hapus constraint dari tabel barang
        Schema::table('barang', function (Blueprint $table) {
            $table->dropForeign('fk_barang_kategori_id');
            $table->dropUnique('uniq_barang_kode_barang');
        });
    }
};
