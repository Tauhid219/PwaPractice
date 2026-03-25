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
        Schema::table('questions', function (Blueprint $table) {
            $table->index(['category_id', 'level_id']);
        });

        Schema::table('user_progress', function (Blueprint $table) {
            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'category_id', 'level_id']);
        });

        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->index(['user_id', 'level_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropIndex(['category_id', 'level_id']);
        });

        Schema::table('user_progress', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['user_id', 'category_id', 'level_id']);
        });

        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'level_id']);
        });
    }
};
