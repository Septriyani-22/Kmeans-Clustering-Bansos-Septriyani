<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iterasi', function (Blueprint $table) {
            $table->id();
            $table->integer('iterasi');
            $table->integer('tahun');
            $table->integer('periode');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iterasi');
    }
}; 