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
        Schema::create('level_matchings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('bonus_id')->constrained('level_bonuses');
            $table->foreignId('transfer_id')->constrained('income_transfers');
            $table->unsignedBigInteger('referrer_id')->comment('산하 ID');
            $table->decimal('matching', 20, 9)->comment('레벨 매칭');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('level_matchings');
    }
};
