<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calon', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nama_wakil');
            $table->enum('posisi', ['GUBERNUR', 'WALIKOTA']);
            $table->string('foto', 300)->nullable();
            $table->timestamps();
            
            $table->foreignId('provinsi_id')->nullable()
                ->constrained('provinsi')->onDelete('cascade');
            $table->foreignId('kabupaten_id')->nullable()
                ->constrained('kabupaten')->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calon');
    }
};