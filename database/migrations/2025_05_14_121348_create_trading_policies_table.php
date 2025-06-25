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
        Schema::create('trading_policies', function (Blueprint $table) {
            $table->id();
            $table->decimal('profit_rate', 20, 9)->comment('수익률');
            $table->unsignedInteger('trading_count')->comment('트레이딩 횟수');
            $table->string('trading_days', 50)->nullable()->comment('트레이딩 가능 요일');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trading_policies');
    }
};
