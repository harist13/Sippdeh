<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelurahan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->timestamps();

            $table->foreignId('kecamatan_id')
                ->nullable()
                ->constrained('kecamatan')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelurahan');
    }
};