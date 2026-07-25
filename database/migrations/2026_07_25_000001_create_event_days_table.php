<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('position')->default(1);
            $table->string('title')->nullable();
            $table->string('theme')->nullable();
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->string('mode');
            $table->string('venue_name')->nullable();
            $table->string('physical_address')->nullable();
            $table->string('meeting_link', 2048)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['event_id', 'start_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_days');
    }
};
