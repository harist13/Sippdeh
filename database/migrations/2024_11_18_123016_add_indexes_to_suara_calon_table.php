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
        Schema::table('suara_calon', function (Blueprint $table) {
            $table->index(['tps_id', 'calon_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suara_calon', function (Blueprint $table) {
            $table->dropIndex(['tps_id', 'calon_id']);
        });
    }
};
