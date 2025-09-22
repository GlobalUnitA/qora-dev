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
            $table->unsignedInteger('waiting_period')->default(0)->comment('대기기간')->after('node_limit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mining_policies', function (Blueprint $table) {
            $table->dropColumn('waiting_period');
        });
    }
};
