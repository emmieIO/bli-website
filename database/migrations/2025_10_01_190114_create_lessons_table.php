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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('course_modules', 'id')->cascadeOnDelete();
            $table->string('title');
            $table->string('type', 0)->nullable();
            $table->text('description')->nullable();

            // For video lessons
            $table->string('vimeo_id')->nullable();
            $table->string('preview_vimeo_id')->nullable();
            $table->boolean('is_preview')->default(false);

            // For resources
            $table->string('content_path')->nullable(); // pdf path or assignment file

            // For assignments
            $table->longText('assignment_instructions')->nullable();

            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
