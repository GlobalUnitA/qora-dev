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
        Schema::create('referral_policies', function (Blueprint $table) {
            $table->id();

            $table->foreignId('grade_id')->constrained('user_grades')->onDelete('cascade');

            for ($i = 1; $i <= 21; $i++) {
                $table->decimal("level_{$i}_rate", 20, 9)->default(0);
            }

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_policies');
    }
};
