<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kabupaten', function (Blueprint $table) {
            $table->id();
            $table->string('logo', 300)->nullable();
            $table->string('nama');
            $table->string('slug')->unique()->nullable();
            $table->timestamps();

            $table->foreignId('provinsi_id')
                ->nullable()
                ->constrained('provinsi')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kabupaten');
    }
};