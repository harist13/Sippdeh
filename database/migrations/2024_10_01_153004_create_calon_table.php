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
            $table->string('nama_paslon');
            $table->foreignId('kabupaten_id')->constrained('kabupaten')->onDelete('cascade');
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calon');
    }
};