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
        Schema::create('mining_daily_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('policy_id')->constrained('mining_policies');
            $table->date('stat_date');
            $table->decimal('exchange_rate', 20, 9)->default(0)->comment('환율');
            $table->decimal('node_amount', 20, 9)->default(0)->comment('1일 채굴값');
            $table->timestamps();

            $table->unique(['policy_id', 'stat_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mining_daily_stats');
    }
};
