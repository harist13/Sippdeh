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
        Schema::create('suara_calon', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('suara')->default(0);
            $table->timestamps();

            $table->foreignId('operator_id')
                ->nullable()
                ->constrained('petugas')
                ->nullOnDelete();
            
            $table->foreignId('tps_id')
                ->nullable()
                ->constrained('tps')
                ->nullOnDelete();
            
            $table->foreignId('calon_id')
                ->nullable()
                ->constrained('calon')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suara_calon');
    }
};
