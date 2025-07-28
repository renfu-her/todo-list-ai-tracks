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
        Schema::table('todos', function (Blueprint $table) {
            // 先刪除外鍵約束
            $table->dropForeign(['user_id']);
            // 將 user_id 改為 JSON 類型
            $table->json('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('todos', function (Blueprint $table) {
            // 恢復為外鍵
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->change();
        });
    }
};
