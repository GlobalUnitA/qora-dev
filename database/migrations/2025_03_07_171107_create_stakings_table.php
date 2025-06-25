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
        Schema::create('stakings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->comment('회원 번호');
            $table->foreignId('coin_id')->constrained('coins')->comment('코인 번호');
            $table->string('staking_name', 50)->comment('상품 이름');
            $table->enum('status', ['pending', 'completed'])->default('pending')->comment('상태');
            $table->decimal('amount', 20, 9)->default(0)->comment('참여 수량');
            $table->unsignedInteger('period')->default(7)->comment('기간');
            $table->timestamp('started_at')->comment('시작일');
            $table->timestamp('ended_at')->comment('만료일');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stakings');
    }
};
