<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pengajuan_riwayat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penduduk_id')->constrained('penduduk')->onDelete('cascade');
            $table->string('aksi')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('status')->default('diajukan');
            $table->text('data_lama')->nullable();
            $table->text('data_baru')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengajuan_riwayat');
    }
}; 