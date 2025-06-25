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
        Schema::create('bonuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->comment('회원 번호');
            $table->unsignedBigInteger('referrer_id')->comment('산하 회원 번호');
            $table->foreignId('transfer_id')->constrained('income_transfers')->comment('수익 내역 번호'); 
            $table->enum('type', ['asset', 'income'])->comment('타입');
            $table->decimal('bonus', 20, 9)->comment('보너스');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bonuses');
    }
};
