<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kriteria', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->string('tipe_kriteria')->nullable();
            $table->timestamps();
        });

        // Schema::create('nilai_kriteria', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('kriteria_id')->constrained('kriteria')->onDelete('cascade');
        //     $table->string('nama');
        //     $table->integer('nilai');
        //     $table->string('keterangan')->nullable();
        //     $table->timestamps();
        // });
    }

    public function down()
    {
        Schema::dropIfExists('nilai_kriteria');
        Schema::dropIfExists('kriteria');
    }
}; 