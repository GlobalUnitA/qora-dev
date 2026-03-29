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
        Schema::table('mining_policies', function (Blueprint $table) {
            $table->unsignedInteger('node_min_limit')->default(0)->after('node_limit')->comment('최소 노드 수량');
            $table->enum('is_hidden', ['n','y'])->default('n')->after('waiting_period')->comment('상품 숨김 여부');
            $table->enum('is_refundable', ['n','y'])->default('n')->after('is_hidden')->comment('코인 반환 여부');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mining_policies', function (Blueprint $table) {
            $table->dropColumn(['node_min_limit', 'is_hidden', 'is_refundable']);
        });
    }
};
