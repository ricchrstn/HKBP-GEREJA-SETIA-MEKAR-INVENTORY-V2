<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengajuanTable extends Migration
{
    public function up()
    {
        Schema::create('pengajuan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pengajuan')->unique();
            $table->string('nama_barang');
            $table->text('spesifikasi')->nullable();
            $table->integer('jumlah')->unsigned();
            $table->string('satuan');
            $table->text('alasan');
            $table->date('kebutuhan');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'disetujui', 'ditolak', 'proses'])->default('pending');
            $table->text('keterangan')->nullable();
            $table->string('file_pengajuan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengajuan');
    }
}
