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
        Schema::create('level_bonuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->unsignedBigInteger('referrer_id')->comment('산하ID');
            $table->foreignId('transfer_id')->constrained('income_transfers');
            $table->foreignId('reward_id')->constrained('mining_rewards');
            $table->decimal('bonus', $precision = 20, $scale = 9)->comment('보너스');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('level_bonuses');
    }
};
