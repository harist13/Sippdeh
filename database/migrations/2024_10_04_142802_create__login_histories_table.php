<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoginHistoriesTable extends Migration
{
    public function up()
    {
        Schema::create('login_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('ip_address');
            $table->string('user_agent');
            $table->timestamp('login_at');
            $table->boolean('is_logged_out')->default(false);
            $table->timestamp('logged_out_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('petugas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('login_histories');
    }
}