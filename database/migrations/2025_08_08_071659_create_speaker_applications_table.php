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
        Schema::create('speaker_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('speaker_id')->nullable()->constrained()->nullOnDelete();

            // Application details
            $table->string('topic_title');
            $table->text('topic_description');
            $table->string('session_format'); // Use enum class in model, store as string
            $table->text('notes')->nullable();

            // Status tracking
            $table->string('status')->default('pending'); // Use enum class in model, store as string
            $table->text('feedback')->nullable();

            // Timestamps
            $table->timestamps();
            $table->timestamp('reviewed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('speaker_applications');
    }
};
