<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mapping_centroids', function (Blueprint $table) {
            $table->id();
            $table->integer('data_ke');
            $table->string('nama_penduduk');
            $table->string('cluster');
            $table->integer('usia');
            $table->integer('jumlah_tanggungan');
            $table->string('kondisi_rumah');
            $table->string('status_kepemilikan');
            $table->decimal('jumlah_penghasilan', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mapping_centroids');
    }
};
