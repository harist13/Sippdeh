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
        Schema::create('suara_tps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kotak_kosong')->default(0);
            $table->unsignedBigInteger('suara_tidak_sah')->default(0);
            $table->enum('posisi', ['GUBERNUR', 'WALIKOTA', 'BUPATI'])->nullable();
            $table->timestamps();

            $table->foreignId('operator_id')
                ->nullable()
                ->constrained('petugas')
                ->nullOnDelete();
            
            $table->foreignId('tps_id')
                ->nullable()
                ->constrained('tps')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suara_tps');
    }
};
