<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('centroids', function (Blueprint $table) {
            $table->id();
            $table->string('nama_centroid');
            $table->bigInteger('penghasilan_num');
            $table->integer('tanggungan_num');
            $table->integer('tahun');
            $table->integer('periode');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('centroids');
    }
}; 