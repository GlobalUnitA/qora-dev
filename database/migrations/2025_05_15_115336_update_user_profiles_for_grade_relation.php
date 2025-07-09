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
        Schema::table('user_profiles', function (Blueprint $table) {

            $table->dropColumn('vip_level');

            $table->unsignedBigInteger('grade_id')->default(1)->nullable()->comment('회원 등급')->after('level');
            $table->foreign('grade_id')->references('id')->on('user_grades')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropForeign(['grade_id']);
            $table->dropColumn('grade_id');

            $table->unsignedTinyInteger('vip_level')->default(1)->comment('회원 레벨')->after('user_id');
        });
    }
};
