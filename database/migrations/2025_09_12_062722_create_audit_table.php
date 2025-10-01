<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditTable extends Migration
{
    public function up()
    {
        Schema::create('audit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barang')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal_audit');
            $table->enum('kondisi', ['baik', 'rusak', 'hilang', 'tidak_terpakai']);
            $table->text('keterangan')->nullable();
            $table->string('foto')->nullable();
            $table->enum('status', ['draft', 'selesai'])->default('selesai');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('audit');
    }
}
