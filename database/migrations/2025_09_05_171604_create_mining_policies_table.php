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
        Schema::create('mining_policies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coin_id')->constrained('coins')->comment('입금 코인 번호');
            $table->foreignId('refund_coin_id')->constrained('coins')->comment('원금 코인 번호');
            $table->foreignId('reward_coin_id')->constrained('coins')->comment('수익 코인 번호');
            $table->decimal('instant_rate', 20, 9)->default(0)->comment('즉시 지급 비율');
            $table->decimal('split_rate', 20, 9)->default(0)->comment('분할 지급 비율');
            $table->decimal('exchange_rate', 20, 9)->default(0)->comment('환율');
            $table->decimal('node_amount', 20, 9)->default(0)->comment('채굴값');
            $table->unsignedInteger('node_limit')->default(0)->comment('최대 노드 수량');
            $table->unsignedInteger('split_period')->default(0)->comment('분할기간');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mining_policies');
    }
};
