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
        Schema::create('minings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('asset_id')->constrained('assets');
            $table->foreignId('refund_id')->nullable()->constrained('assets');
            $table->foreignId('reward_id')->nullable()->constrained('incomes');
            $table->foreignId('policy_id')->constrained('mining_policies');

            $table->enum('status', ['pending', 'completed'])->default('pending')->comment('상태');

            $table->decimal('coin_amount', 20, 9)->default(0)->comment('참여수량');
            $table->decimal('refund_coin_amount', 20, 9)->default(0)->comment('상환수량');
            $table->decimal('node_amount', 20, 9)->default(0)->comment('노드 참여수량');
            $table->decimal('exchange_rate', 20, 9)->default(0)->comment('환율');

            $table->unsignedInteger('split_period')->default(0)->comment('분할기간');
            $table->unsignedInteger('reward_count')->default(0)->comment('수익 지급 횟수');

            $table->timestamp('started_at')->nullable()->comment('시작일');
            $table->timestamp('ended_at')->nullable()->comment('종료일');
            $table->timestamp('maturity_at')->nullable()->comment('상환 예정일');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('minings');
    }
};
