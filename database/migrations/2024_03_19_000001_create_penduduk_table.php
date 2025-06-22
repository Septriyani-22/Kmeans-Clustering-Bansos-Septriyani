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
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            
            // Kolom asli yang dipertahankan dan dibuat nullable
            $table->integer('no')->nullable();
            $table->string('nik')->unique()->nullable();
            $table->string('nama')->nullable();
            $table->integer('tahun')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->integer('usia')->nullable();
            $table->integer('rt')->nullable();
            $table->integer('tanggungan')->nullable();
            $table->enum('kondisi_rumah', ['baik', 'cukup', 'kurang'])->nullable();
            $table->enum('status_kepemilikan', ['hak milik', 'numpang', 'sewa'])->nullable();
            $table->decimal('penghasilan', 12, 2)->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('penduduk');
    }
}; 