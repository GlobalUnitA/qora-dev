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
            $table->timestamp('maturity_at')->nullable()->comment('상환 예정일')->after('ended_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('minings', function (Blueprint $table) {
            $table->dropColumn('maturity_at');
        });
    }
};
