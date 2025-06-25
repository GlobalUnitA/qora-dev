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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->comment('회원 번호');
            $table->bigInteger('parent_id')->nullable()->comment('추천 회원 번호');
            $table->tinyInteger('level')->default(1)->comment('회원 레벨');
            $table->tinyInteger('vip_level')->default(1)->comment('vip 레벨');
            $table->string('email', 100)->comment('이메일');
            $table->string('phone', 20)->comment('전화 번호');
            $table->string('post_code', 10)->nullable()->comment('우편 번호');
            $table->string('address', 255)->nullable()->comment('주소');
            $table->string('detail_address', 255)->nullable()->comment('상세 주소');
            $table->string('meta_uid', 30)->nullable()->comment('메타웨이브 유아이디');
            $table->enum('is_valid', ['n', 'y'])->default('n')->comment('유효 계정 여부');
            $table->enum('is_frozen', ['n', 'y'])->default('n')->comment('계좌 동결 여부');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
