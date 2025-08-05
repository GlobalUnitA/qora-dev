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
        Schema::create('rank_policies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_id')->constrained('user_grades')->onDelete('cascade');
            $table->decimal('self_sales', 20, 9)->default(0)->comment('개인매출 추가자격');
            $table->decimal('bonus', 20, 9)->default(0)->comment('보너스');
            $table->json('conditions')->nullable()->comment('조건');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rank_policies');
    }
};
