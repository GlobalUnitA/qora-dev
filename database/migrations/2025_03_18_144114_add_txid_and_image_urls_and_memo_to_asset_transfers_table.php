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
        Schema::table('asset_transfers', function (Blueprint $table) {
            $table->string('txid', 50)->nullable()->comment('txid')->after('after_balance');
            $table->json('image_urls')->nullable()->comment('이미지 링크')->after('txid');  
            $table->string('memo', 255)->nullable()->comment('관리자 메모')->after('image_urls'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_transfers', function (Blueprint $table) {
            $table->dropColumn(['txid', 'image_urls', 'memo']);
        });
    }
};
