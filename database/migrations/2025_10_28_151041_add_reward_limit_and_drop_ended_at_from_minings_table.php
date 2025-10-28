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
        Schema::table('minings', function (Blueprint $table) {
            $table->unsignedInteger('reward_limit')->default(0)->after('reward_count')->comment('체굴 제한');
            $table->dropColumn('ended_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('minings', function (Blueprint $table) {
            $table->dropColumn('reward_limit');
            $table->timestamp('ended_at')->nullable()->after('started_at')->comment('종료일');
        });
    }
};
