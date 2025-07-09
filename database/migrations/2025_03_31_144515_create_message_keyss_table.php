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
        Schema::create('message_keys', function (Blueprint $table) {
            $table->id();
            $table->string('category', 255)->comment('카테고리');
            $table->string('key', 255)->comment('키');
            $table->string('description', 255)->nullable()->comment('설명');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_keys');
    }
};
