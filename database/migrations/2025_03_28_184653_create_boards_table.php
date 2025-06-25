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
        Schema::create('boards', function (Blueprint $table) {
            $table->id();
            $table->string('board_code', 20)->comment('게시판 코드');
            $table->string('board_name', 50)->comment('게시판 이름');
            $table->enum('is_comment', ['n', 'y'])->default('n')->comment('답글 여부');
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
        Schema::dropIfExists('boards');
    }
};
