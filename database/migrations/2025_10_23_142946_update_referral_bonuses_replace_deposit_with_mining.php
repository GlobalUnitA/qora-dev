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
        Schema::table('referral_bonuses', function (Blueprint $table) {
            $table->dropColumn('deposit_id');
            $table->unsignedBigInteger('mining_id')->after('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('referral_bonuses', function (Blueprint $table) {
            $table->dropColumn('mining_id');
            $table->unsignedBigInteger('deposit_id')->after('user_id')->nullable();
        });
    }
};
