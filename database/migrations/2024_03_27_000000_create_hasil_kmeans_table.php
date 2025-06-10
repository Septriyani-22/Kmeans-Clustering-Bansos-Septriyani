<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Ensure penduduks table exists first
        if (!Schema::hasTable('penduduks')) {
            throw new \Exception('The penduduks table must exist before creating hasil_kmeans table');
        }

        Schema::create('hasil_kmeans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penduduk_id')->constrained('penduduks')->onDelete('cascade');
            $table->foreignId('centroid_id')->constrained('centroids')->onDelete('cascade');
            $table->string('cluster');
            $table->decimal('skor_kelayakan', 8, 4)->default(0);
            $table->decimal('skor_penghasilan', 8, 4)->default(0);
            $table->decimal('skor_tanggungan', 8, 4)->default(0);
            $table->decimal('skor_kondisi_rumah', 8, 4)->default(0);
            $table->decimal('skor_status_kepemilikan', 8, 4)->default(0);
            $table->decimal('skor_usia', 8, 4)->default(0);
            $table->enum('kelayakan', ['Layak', 'Tidak Layak']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hasil_kmeans');
    }
}; 