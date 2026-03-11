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
        Schema::table('user_progress', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropUnique(['user_id', 'level_id']);
            $table->foreignId('category_id')->after('user_id')->constrained()->cascadeOnDelete();
            $table->unique(['user_id', 'category_id', 'level_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_progress', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'category_id', 'level_id']);
            $table->dropConstrainedForeignId('category_id');
            $table->unique(['user_id', 'level_id']);
        });
    }
};
