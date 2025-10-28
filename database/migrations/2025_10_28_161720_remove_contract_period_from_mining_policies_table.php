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
            $table->dropColumn('contract_period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mining_policies', function (Blueprint $table) {
            $table->unsignedInteger('contract_period')->nullable()->comment('상환 기간')->after('waiting_period');
        });
    }
};
