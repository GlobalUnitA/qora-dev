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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_id')->constrained('boards')->onDelete('cascade')->comment('게시판 번호');
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade')->comment('게시글 번호');
            $table->unsignedInteger('tab')->default(1)->comment('댓글 순번');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->nullable()->comment('회원 번호');
            $table->foreignId('admin_id')->constrained('admins')->onDelete('cascade')->nullable()->comment('관리자 번호');
            $table->text('content')->comment('내용');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
