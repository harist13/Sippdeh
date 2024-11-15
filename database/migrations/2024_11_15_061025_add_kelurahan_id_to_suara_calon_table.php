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
            // Add new foreign key and column changes
            $table->foreignId('kelurahan_id')
                ->nullable()
                ->constrained('kelurahan')
                ->nullOnDelete();

            // Drop the old foreign key for tps_id and column
            $table->dropForeign(['tps_id']);
            $table->dropColumn('tps_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suara_calon', function (Blueprint $table) {
            // Remove the kelurahan_id column and foreign key
            $table->dropForeign(['kelurahan_id']);
            $table->dropColumn('kelurahan_id');
            
            // Re-add the tps_id column and foreign key
            $table->foreignId('tps_id')
                ->nullable()
                ->constrained('tps')
                ->nullOnDelete();
        });
    }
};
