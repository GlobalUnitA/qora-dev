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
        Schema::create('rank_bonuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->comment('회원 번호');
            $table->foreignId('policy_id')->constrained('rank_policies')->comment('등급 번호');
            $table->foreignId('transfer_id')->constrained('income_transfers')->comment('내역 번호');
            $table->decimal('self_sales', 20, 9)->default(0)->comment('개인매출');
            $table->decimal('group_sales', 20, 9)->default(0)->comment('그룹매출');
            $table->decimal('bonus', 20, 9)->default(0)->comment('수익');
            $table->unsignedInteger('referral_count')->default(0)->comment('직추천 인원');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rank_bonuses');
    }
};
