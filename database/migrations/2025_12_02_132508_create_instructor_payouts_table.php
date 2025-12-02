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
        Schema::create('instructor_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained('users')->onDelete('cascade');
            $table->string('payout_reference')->unique(); // Unique reference for tracking
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('NGN');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled'])->default('pending');
            // Note: PayPal not included as it doesn't work in Nigeria
            $table->enum('payout_method', ['bank_transfer', 'payoneer', 'manual'])->default('bank_transfer');

            // Bank details (for bank transfers - primary method in Nigeria)
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_name')->nullable();
            $table->string('bank_code')->nullable(); // Nigerian bank code

            // Alternative payout details
            $table->string('payout_email')->nullable(); // For Payoneer if needed
            $table->text('payout_details')->nullable(); // Additional JSON details

            // Processing information
            $table->text('notes')->nullable(); // Admin notes
            $table->string('external_reference')->nullable(); // Reference from payment processor
            $table->text('failure_reason')->nullable();

            // Timestamps
            $table->timestamp('requested_at');
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('instructor_id');
            $table->index('status');
            $table->index(['instructor_id', 'status']);
            $table->index('requested_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructor_payouts');
    }
};
