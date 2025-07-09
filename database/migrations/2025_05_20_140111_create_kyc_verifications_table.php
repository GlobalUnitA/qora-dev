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
        Schema::create('kyc_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->comment('회원 번호');
            $table->enum('type', ['id_card', 'passport', 'driver_license'])->comment('인증 타입');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->comment('인증 상태');
            $table->string('nationality', 50)->comment('국가');
            $table->string('given_name', 255)->comment('본명');
            $table->string('surname', 255)->comment('성');
            $table->string('id_number', 255)->comment('신분증 번호');
            $table->json('image_urls')->comment('이미지 경로');
            $table->timestamp('date_of_birth')->comment('생년월일');
            $table->string('memo', 255)->nullable()->comment('관리자 메모');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kyc_verifications');
    }
};
