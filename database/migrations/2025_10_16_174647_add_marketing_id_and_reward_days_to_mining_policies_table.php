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
        Schema::table('mining_policies', function (Blueprint $table) {
            $table->foreignId('marketing_id')->nullable()->constrained('marketings')->after('id');
            $table->string('reward_days', 50)->nullable()->after('node_limit')->comment('채굴 가능 요일');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mining_policies', function (Blueprint $table) {
            $table->dropForeign(['marketing_id']);
            $table->dropColumn(['marketing_id', 'reward_days']);

        });
    }
};
