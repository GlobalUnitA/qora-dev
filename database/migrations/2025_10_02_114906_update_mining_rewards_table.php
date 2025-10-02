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
        Schema::table('mining_rewards', function (Blueprint $table) {
            $table->unsignedInteger('profit_count')->default(0)->comment('수익 지급 횟수')->after('reward_date');
            $table->timestamp('started_at')->nullable()->comment('시작일')->after('profit_count');
            $table->timestamp('ended_at')->nullable()->comment('종료일')->after('started_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mining_rewards', function (Blueprint $table) {
            $table->dropColumn(['profit_count', 'started_at', 'ended_at']);
        });
    }
};
