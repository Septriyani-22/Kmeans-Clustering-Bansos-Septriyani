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
        Schema::create('iterasi', function (Blueprint $table) {
            $table->id();
            $table->text('centroid_awal')->nullable();
            $table->text('hasil_iterasi')->nullable();
            $table->integer('iterasi_ke');
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iterasi');
    }
};
