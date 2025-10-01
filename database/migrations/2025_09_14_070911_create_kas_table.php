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
        Schema::create('kas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->enum('jenis', ['masuk', 'keluar']);
            $table->decimal('jumlah', 15, 2);
            $table->date('tanggal');
            $table->string('keterangan');
            $table->string('sumber')->nullable(); // Sumber pemasukan (misal: persembahan, donasi)
            $table->string('tujuan')->nullable(); // Tujuan pengeluaran (misal: pengadaan, operasional)
            $table->string('bukti_transaksi')->nullable(); // Path file bukti transaksi
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kas');
    }
};
