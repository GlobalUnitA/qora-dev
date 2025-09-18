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
        Schema::create('level_policies', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('depth')->comment('뎁스');
            $table->decimal('bonus', 20, 9)->default(0)->comment('레벨 보너스 비율');
            $table->decimal('matching', 20, 9)->default(0)->comment('레벨 매칭 비율');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('level_policies');
    }
};
