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
            $table->integer('no')->nullable();
            $table->string('nik')->unique();
            $table->string('nama');
            $table->integer('tahun');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->integer('usia');
            $table->integer('rt');
            $table->integer('tanggungan');
            $table->enum('kondisi_rumah', ['baik', 'cukup', 'kurang']);
            $table->enum('status_kepemilikan', ['hak milik', 'numpang', 'sewa']);
            $table->decimal('penghasilan', 12, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('penduduk');
    }
}; 