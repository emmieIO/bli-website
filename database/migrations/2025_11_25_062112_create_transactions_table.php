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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('transaction_id')->unique(); // Flutterwave transaction ID
            $table->string('flw_ref')->unique(); // Flutterwave reference
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('status')->default('pending'); // pending, successful, failed
            $table->string('payment_type')->nullable(); // card, bank_transfer, etc.
            $table->json('metadata')->nullable(); // Store additional Flutterwave data
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('course_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
