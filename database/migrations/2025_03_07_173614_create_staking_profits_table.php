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
        Schema::create('staking_profits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staking_id')->constrained('stakings')->comment('스테이킹 번호');
            $table->decimal('profit', 20, 9)->default(0)->comment('수익');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staking_profits');
    }
};
