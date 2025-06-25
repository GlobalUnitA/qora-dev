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
        Schema::table('stakings', function (Blueprint $table) {
            $table->foreignId('staking_id')->constrained('staking_policies')->comment('스테이킹 번호')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stakings', function (Blueprint $table) {
            $table->dropColumn('staking_id');
        });
    }
};
