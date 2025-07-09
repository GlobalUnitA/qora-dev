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
        Schema::create('wallet_policies', function (Blueprint $table) {
            $table->id();
            $table->integer('min_quantity')->comment('최소 참여 수량');
            $table->decimal('profit_rate', 20, 9)->comment('수익률');
            $table->decimal('deposit_fee', 20, 9)->comment('입금 수수료');
            $table->decimal('withdrawal_fee', 20, 9)->comment('출금 수수료');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_policies');
    }
};
