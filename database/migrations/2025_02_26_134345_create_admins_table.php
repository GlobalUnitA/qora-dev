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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('이름');
            $table->string('account')->unique()->comment('계정');
            $table->string('password')->comment('비밀번호');
            $table->rememberToken()->nullable()->comment('토큰');
            $table->tinyInteger('admin_level')->comment('관리자 등급');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
