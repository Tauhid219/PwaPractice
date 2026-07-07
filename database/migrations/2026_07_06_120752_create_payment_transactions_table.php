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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_order_id')->constrained()->cascadeOnDelete();
            $table->string('provider')->default('sslcommerz');
            $table->string('provider_reference')->nullable(); // SSLCommerz trans_id
            $table->decimal('amount', 8, 2)->nullable();
            $table->string('currency', 3)->nullable();
            $table->string('status')->default('initiated'); // initiated, paid, failed, cancelled
            $table->json('payload')->nullable(); // Raw response
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
