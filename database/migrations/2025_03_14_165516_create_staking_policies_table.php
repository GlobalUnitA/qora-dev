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
        Schema::create('staking_policies', function (Blueprint $table) {
            $table->id();
            $table->string('staking_name', 50)->comment('상품 이름');
            $table->integer('min_quantity')->comment('최소 참여수량');
            $table->integer('max_quantity')->comment('최대 참여수량');
            $table->decimal('daily', 20, 9)->comment('데일리 수익률');
            $table->integer('period')->comment('기간');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staking_policies');
    }
};
