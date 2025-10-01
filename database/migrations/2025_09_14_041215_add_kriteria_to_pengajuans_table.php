<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            $table->integer('urgensi')->nullable()->after('file_pengajuan');
            $table->integer('ketersediaan_stok')->nullable()->after('urgensi');
            $table->integer('ketersediaan_dana')->nullable()->after('ketersediaan_stok');
        });
    }

    public function down()
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            $table->dropColumn(['urgensi', 'ketersediaan_stok', 'ketersediaan_dana']);
        });
    }
};
