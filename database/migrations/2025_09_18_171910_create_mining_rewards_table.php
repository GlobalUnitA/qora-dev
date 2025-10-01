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
        Schema::create('mining_rewards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('mining_id')->constrained('minings');
            $table->decimal('reward', 20, 9)->default(0)->comment('채굴량');
            $table->date('reward_date')->comment('채굴 날짜');
            $table->timestamps();

            $table->unique(['mining_id', 'reward_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mining_rewards');
    }
};
