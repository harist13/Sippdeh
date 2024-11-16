<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tps', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->unsignedBigInteger('dpt')->default(0);
            $table->timestamps();

            $table->foreignId('kelurahan_id')
                ->nullable()
                ->constrained('kelurahan')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tps');
    }
};