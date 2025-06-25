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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->comment('회원 번호');
            $table->foreignId('board_id')->constrained('boards')->comment('게시판 번호');
            $table->string('subject', 255)->comment('제목');
            $table->longText('content')->comment('내용');
            $table->enum('is_popup', ['n', 'y'])->default('n')->comment('팝업 여부');
            $table->enum('is_banner', ['n', 'y'])->default('n')->comment('배너 여부');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
