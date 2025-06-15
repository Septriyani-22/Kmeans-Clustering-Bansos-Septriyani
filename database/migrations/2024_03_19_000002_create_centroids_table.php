<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCentroidsTable extends Migration
{
    public function up()
    {
        Schema::create('centroids', function (Blueprint $table) {
            $table->id();
            $table->integer('usia');
            $table->integer('tanggungan_num');
            $table->string('kondisi_rumah');
            $table->string('status_kepemilikan');
            $table->integer('penghasilan_num');
            $table->integer('tahun')->nullable();
            $table->integer('periode')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('centroids');
    }
} 