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
            $table->integer('no_urut');
            $table->string('nama');
            $table->string('nama_wakil');
            $table->enum('posisi', ['GUBERNUR', 'WALIKOTA', 'BUPATI']);
            $table->string('foto', 300)->nullable();
            $table->timestamps();
            
            $table->foreignId('provinsi_id')
                ->nullable()
                ->constrained('provinsi')
                ->nullOnDelete();
            
            $table->foreignId('kabupaten_id')
                ->nullable()
                ->constrained('kabupaten')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calon');
    }
};