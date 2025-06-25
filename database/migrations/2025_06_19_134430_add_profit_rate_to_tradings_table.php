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
            $table->decimal('profit_rate', 20, 9)->after('daily')->nullable()->comment('수익률');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tradings', function (Blueprint $table) {
             $table->dropColumn('profit_rate');
        });
    }
};
