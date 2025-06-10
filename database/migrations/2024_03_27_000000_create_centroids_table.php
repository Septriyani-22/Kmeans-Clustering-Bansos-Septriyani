<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('centroids', function (Blueprint $table) {
            $table->id();
            $table->string('nama_centroid');
            $table->decimal('penghasilan_num', 12, 2);
            $table->integer('tanggungan_num');
            $table->integer('tahun');
            $table->integer('periode');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('centroids');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}; 