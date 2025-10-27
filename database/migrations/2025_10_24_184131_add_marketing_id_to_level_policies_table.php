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
        Schema::table('level_policies', function (Blueprint $table) {
            $table->foreignId('marketing_id')->nullable()->constrained('marketings')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('level_policies', function (Blueprint $table) {
            $table->dropForeign(['marketing_id']);
            $table->dropColumn('marketing_id');
        });
    }
};
