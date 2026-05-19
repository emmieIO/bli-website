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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('thumbnail_path')->nullable();
            $table->string('preview_video_id')->nullable();
            $table->string('language')->nullable();
            $table->string('level');
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('instructor_id')->constrained('users')->cascadeOnDelete();
            $table->string('status')->default('draft');
            $table->boolean('is_free')->default(false);
            $table->decimal('price', 8, 2)->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('is_free');
            $table->index(['status', 'instructor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
