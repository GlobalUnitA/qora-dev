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
        Schema::create('level_condition_policies', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('node_amount')->unique()->comment('노드 참여 수량');
            $table->unsignedInteger('max_depth')->comment('최대 적용 뎁스');
            $table->unsignedInteger('referral_count')->comment('추천인원 수');
            $table->enum('condition', ['or', 'and'])->comment('조건 조합 방식');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('level_condition_policies');
    }
};
