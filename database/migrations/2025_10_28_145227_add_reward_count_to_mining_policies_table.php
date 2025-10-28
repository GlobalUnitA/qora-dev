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
            $table->unsignedInteger('reward_limit')->default(0)->after('reward_days')->comment('채굴 제한');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mining_policies', function (Blueprint $table) {
            $table->dropColumn('reward_limit');
        });
    }
};
