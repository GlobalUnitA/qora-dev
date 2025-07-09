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
        
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('posts', function (Blueprint $table) {
            
            $table->unsignedBigInteger('user_id')->nullable()->change();
            $table->unsignedBigInteger('admin_id')->nullable()->after('user_id');
        });

        Schema::table('posts', function (Blueprint $table) {
         
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
          
            $table->dropForeign(['user_id']);
            $table->dropForeign(['admin_id']);

            $table->dropColumn('admin_id');

            $table->unsignedBigInteger('user_id')->nullable(false)->change();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
