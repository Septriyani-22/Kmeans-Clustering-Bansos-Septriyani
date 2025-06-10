<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('penduduk', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16)->unique();
            $table->string('nama');
            $table->integer('usia');
            $table->integer('tanggungan');
            $table->string('kondisi_rumah');
            $table->string('status_kepemilikan');
            $table->decimal('penghasilan', 12, 2);
            $table->string('jenis_kelamin');
            $table->integer('rt');
            $table->integer('tahun');
            $table->integer('cluster')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('penduduk');
    }
}; 