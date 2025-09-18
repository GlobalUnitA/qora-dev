<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE income_transfers MODIFY COLUMN type ENUM('deposit','withdrawal', 'mining_reward', 'rank_bonus', 'referral_bonus', 'referral_matching', 'level_bonus', 'level_matching') DEFAULT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE income_transfers MODIFY COLUMN type ENUM('deposit', 'withdrawal', 'mining_reward', 'referral_bonus', 'referral_matching', 'rank_bonus') DEFAULT NULL");
    }
};
