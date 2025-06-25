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
        Schema::create('trading_profits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->comment('회원 번호');
            $table->foreignId('trading_id')->constrained('tradings')->comment('트레이딩 번호');
            $table->foreignId('transfer_id')->constrained('tradings')->comment('수익내역 번호');
            $table->decimal('profit', 20, 9)->default(0)->comment('수익');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trading_profits');
    }
};
