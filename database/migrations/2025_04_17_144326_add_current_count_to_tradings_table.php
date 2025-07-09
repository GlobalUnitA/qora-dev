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
        Schema::table('tradings', function (Blueprint $table) {
            $table->unsignedInteger('current_count')->default(0)->comment('트레이딩 횟수')->after('daily');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tradings', function (Blueprint $table) {
            $table->dropColumn('current_count');
        });
    }
};
