<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_kmeans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penduduk_id')->constrained('penduduk')->onDelete('cascade');
            $table->foreignId('centroid_id')->constrained('centroids')->onDelete('cascade');
            $table->integer('cluster')->default(1);
            $table->float('jarak');
            $table->integer('iterasi');
            $table->integer('tahun');
            $table->integer('periode');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_kmeans');
    }
}; 