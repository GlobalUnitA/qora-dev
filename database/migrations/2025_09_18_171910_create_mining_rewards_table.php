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
        Schema::create('mining_rewards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('mining_id')->constrained('minings');
            $table->foreignId('transfer_id')->constrained('income_transfers');
            $table->enum('type', ['daily', 'instant'])->comment('지급 방식');
            $table->decimal('reward', 20, 9)->default(0)->comment('수익');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mining_rewards');
    }
};
