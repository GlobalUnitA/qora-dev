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
        Schema::create('user_grades', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('등급 이름');              
            $table->unsignedInteger('level')->unique()->comment('등급 순서');      
            $table->text('description')->nullable()->comment('등급 설명');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_grades');
    }
};
