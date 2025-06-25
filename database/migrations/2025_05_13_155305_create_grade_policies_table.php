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
        Schema::create('grade_policies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_id')->constrained('user_grades')->comment('등급 번호');
            $table->decimal('base_sales', 20, 9)->default(0)->comment('기본 매출');
            $table->decimal('self_sales', 20, 9)->default(0)->comment('개인 매출');
            $table->decimal('group_sales', 20, 9)->default(0)->comment('그룹 매출');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_policies');
    }
};
