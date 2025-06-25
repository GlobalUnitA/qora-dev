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
        Schema::table('staking_policies', function (Blueprint $table) {
            $table->foreignId('coin_id')->constrained('coins')->comment('코인 번호')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staking_policies', function (Blueprint $table) {
            $table->dropColumn('coin_id');
        });
    }
};
