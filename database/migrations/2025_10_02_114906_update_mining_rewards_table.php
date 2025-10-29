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
            $table->date('start_date')->nullable()->comment('시작일')->after('reward_date');
            $table->date('end_date')->nullable()->comment('종료일')->after('started_at');
            $table->unsignedInteger('profit_count')->default(0)->comment('분할 수익 지급 횟수')->after('end_date');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mining_rewards', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date', 'profit_count']);
        });
    }
};
