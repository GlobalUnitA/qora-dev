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
        Schema::create('asset_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->comment('회원 번호');
            $table->foreignId('asset_id')->constrained('assets')->comment('자산 번호');
            $table->enum('type', ['deposit', 'withdrawal', 'manual_deposit'])->comment('거래 타입');
            $table->enum('status', ['pending', 'waiting', 'completed', 'canceled', 'refunded'])->comment('거래 상태');
            $table->decimal('actual_amount', 12, 2)->default(0)->comment('실제금액');
            $table->decimal('tax', 20, 9)->default(0)->comment('세금');
            $table->decimal('fee', 20, 9)->default(0)->comment('수수료');
            $table->decimal('amount', 20, 9)->default(0)->comment('거래금액');
            $table->decimal('before_balance', 20, 9)->default(0)->comment('거래 전 잔액');
            $table->decimal('after_balance', 20, 9)->default(0)->comment('거래 후 잔액');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_transfers');
    }
};
