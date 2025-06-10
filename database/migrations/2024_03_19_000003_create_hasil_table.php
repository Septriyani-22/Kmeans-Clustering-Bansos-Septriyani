<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hasil', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penduduk_id')->constrained('penduduk')->onDelete('cascade');
            $table->integer('cluster');
            $table->decimal('jarak', 10, 4);
            $table->integer('iterasi');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hasil');
    }
}; 