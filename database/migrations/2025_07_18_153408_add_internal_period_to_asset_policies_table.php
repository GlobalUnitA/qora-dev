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
        Schema::table('asset_policies', function (Blueprint $table) {
            $table->integer('internal_period')->comment('내부이체 반영 기간')->after('deposit_period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_policies', function (Blueprint $table) {
            $table->dropColumn('internal_period');
        });
    }
};
