<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('live_exam_attempts', function (Blueprint $table) {
            $table->unique(['live_exam_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('live_exam_attempts', function (Blueprint $table) {
            $table->dropUnique(['live_exam_id', 'user_id']);
        });
    }
};
