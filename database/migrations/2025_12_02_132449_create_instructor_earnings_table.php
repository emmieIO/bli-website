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
        Schema::create('instructor_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('gross_amount', 10, 2); // Full course price
            $table->decimal('platform_fee', 10, 2); // Platform commission
            $table->decimal('net_amount', 10, 2); // Amount instructor receives
            $table->decimal('platform_fee_percentage', 5, 2)->default(20.00); // Platform commission %
            $table->string('currency', 3)->default('NGN');
            $table->enum('status', ['pending', 'available', 'paid', 'refunded'])->default('pending');
            $table->timestamp('available_at')->nullable(); // When funds become available for payout
            $table->timestamp('paid_at')->nullable();
            $table->unsignedBigInteger('payout_id')->nullable(); // Will add foreign key constraint after instructor_payouts table exists
            $table->timestamps();

            // Indexes for performance
            $table->index('instructor_id');
            $table->index('status');
            $table->index(['instructor_id', 'status']);
            $table->index('available_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructor_earnings');
    }
};
