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
            // Make category_id nullable to support standalone live exam questions
            $table->foreignId('category_id')->nullable()->change();
            
            // Add option_4 for 4-option MCQ support
            $table->string('option_4')->nullable()->after('option_3');
            
            // Add correct_answers JSON column for storing spelling variations
            $table->json('correct_answers')->nullable()->after('answer_text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable(false)->change();
            $table->dropColumn(['option_4', 'correct_answers']);
        });
    }
};
