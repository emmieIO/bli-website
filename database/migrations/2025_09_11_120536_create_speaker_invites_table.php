<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('speaker_invites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events', 'id')->cascadeOnDelete();
            $table->foreignId('speaker_id')->nullable()->constrained('speakers', 'id')->nullOnDelete();
            $table->string('email')->nullable();
            $table->string('suggested_topic')->nullable();
            $table->integer('suggested_duration')->nullable();
            $table->text('audience_expectations')->nullable();
            $table->string('expected_format')->nullable();
            $table->text('special_instructions')->nullable();
            $table->string('status')->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('speaker_invites');
    }
};
