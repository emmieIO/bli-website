<?php

use App\Enums\EventStatus;
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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('theme');
            $table->text('description');
            $table->string('program_cover')->nullable();
            $table->string('mode');
            $table->string('location')->nullable()->comment('This can also serve as the meeting URL');
            $table->unsignedInteger('attendee_slots')->nullable();
            $table->string('physical_address')->nullable();
            $table->string('venue')->nullable();
            $table->string('contact_email')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();
            $table->string('status')->default(EventStatus::DRAFT->value);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_published')->default(false);
            $table->boolean('is_allowing_application')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->decimal('entry_fee', 10, 2)->default(0);
            $table->json('metadata')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('status');
            $table->index(['status', 'start_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
