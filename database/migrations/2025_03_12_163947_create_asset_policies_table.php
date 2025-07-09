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
        Schema::create('asset_policies', function (Blueprint $table) {
            $table->id();
            $table->integer('deposit_period')->comment('입금 반영 기간');
            $table->integer('withdrawal_period')->comment('출금 공제 기간');
            $table->decimal('tax_rate', 20, 9)->comment('세금 비율');
            $table->decimal('fee_rate', 20, 9)->comment('수수료 비율');
            $table->decimal('total_rate', 20, 9)->comment('총 공제 비율');
            $table->decimal('after_total_rate', 20, 9)->comment('기간 이후 공제 비율');
            $table->decimal('min_valid', 20, 9)->comment('최소 보유 금액');
            $table->decimal('min_withdrawal', 20, 9)->comment('최소 출금 금액');
            $table->string('withdrawal_days', 50)->nullable()->comment('출금 가능 요일');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_policies');
    }
};
