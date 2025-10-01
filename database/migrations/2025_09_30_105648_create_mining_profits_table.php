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
        Schema::create('mining_profits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('reward_id')->constrained('mining_rewards');
            $table->foreignId('transfer_id')->constrained('income_transfers');
            $table->enum('type', ['daily', 'instant'])->comment('지급 방식');
            $table->decimal('profit', 20, 9)->default(0)->comment('수익');
            $table->decimal('node_amount', 20, 9)->default(0)->comment('1일 채굴값');
            $table->decimal('reward_rate', 20, 9)->default(0)->comment('지급비율');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mining_profits');
    }
};
