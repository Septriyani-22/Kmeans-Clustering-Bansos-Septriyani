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
        Schema::create('penduduks', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique();
            $table->string('nama');
            $table->integer('tahun');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->integer('usia');
            $table->integer('rt');
            $table->integer('tanggungan');
            $table->decimal('penghasilan', 12, 2);
            $table->enum('kondisi_rumah', ['kurang', 'cukup', 'baik']);
            $table->enum('status_kepemilikan', ['hak milik', 'numpang', 'sewa']);
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penduduks');
    }
};
